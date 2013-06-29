<?php

namespace Flint\Config;

use Pimple;
use Symfony\Component\Config\ConfigCache;
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
     * @param string          $cacheDir
     * @param boolean         $debug
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
        $cache = new ConfigCache($this->cacheDir . '/' . crc32($resource) . '.php', $this->debug);

        if (!$cache->isFresh()) {
            $parameters = $this->loader->load($resource);
        }

        if ($this->cacheDir && isset($parameters)) {
            $cache->write('<?php $parameters = ' . var_export($parameters, true) . ';');
        }

        if (!isset($parameters)) {
            require (string) $cache;
        }

        $this->build($pimple, $parameters);
    }

    /**
     * @param Pimple $pimple
     * @param array  $parameters
     */
    protected function build(Pimple $pimple, array $parameters)
    {
        foreach ($parameters as $key => $value) {
            $pimple[$key] = $value;
        }
    }
}
