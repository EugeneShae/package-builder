<?php

return [

    'packages_dir' => 'packages',

    'folders_structure' => [
        'src'                => [
            'Models',
            'Http\\Controllers',
            'Http\\Middleware',
            'Console',
            'Exceptions',
            'Database\\Seeds',
        ],
        'default_config'     => 'config',
        'routes'             => 'routes',
        'resources'          => 'resources',
        'views'              => 'resources/views',
        'assets'             => 'resources/assets',
        'publishable'        => 'publishable',
        'tests'              => 'tests',
        'migrations'         => 'database/migrations',
        'factories'          => 'database/factories',
        'publishable_lang'   => 'publishable/lang',
        'publishable_assets' => 'publishable/assets',
    ],

    'gitignore' => [
        '/node_modules',
        '.DS_Store',
        '.idea',
        '/vendor',
        'build',
        '.lock',
        'composer.lock',
        'composer.phar',
        'package-lock.json',
    ],
];