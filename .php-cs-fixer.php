<?php

declare(strict_types=1);

use Baijunyao\PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests'
    ]);

return (new Config())->setFinder($finder);
