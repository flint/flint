<?php

namespace Flint;

/**
 * @package Flint
 */
abstract class ApplicationAware implements ApplicationAwareInterface
{
    protected $app;

    /**
     * {@inheritDoc}
     */
    public function setApplication(Application $app = null)
    {
        $this->app = $app;
    }
}
