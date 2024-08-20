<?php

namespace Appliance\Commands\Publisher;

use Ephect\Framework\CLI\Console;
use Ephect\Framework\Commands\AbstractCommandLib;
use Ephect\Framework\Utils\File;

class Lib extends AbstractCommandLib
{

    public function publish(string $source): void
    {
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
    }
}

