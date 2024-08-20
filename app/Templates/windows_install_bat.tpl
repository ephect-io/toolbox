@echo off

cd vendor\{{windowsPackageFolder}}
php use install:plugin %cd% %1 %2
