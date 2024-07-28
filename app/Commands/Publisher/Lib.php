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

        $vendorPos = strpos( SITE_ROOT, 'vendor');
        $vendorPath = SITE_ROOT;

        if($vendorPos > -1) {
            $vendorPath = substr(SITE_ROOT, 0, $vendorPos - 1);
        }
        $allFiles = File::walkTreeFiltered($source);
        foreach ($allFiles as $file) {
            Console::writeLine("Publishing file: %s%s", $vendorPath, $file);
            if(!is_file($vendorPath . $file)) {
                File::safeMkDir(dirname($vendorPath . $file));
            }
            if(is_file($vendorPath . $file)) {
                $dirname = dirname($vendorPath . $file);
                $filename = pathinfo($file, PATHINFO_FILENAME);
                copy($file, $dirname . DIRECTORY_SEPARATOR . 'ephect-toolbox_' . $filename);
            } else {
                copy($file, $vendorPath . $file);
            }
        }
    }
}

