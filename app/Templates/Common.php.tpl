<?php

namespace {{namespace}};

use Ephect\Framework\Modules\ModuleManifestEntity;
use Ephect\Framework\Modules\ModuleManifestReader;

final class Common
{
    public static function getModuleDir()
    {
        return  dirname(__DIR__, 2) . DIRECTORY_SEPARATOR;
    }

    public static function getModuleSrcDir()
    {
        return  dirname(__DIR__) . DIRECTORY_SEPARATOR;
    }

    public static function getModuleManifest(): ModuleManifestEntity
    {
        $manifestReader = new ModuleManifestReader();
        return $manifestReader->read(Common::getModuleDir());
    }
}