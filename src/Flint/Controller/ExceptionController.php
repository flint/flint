<?php

namespace Flint\Controller;

use Symfony\Component\HttpFoundation\Request;

class ExceptionController extends Controller
{
    public function showAction(Request $request)
    {
        return 'I am an error';
    }
}
