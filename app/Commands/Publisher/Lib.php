<?php

namespace Appliance\Commands\Publisher;

use Ephect\Framework\CLI\Console;
use Ephect\Framework\Commands\AbstractCommandLib;
use Ephect\Framework\Utils\File;

class Lib extends AbstractCommandLib
{

    public function publish(string $source): void
    {
        $source = realpath($source . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Assets');
        Console::writeLine("Source: %s", $source);

        $allFiles = File::walkTreeFiltered($source);
        foreach ($allFiles as $file) {
            Console::writeLine("Publishing file: %s", $file);
        }
    }
}

