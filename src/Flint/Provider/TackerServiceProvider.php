<?php

namespace Flint\Provider;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Tacker\Configurator;
use Tacker\ResourceCollection;
use Tacker\Loader\IniFileLoader;
use Tacker\Loader\JsonFileLoader;
use Tacker\Loader\YamlFileLoader;
use Tacker\Normalizer\ChainNormalizer;
use Tacker\Normalizer\EnvironmentNormalizer;
use Tacker\Normalizer\PimpleNormalizer;
use Pimple;

/**
 * ServiceProvider for Tacker
 *
 * @package Flint
 */
class TackerServiceProvider implements \Silex\Api\ServiceProviderInterface
{
    public function register(Pimple $app)
    {
        // This should proberly have a different name.
        $app['locator'] = function ($app) {
            return new FileLocator($app['paths']);
        };

        $app['tacker'] = function ($app) {
            return new Configurator($app['tacker.loader'], $app['tacker.normalizer'], $app['tacker.resource_collection']);
        };

        $app['tacker.resource_collection'] = function ($app) {
            return new ResourceCollection;
        };

        $app['tacker.normalizer'] = function ($app) {
            return new ChainNormalizer(array(
                new EnvironmentNormalizer,
                new PimpleNormalizer($app),
            ));
        };

        $app['tacker.loader_resolver'] = function ($app) {
            $resolver = new LoaderResolver(array(
                new JsonFileLoader($app['locator'], $app['tacker.resource_collection']),
                new IniFileLoader($app['locator'], $app['tacker.resource_collection']),
            ));

            if (class_exists('Symfony\Component\Yaml\Yaml')) {
                $resolver->addLoader(new YamlFileLoader($app['locator'], $app['tacker.resource_collection']));
            }

            return $resolver;
        };

        $app['tacker.loader'] = function ($app) {
            return new DelegatingLoader($app['tacker.loader_resolver']);
        };
    }
}
