@echo off

cd vendor\vendor_name\package_name
php use install:plugin %cd% %1 %2
