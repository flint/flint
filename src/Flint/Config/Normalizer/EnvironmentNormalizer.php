<?php

namespace Flint\Config\Normalizer;

/**
 * @package Flint
 */
class EnvironmentNormalizer implements NormalizerInterface
{
    const PLACEHOLDER = '/#([A-Za-z0-9_.]+)#/';

    /**
     * @param string $contents
     * @return string
     */
    public function normalize($contents)
    {
        return preg_replace_callback(static::PLACEHOLDER, array($this, 'callback'), $contents);
    }

    /**
     * @param array $matches
     * @return mixed
     */
    protected function callback($matches)
    {
        return getenv($matches[1]);
    }
}
