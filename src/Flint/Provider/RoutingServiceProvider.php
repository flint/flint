<?php

namespace Flint\Provider;

use Silex\Application;
use Symfony\Component\Routing\Loader\XmlFileLoader;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\DelegatingLoader;

/**
 * @package Flint
 */
class RoutingServiceProvider implements \Silex\ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function register(Application $app)
    {
        $app['routing.loader.xml'] = $app->share(function (Application $app) {
            return new XmlFileLoader($app['config.locator']);
        });

        $app['routing.loader.php'] = $app->share(function (Application $app) {
            return new PhpFileLoader($app['config.locator']);
        });

        $app['routing.loader.yml'] = $app->share(function (Application $app) {
            return new YamlFileLoader($app['config.locator']);
        });

        $app['routing.loader.resolver'] = $app->share(function (Application $app) {
            $loaders = array(
                $app['routing.loader.xml'],
                $app['routing.loader.php'],
            );

            if (class_exists('Symfony\Component\Yaml\Yaml')) {
                $loaders[] = $app['routing.loader.yml'];
            }

            return new LoaderResolver($loaders);
        });

        $app['routing.loader'] = $app->share(function (Application $app) {
            return new DelegatingLoader($app['routing.loader.resolver']);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function boot(Application $app)
    {
    }
}
