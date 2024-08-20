<?php

namespace Appliance\Commands\Publisher;

use Ephect\Framework\CLI\Console;
use Ephect\Framework\CLI\ConsoleColors;
use Ephect\Framework\CLI\Enums\ConsoleOptionsEnum;
use Ephect\Framework\Commands\AbstractCommandLib;
use Ephect\Framework\Templates\TemplateMaker;
use Ephect\Framework\Utils\File;
use Ephect\Plugins\WebComponent\Builder\Compiler;
use Exception;

class Lib extends AbstractCommandLib
{

    public function publish(string $source): void
    {
        try {

            [$tagName, $templatesFolder, $packageName, $namespace, $description, $isComponentBuilder, $entrypoint, $version] = $this->readLine();

            $destDir = siteRoot();

            $this->copyTemplates($tagName, $templatesFolder, $packageName, $namespace, $description, $entrypoint, $version,);

            $source = realpath($source . DIRECTORY_SEPARATOR . CONFIG_APP . DIRECTORY_SEPARATOR . 'Assets');
            Console::writeLine("Source: %s", $source);
            $siteRoot = siteRoot();

            $allFiles = File::walkTreeFiltered($source);
            foreach ($allFiles as $file) {
                Console::writeLine("Publishing file: %s%s", $siteRoot, $file);
                if(!is_file($siteRoot . $file)) {
                    File::safeMkDir(dirname($siteRoot . $file));
                }
                if(is_file($siteRoot . $file)) {
                    $dirname = dirname($siteRoot . $file);
                    $basename = pathinfo($file, PATHINFO_BASENAME);
                    copy($source . $file, $dirname . DIRECTORY_SEPARATOR . 'ephect-toolbox_' . $basename);
                } else {
                    copy($source . $file, $siteRoot . $file);
                }
            }

            Console::writeLine(ConsoleColors::getColoredString("Module ", ConsoleColors::BLUE) . "%s" .  ConsoleColors::getColoredString(" is available in:", ConsoleColors::BLUE), $namespace);
            Console::writeLine("%s", $destDir);
        } catch (Exception $ex) {
            Console::error($ex, ConsoleOptionsEnum::ErrorMessageOnly);
        }
    }

    private function readLine(): array
    {
        $tagName = '';
        $templatesFolder = '!';

        Console::writeLine(ConsoleColors::getColoredString("Creating a new module.", ConsoleColors::LIGHT_BLUE));
        Console::writeLine(ConsoleColors::getColoredString("Please, answer the following questions.", ConsoleColors::BLUE));
        Console::writeLine(ConsoleColors::getColoredString("Leave the answer blank to pass to the next question or to abort the process.", ConsoleColors::BROWN));

        /**
         * Asking for the package name.
         */
        $packageName = Console::readLine("Module name for registration in Composer (kebab-case, eg: vendor-name/package-name): ");
        if (trim($packageName) == '') {
            throw new Exception("Module name must not be empty");
        }

        /**
         * Asking for the package namespace.
         */
        $namespace = Console::readLine("Module namespace map to src folder (PascalCase, eg: VendorName\PackageName): ");
        if (trim($namespace) == '') {
            throw new Exception("Module namespace must not be empty");
        }

        /**
         * Asking for description.
         */
        $description = Console::readLine("Module short description: ");

        $isComponentBuilder = Console::readYesOrNo("Is the module a component builder?");

        if($isComponentBuilder) {
            /**
             * Asking the tag name.
             */
            $tagName = Console::readLine("Tag name (kebab-case), you can leave: ");
            $tagName =  strtolower($tagName);
            if (trim($tagName) == '') {
                throw new Exception("WebComponent tag name must not be empty");
            }

            /**
             * Asking for the templates' folder.
             */
            $templatesFolder = Console::readLine("Module templates' folder (PascalCase, eg: Components): ");
            if (trim($templatesFolder) == '') {
                $templatesFolder = '!';
            }
        }

        /**
         * Asking for entrypoint.
         */
        $entrypoint = Console::readLine("Module entrypoint class (PamelCase, can be set afterwards): ");
        if (trim($entrypoint) == '') {
            $entrypoint = null;
        }

        /**
         * Asking for version.
         */
        $version = Console::readLine("Module short version: ");

        return [$tagName, $templatesFolder, $packageName, $namespace, $description, $isComponentBuilder, $entrypoint, $version];
    }

    private function copyTemplates(
        string $tagName,
        string $templatesFolder,
        string $packageName,
        string $namespace,
        string $description,
        ?string $entrypoint,
        string $version,
    )
    {
        $templatesDir = MODULE_SRC_DIR . 'Templates' . DIRECTORY_SEPARATOR;
        $destDir = siteRoot();

        $snakeCasePackageName = str_replace('/', '_', $packageName);

        $composerTemplate = new TemplateMaker($templatesDir . "composer_json.tpl");
        $composerTemplate->make([
            'fqPackageName' => $packageName,
            'fqPackageNamespace' => $namespace,
            'snakeCasePackageName' => $snakeCasePackageName,
            'description' => $description,
            'version' => $version,
        ]);
        $composerTemplate->save($destDir . "composer.json");

        $manifestPhpTemplate = new TemplateMaker($templatesDir . "manifest_php.tpl");
        $manifestPhpTemplate->make([
            'tagName' => $tagName,
            'fqPackageName' => $packageName,
            'endpointClass' => $entrypoint,
            'templatesFolder' => $templatesFolder,
            'description' => $description,
            'version' => $version,
        ]);
        $manifestPhpTemplate->save($destDir . REL_CONFIG_DIR . "manifest.php");

        $unixScriptTemplate = new TemplateMaker($templatesDir . "unix_install_sh.tpl");
        $unixScriptTemplate->make([
            'unixPackageFolder' => $packageName,
        ]);
        $unixScriptTemplate->save($destDir . 'bin' . DIRECTORY_SEPARATOR . $snakeCasePackageName . "_install.sh");

        $windowsScriptTemplate = new TemplateMaker($templatesDir . "windows_install_bat.tpl");
        $windowsScriptTemplate->make([
            'unixPackageFolder' => $packageName,
        ]);
        $windowsScriptTemplate->save($destDir . 'bin' . DIRECTORY_SEPARATOR . $snakeCasePackageName . "_install.bat");

    }
}

