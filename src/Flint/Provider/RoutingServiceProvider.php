<?php

namespace Flint\Provider;

use Flint\Controller\ControllerResolver;
use Flint\Routing\Loader\NullLoader;
use Flint\Routing\Matcher\ChainUrlMatcher;
use Pimple;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\Loader\XmlFileLoader;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Router;

/**
 * @package Flint
 */
class RoutingServiceProvider implements \Silex\Api\ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function register(Pimple $app)
    {
        $app['routing.resource'] = null;
        $app['routing.options'] = array();

        $app->extend('resolver', function ($resolver, $app) {
            return new ControllerResolver($resolver, $app);
        });

        $app['routing.loader.xml'] = function ($app) {
            return new XmlFileLoader($app['config.locator']);
        };

        $app['routing.loader.php'] = function ($app) {
            return new PhpFileLoader($app['config.locator']);
        };

        $app['routing.loader.yml'] = function ($app) {
            return new YamlFileLoader($app['config.locator']);
        };

        $app['routing.loader.null'] = function ($app) {
            return new NullLoader;
        };

        $app['routing.loader.resolver'] = function ($app) {
            $loaders = array(
                $app['routing.loader.xml'],
                $app['routing.loader.php'],
                $app['routing.loader.null'],
            );

            if (class_exists('Symfony\\Component\\Yaml\\Yaml')) {
                $loaders[] = $app['routing.loader.yml'];
            }

            return new LoaderResolver($loaders);
        };

        $app['routing.loader'] = function ($app) {
            return new DelegatingLoader($app['routing.loader.resolver']);
        };

        $app['router'] = function ($app) {
            $defaults = array(
                'debug'              => $app['debug'],
                'matcher_base_class' => 'Silex\\RedirectableUrlMatcher',
                'matcher_class'      => 'Silex\\RedirectableUrlMatcher',
            );

            $options = array_replace($defaults, $app['routing.options']);

            return new Router($app['routing.loader'], $app['routing.resource'], $options, $app['request_context'], $app['logger']);
        };

        $app->extend('url_matcher', function ($matcher, $app) {
            return new ChainUrlMatcher(array($app['router'], $matcher));
        });

        $app['url_generator'] = $app->factory(function ($app) {
            return $app['router'];
        });
    }
}
