@echo off

cd vendor\{{windowsPackageFolder}}
php use install:module %cd% %1 %2
