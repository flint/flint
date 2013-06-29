<?php

namespace Flint\Config\Loader;

use Flint\Config\Normalizer\NormalizerInterface;
use Symfony\Component\Config\FileLocatorInterface;

/**
 * @package Flint
 */
class JsonFileLoader extends \Symfony\Component\Config\Loader\FileLoader
{
    protected $normalizer;

    /**
     * @param NormalizerInterface  $normalizer
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
        $file = $this->locator->locate($resource);

        return $this->parse($this->read($file), $file);
    }

    /**
     * @param  array  $parameters
     * @param  string $file
     * @return array
     */
    protected function parse(array $parameters, $file)
    {
        if (!isset($parameters['@import'])) {
            return $parameters;
        }

        $import = $parameters['@import'];

        unset($parameters['@import']);

        $this->setCurrentDir(dirname($import));

        return array_replace($this->import($import, null, false, $file), $parameters);
    }

    protected function read($resource)
    {
        return json_decode($this->normalizer->normalize(file_get_contents($resource)), true);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'json' === pathinfo($resource, PATHINFO_EXTENSION);
    }
}
