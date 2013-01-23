<?php

namespace Flint\Routing;

use Symfony\Component\Routing\RouteCollection;

/**
 * @package Flint
 */
class NullLoader extends \Symfony\Component\Config\Loader\Loader
{
    /**
     * {@inheritDoc}
     */
    public function load($resource, $type = null)
    {
        return new RouteCollection;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($resource, $type = null)
    {
        return null === $resource;
    }
}
