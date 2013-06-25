<?php

namespace Flint\Config\Normalizer;

use Pimple;

/**
 * @package Flint
 */
class PimpleAwareNormalizer extends \Flint\PimpleAware implements NormalizerInterface
{
    const PLACEHOLDER = '/%([A-Za-z0-9_.]+)%/';

    /**
     * @param Pimple $pimple
     */
    public function __construct(Pimple $pimple = null)
    {
        $this->setPimple($pimple);
    }

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
        $value = $this->pimple[$matches[1]];

        return is_bool($value) ? ($value ? 'true' : 'false') : $value;
    }
}
