<?php

namespace Flint\Config\Loader;

use Symfony\Component\Yaml\Parser;

/**
 * @package Flint
 */
class YamlFileLoader extends AbstractLoader
{
    protected function read($resource)
    {
        $parser = new Parser();
        return $parser->parse(file_get_contents($resource));
    }

    /**
     * {@inheritDoc}
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo($resource, PATHINFO_EXTENSION);
    }
}
