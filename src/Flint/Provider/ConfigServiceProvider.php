<?php

namespace Flint\Provider;

use Silex\Application;
use Symfony\Component\Config\FileLocator;

/**
 * @package Flint
 */
class ConfigServiceProvider implements \Silex\ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function register(Application $app)
    {
        $app['config.paths'] = function (Application $app) {
            $rootDir = $app['root_dir'];

            return array(
                $rootDir . '/config',
                $rootDir,
            );
        };

        $app['config.locator'] = $app->share(function (Application $app) {
            return new FileLocator($app['config.paths']);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function boot(Application $app)
    {
    }
}
