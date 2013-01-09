<?php

namespace Flint;

/**
 * @package Flint
 */
interface ApplicationAwareInterface
{
    /**
     * A Silex version of ContainerAwareInterface that is found in Symfony
     *
     * @param Application|null $flint
     */
    public function setApplication(Application $app = null);
}
