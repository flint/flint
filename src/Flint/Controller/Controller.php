<?php

namespace Flint\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @package Flint
 */
abstract class Controller extends \Flint\ApplicationAware
{
    /**
     * Creates a normal response with the given text and statusCode
     *
     * @param string $text
     * @param integer $statusCode
     * @param array $headers
     * @return Response
     */
    protected function text($text, $statusCode = 200, array $headers = array())
    {
        return new Response($text, $statusCode, $headers);
    }

    /**
     * @see Symfony\Component\Routing\RouterInterface::generate()
     */
    public function generateUrl($name, array $parameters = array(), $reference = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->app['router']->generate($name, $parameters, $reference);
    }

    /**
     * @see Twig_Environment::render();
     */
    protected function render($name, array $context = array())
    {
        return $this->app['twig']->render($name, $context);
    }

    /**
     * @see Silex\Application::redirect()
     */
    protected function redirect($url, $statusCode = 302)
    {
        return $this->app->redirect($url, $statusCode);
    }

    /**
     * @see Silex\Application::abort()
     */
    protected function abort($statusCode, $message = '', array $headers = array())
    {
        return $this->app->abort($statusCode, $message, $headers);
    }

    /**
     * @param string $id
     * @return boolean
     */
    protected function has($id)
    {
        return isset($this->app[$id]);
    }

    /**
     * @param string $id
     * @return mixed
     */
    protected function get($id)
    {
        return $this->app[$id];
    }
}
