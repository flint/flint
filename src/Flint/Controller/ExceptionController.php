<?php

namespace Flint\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * @package Flint
 */
class ExceptionController extends Controller
{
    /**
     * @param Request $request
     * @param string $format
     */
    public function showAction(Request $request, $format)
    {
        return $format;
    }
}
