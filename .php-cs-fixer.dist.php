<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude([
        '.git',
        'bootstrap/cache',
        'node_modules',
        'public',
        'resources/lang',
        'storage',
        'vendor',
    ])
    ->in(getcwd());

$config = new PhpCsFixer\Config();
$config->setFinder($finder);
$config->setRules(['@PSR12' => true]);

return $config;
