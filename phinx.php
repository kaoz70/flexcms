<?php

if(file_exists(__DIR__ . '/.env')) {
    require __DIR__ . '/flexcms/vendor/autoload.php';
    $loader = new josegonzalez\Dotenv\Loader(__DIR__ . '/.env');
    $loader->parse();
    $loader->toEnv();
}

return
    [
        'paths' => [
            'migrations' => '%%PHINX_CONFIG_DIR%%/flexcms/database/migrations',
            'seeds' => '%%PHINX_CONFIG_DIR%%/flexcms/database/seeds',
        ],
        'environments' =>
            [
                'default_database' => 'environment',
                'default_migration_table' => 'phinxlog',
                'environment'      =>
                    [
                        'adapter' => 'mysql',
                        'host' => $_ENV['DB_HOST'],
                        'name' => $_ENV['DB_NAME'],
                        'user' => $_ENV['DB_USERNAME'],
                        'pass' => $_ENV['DB_PASSWORD'],
                        'port' => 3306,
                        'charset' => 'utf8',
                        'collation' => 'utf8_unicode_ci',
                    ],
            ],
    ];