<?php

use Rector\Config\RectorConfig;
use Rector\Php84\Rector\Param\ExplicitNullableParamTypeRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPaths([__DIR__])
    ->withSkip(["vendor/"])
    ->withPhpSets(); // use the one defined in composer.json
