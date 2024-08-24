#!/usr/bin/env sh

cd vendor/{{unixPackageFolder}}
php use install:module $(pwd) $1 $2