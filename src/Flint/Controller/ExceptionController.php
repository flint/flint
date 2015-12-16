<?php

namespace Flint\Controller;

use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @package Flint
 */
class ExceptionController extends Controller
{
    /**
     * @param Request          $request
     * @param FlattenException $exception
     * @param string           $format
     */
    public function showAction(Request $request, FlattenException $exception, $format)
    {
        $handler = new ExceptionHandler($this->pimple['debug']);

        if ($this->pimple['debug']) {
            return $this->createResponse($handler, $exception);
        }

        $code = $exception->getStatusCode();
        $template = $this->resolve($request, $code, $format);

        if ($template) {
            $contents =  $this->pimple['twig']->render($template, array(
                'status_code'    => $code,
                'status_text'    => isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '',
                'exception'      => $exception,
            ));

            return new Response($contents, $code);
        }

        return $this->createResponse($handler, $exception);
    }

    /**
     * @param  Request     $request
     * @param  integer     $code
     * @param  string      $format
     * @return string|null
     */
    protected function resolve(Request $request, $code, $format)
    {
        $loader = $this->pimple['twig.loader'];

        $templates = array(
            'Exception/error' . $code . '.' . $format . '.twig',
            'Exception/error.' . $format . '.twig',
            'Exception/error.html.twig',
            '@Flint/Exception/error.' . $format . '.twig',
            '@Flint/Exception/error.html.twig',
        );

        foreach ($templates as $template) {
            if (false == $loader->exists($template)) {
                continue;
            }

            if (strpos($template, '.html.twig')) {
                $request->setRequestFormat('html');
            }

            return $template;
        }
    }

    private function createResponse(ExceptionHandler $handler, FlattenException $exception)
    {
        if (method_exists('getHtml', $handler)) {
            return Response::create($handler->getHtml($exception), $exception->getStatusCode(), $exception->getHeaders());

        }

        return $handler->createResponse($exception);
    }
}
