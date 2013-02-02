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
            return array(
                $app['root_dir'],
                __DIR__ . '/../Resources',
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
