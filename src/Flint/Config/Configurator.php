<?php

namespace Flint\Config;

use Pimple;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * @package Flint
 */
class Configurator
{
    protected $loader;
    protected $cacheDir;
    protected $debug;

    /**
     * @param LoaderInterface $resolver
     * @param string $cacheDir
     * @param boolean $debug
     */
    public function __construct(LoaderInterface $resolver, $cacheDir, $debug = false)
    {
        $this->loader = $resolver;
        $this->cacheDir = $cacheDir;
        $this->debug = $debug;
    }

    /**
     * @param Pimple $pimple
     * @param string $resource
     */
    public function configure(Pimple $pimple, $resource)
    {
        $metadata = new \ArrayObject;
        $cache = new ConfigCache($this->cacheDir . '/' . crc32($resource) . '.php', $this->debug);

        if (!$cache->isFresh()) {
            $parameters = $this->load($resource, $metadata);
        }

        if ($this->cacheDir && isset($parameters)) {
            $cache->write('<?php $parameters = ' . var_export($parameters, true) . ';', iterator_to_array($metadata));
        }

        if (!isset($parameters)) {
            require (string) $cache;
        }

        $this->build($pimple, $parameters);
    }

    /**
     * @param string $resource
     * @param ArrayObject $metadata
     * @return array
     */
    protected function load($resource, \ArrayObject $metadata)
    {
        $parameters = $this->loader->load($resource);

        $metadata->append(new FileResource($resource));

        if (isset($parameters['@import'])) {
            $parameters = array_replace($this->load($parameters['@import'], $metadata), $parameters);
        }

        return $parameters;
    }

    /**
     * @param Pimple $pimple
     * @param array $parameters
     */
    protected function build(Pimple $pimple, array $parameters)
    {
        foreach ($parameters as $key => $value) {
            $pimple[$key] = $value;
        }
    }
}
