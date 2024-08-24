<?php

namespace {{namespace}};

class Common
{
    public static function getModuleSrcDir()
    {
        return  dirname(__DIR__) . DIRECTORY_SEPARATOR;
    }
}