<?php

namespace Flint\Controller;

use Symfony\Component\HttpKernel\Debug\ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;

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

        return $handler->createResponse($exception);
    }
}
