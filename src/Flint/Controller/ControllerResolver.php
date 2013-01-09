<?php

namespace Flint\Controller;

use Flint\Application;
use Flint\ApplicationAwareInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

/**
 * Injects the Application into the Controller if it implements the right interface
 * otherwise it delegates to the composed resolver.
 *
 * @package Flint
 */
class ControllerResolver implements ControllerResolverInterface
{
    protected $app;
    protected $resolver;

    /**
     * @param ControllerResolverInterface $resolver
     * @param Application $app
     */
    public function __construct(ControllerResolverInterface $resolver, Application $app)
    {
        $this->resolver = $resolver;
        $this->app = $app;
    }

    /**
     * {@inheritDoc}
     */
    public function getController(Request $request)
    {
        $controller = $this->resolver->getController($request);

        if (false == is_array($controller)) {
            return $controller;
        }

        if ($controller[0] instanceof ApplicationAwareInterface) {
            $controller[0]->setApplication($this->app);
        }

        return $controller;
    }

    /**
     * {@inheritDoc}
     */
    public function getArguments(Request $request, $controller)
    {
        return $this->resolver->getArguments($request, $controller);
    }
}
