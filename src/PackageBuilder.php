<?php

namespace Shae\PackageBuilder;

class PackageBuilder
{
    public function getStub(string $name): string
    {
        return file_get_contents(package_builder_path('stubs' . DIRECTORY_SEPARATOR . $name . '.stub'));
    }
}