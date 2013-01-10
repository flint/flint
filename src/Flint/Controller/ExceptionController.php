<?php

namespace Flint\Controller;

use Symfony\Component\HttpKernel\Debug\ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @package Flint
 */
class ExceptionController extends Controller
{
    /**
     * @param Request $request
     * @param FlattenException $exception
     * @param string $format
     */
    public function showAction(Request $request, FlattenException $exception, $format)
    {
        $handler = new ExceptionHandler($this->app['debug']);

        if ($this->app['debug']) {
            return $handler->createResponse($exception);
        }

        $this->app['twig.loader.filesystem']->addPath(__DIR__ . '/../Resources/views');

        $code = $exception->getStatusCode();
        $template = $this->resolve($request, $code, $format);

        if ($template) {
            return $this->app['twig']->render($template, array(
                'status_code'    => $code,
                'status_text'    => isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '',
                'exception'      => $exception,
            ));
        }

        return $handler->createResponse($exception);
    }

    /**
     * @param Request $request
     * @param integer $code
     * @param string $format
     * @return string|null
     */
    protected function resolve(Request $request, $code, $format)
    {
        $loader = $this->app['twig.loader'];

        $templates = array(
            'Exception/error' . $code . '.' . $format . '.twig',
            'Exception/error.' . $format . '.twig',
            'Exception/error.twig',
        );

        foreach ($templates as $template) {
            if (false == $loader->exists($template)) {
                continue;
            }

            return $template;
        }
    }
}
