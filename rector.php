<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
//        __DIR__ . '/config',
        __DIR__ . '/src',
//        __DIR__ . '/tests',
    ]);

    // register a single rule
    $rectorConfig->rule(\Rector\PSR4\Composer\PSR4NamespaceMatcher::class);

    // define sets of rules
//        $rectorConfig->sets([
//            LevelSetList::UP_TO_PHP_81

//        ]);
};
