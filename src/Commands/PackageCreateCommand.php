<?php

namespace Shae\PackageBuilder\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class PackageCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get some data to create a package
        $vendor_name  = mb_strtolower($this->ask('Package vendor name?'));
        $package_name = mb_strtolower($this->ask('Package name?'));
        $distance_dir = base_path(config('packageBuilder.packages_dir'));
        $package_path = $distance_dir . DIRECTORY_SEPARATOR . $vendor_name . DIRECTORY_SEPARATOR . $package_name;

        while (file_exists($package_path)) {
            $package_name = $this->ask('Package with such name already exists...Maybe another name?');
            $package_path = $distance_dir . DIRECTORY_SEPARATOR . $vendor_name . DIRECTORY_SEPARATOR . $package_name;
        }
        // =========================================================

        // Create folders tree
        foreach (config('packageBuilder.folders_structure') as $key => $value) {
            if (is_array($value) && $key === 'src') {
                foreach ($value as $_value) {
                    if ($this->createDirectory($package_path . DIRECTORY_SEPARATOR . $key . DIRECTORY_SEPARATOR . $_value)) {
                        $this->info("Directory for {$_value} created!");
                    } else {
                        $this->warn("Directory for {$_value} created failure!");
                    }
                }
            } else {
                if ($this->createDirectory($package_path . DIRECTORY_SEPARATOR . $value)) {
                    $this->info("Directory for {$value} created!");
                } else {
                    $this->warn("Directory for {$value} created failure!");
                }
            }
        }
        // =========================================================

        // Create content for files
        $composer_json        = [
            'name'              => $vendor_name . '/' . $package_name,
            'description'       => '',
            'type'              => 'library',
            'authors'           => [],
            'license'           => 'Apache-2.0',
            'minimum-stability' => 'stable',
            'autoload'          => [
                'psr-4' => [
                    ucfirst($vendor_name) . '\\' . ucfirst($package_name) . '\\' => 'src/',
                ],
                'files' => [
                    'src/helpers.php',
                ],
            ],
            'extra'             => [
                'laravel' => [
                    'providers' => [
                        ucfirst($vendor_name) . '\\' . ucfirst($package_name) . '\\ServiceProvider',
                    ],
                    'aliases'   => [
                        'aliases' => ucfirst($vendor_name) . '\\' . ucfirst($package_name) . '\\Facade',
                    ],
                ],
            ],
        ];

        $serviceProvider_file = str_replace("{namespace}", ucfirst($vendor_name) . '\\' . ucfirst($package_name), getStub('ServiceProvider.php'));
        $serviceProvider_file = str_replace("{name}", $package_name, $serviceProvider_file);
        $serviceProvider_file = str_replace("{classname}", ucfirst($package_name), $serviceProvider_file);

        $facade_file          = str_replace("{namespace}", ucfirst($vendor_name) . '\\' . ucfirst($package_name), getStub('Facade.php'));
        $facade_file          = str_replace("{name}", $package_name, $facade_file);

        $package_class_file   = str_replace("{namespace}", ucfirst($vendor_name) . '\\' . ucfirst($package_name), getStub('PackageClass.php'));
        $package_class_file   = str_replace("{classname}", ucfirst($package_name), $package_class_file);
        // =========================================================

        // Create files
        $filesystem = app(Filesystem::class);

        $filesystem->put($package_path . DIRECTORY_SEPARATOR . 'composer.json', json_encode($composer_json, JSON_UNESCAPED_SLASHES));
        $filesystem->put($package_path . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'ServiceProvider.php', $serviceProvider_file);
        $filesystem->put($package_path . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Facade.php', $facade_file);
        $filesystem->put($package_path . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'helpers.php', getStub('helpers.php'));
        $filesystem->put($package_path . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . ucfirst($package_name) . '.php', $package_class_file);
        $filesystem->put($package_path . DIRECTORY_SEPARATOR . 'license', '');
        $filesystem->put($package_path . DIRECTORY_SEPARATOR . 'license', '');
        $filesystem->put($package_path . DIRECTORY_SEPARATOR . 'README.md', '');
        $filesystem->put($package_path . DIRECTORY_SEPARATOR . '.gitignore', implode(PHP_EOL, config('packageBuilder.gitignore')));
        // =========================================================

        $this->info('Package "' . $vendor_name . '/' . $package_name . '" successfully created... Enjoy!');
    }

    public function createDirectory(string $path, int $option = 0777, $recursive = true): bool
    {
        if (file_exists($path) && is_dir($path)) {
            return false;
        }

        return app(Filesystem::class)->makeDirectory($path, $option, $recursive);
    }
}