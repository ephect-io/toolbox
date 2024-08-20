#!/usr/bin/env sh

cd vendor/{{unixPackageFolder}}
php use install:plugin $(pwd) $1 $2