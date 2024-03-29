<?php

/**
 * Dependency analyzer configuration
 * @link https://github.com/shipmonk-rnd/composer-dependency-analyser
 */

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

$config = new Configuration();

return $config
    // ignore errors on specific packages and paths
    ->ignoreErrorsOnPackageAndPath('ddeboer/vatin', __DIR__ . '/php-src/Rules/External/IsEuVat.php', [ErrorType::DEV_DEPENDENCY_IN_PROD])
    ->ignoreErrorsOnPackageAndPath('giggsey/libphonenumber-for-php', __DIR__ . '/php-src/Rules/External/IsTelephone.php', [ErrorType::DEV_DEPENDENCY_IN_PROD])
;