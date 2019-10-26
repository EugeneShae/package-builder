<?php

if (!function_exists('package_builder_path')){
    function package_builder_path(string $path = ''): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $path;
    }
}

if (!function_exists('getStub')){
    function getStub(string $name): string
    {
        return file_get_contents(package_builder_path('stubs' . DIRECTORY_SEPARATOR . $name . '.stub'));
    }
}