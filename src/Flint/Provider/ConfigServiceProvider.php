<?php

namespace Flint\Provider;

use Flint\Config\Configurator;
use Flint\Config\Loader\IniFileLoader;
use Flint\Config\Loader\JsonFileLoader;
use Flint\Config\Loader\YamlFileLoader;
use Flint\Config\Normalizer\ChainNormalizer;
use Flint\Config\Normalizer\EnvironmentNormalizer;
use Flint\Config\Normalizer\PimpleAwareNormalizer;
use Flint\Config\ResourceCollection;
use Pimple;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;

/**
 * @package Flint
 */
class ConfigServiceProvider implements \Silex\Api\ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function register(Pimple $app)
    {
        $app['config.paths'] = $app->factory(function ($app) {
            return array($app['root_dir'] . '/config', $app['root_dir']);
        });

        $app['config.locator'] = function ($app) {
            return new FileLocator($app['config.paths']);
        };

        $app['config.resource_collection'] = function ($app) {
            return new ResourceCollection;
        };

        $app['config.normalizer'] = function ($app) {
            $normalizer = new ChainNormalizer;
            $normalizer->add(new PimpleAwareNormalizer($app));
            $normalizer->add(new EnvironmentNormalizer);

            return $normalizer;
        };

        $app['config.loader'] = function ($app) {
            return new DelegatingLoader($app['config.loader_resolver']);
        };

        $app['config.loader_resolver'] = function ($app) {
            $loaders = array(
                new JsonFileLoader($app['config.normalizer'], $app['config.locator'], $app['config.resource_collection']),
                new IniFileLoader($app['config.normalizer'], $app['config.locator'], $app['config.resource_collection']),
                new YamlFileLoader($app['config.normalizer'], $app['config.locator'], $app['config.resource_collection']),
            );

            return new LoaderResolver($loaders);
        };

        $app['configurator'] = function ($app) {
            $configurator = new Configurator($app['config.loader'], $app['config.resource_collection']);
            $configurator->setCacheDir($app['config.cache_dir']);
            $configurator->setDebug($app['debug']);

            return $configurator;
        };

        if (!isset($app['config.cache_dir'])) {
            $app['config.cache_dir'] = null;
        }
    }
}
