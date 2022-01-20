<?php

/*
|--------------------------------------------------------------------------
| Generate `composer-dev.json` and `composer-dev.lock` [API-118]
|--------------------------------------------------------------------------
|
| This script creates `composer-dev` files, which are used to symlink
| `vendor/aic/data-hub-foundation` to an existing local copy of the
| foundation repo. To use it, add this to your `composer.json`:
|
|     {
|         "scripts": {
|             "foundation": [
|                 "php vendor/aic/data-hub-foundation/composer-dev.php",
|                 "COMPOSER=composer-dev.json composer update aic/data-hub-foundation"
|             ]
|         }
|     }
|
| You can then run this command to create the symlink:
|
|    composer foundation
|
| Before running this command, do the following:
|
|    1. Clone the foundation repo to your development environment.
|    2. Add the path to the foundation repo to e.g. your `~/.bashrc`:
|
|       AIC_PATH_TO_FOUNDATION='/home/vagrant/www/path/to/foundation'
|
| Add the following to your `.gitignore`:
|
|    composer-dev.json
|    composer-dev.lock
|
| See API-118 and "Working with the Foundation" on Confluence.
|
*/

$foundationPath = getenv('AIC_PATH_TO_FOUNDATION') ?: '../../foundation';

$composerJson = json_decode(file_get_contents('composer.json'));

$composerJson->repositories = array_filter($composerJson->repositories, function ($repository) {
    return $repository->url !== 'https://github.com/art-institute-of-chicago/data-hub-foundation.git';
});

array_push($composerJson->repositories, (object) [
    'type' => 'path',
    'url' => $foundationPath,
    'options' => [
        'symlink' => true,
    ],
]);

$composerJson = json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;

file_put_contents('composer-dev.json', $composerJson);

copy('composer.lock', 'composer-dev.lock');
