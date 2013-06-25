<?php

namespace Flint\Config\Loader;

use Flint\Config\Normalizer\NormalizerInterface;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Exception\FileLoaderLoadException;

/**
 * @package Flint
 */
class JsonFileLoader extends \Symfony\Component\Config\Loader\FileLoader
{
    protected $normalizer;

    /**
     * @param NormalizerInterface $normalizer
     * @param FileLocatorInterface $locator
     */
    public function __construct(NormalizerInterface $normalizer, FileLocatorInterface $locator)
    {
        parent::__construct($locator);

        $this->normalizer = $normalizer;
    }

    /**
     * {@inheritDoc}
     */
    public function load($resource, $type = null)
    {
        if (!$this->supports($resource)) {
            throw new FileLoaderLoadException($resource);
        } 

        $contents = file_get_contents($this->locator->locate($resource));

        return json_decode($this->normalizer->normalize($contents), true);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'json' === pathinfo($resource, PATHINFO_EXTENSION);
    }
}
