<?php

namespace Appliance\Commands\Publisher;

use Ephect\Framework\CLI\Console;
use Ephect\Framework\Commands\AbstractCommand;
use Ephect\Framework\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "install", subject: "toolbox")]
#[CommandDeclaration(desc: "Publish toolbox scripts files in Ephect application root")]
class Main extends AbstractCommand
{
    public function run(): int
    {
        $source = $this->application->getArgi(2);

        if(empty($source)) {
            Console::writeLine("Argument source is required");
            return 1;
        }

        $lib = new Lib($this->application);
        $lib->publish($source);

        return 0;
    }
}
