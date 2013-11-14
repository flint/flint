<?php

namespace Flint\Routing\Matcher;

use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;

/**
 * Contains multiple matchers and circumvent a problem with 
 * `routes` service trigger muliple routing loads. Also it will
 * remove the `serializing` from the Router if Silex specific callback
 * routes are used.
 *
 * @package Flint
 */
class ChainUrlMatcher implements UrlMatcherInterface
{
    protected $matchers;
    protected $context;

    /**
     * @param array $matchers
     */
    public function __construct(array $matchers)
    {
        foreach ($matchers as $matcher) {
            $this->add($matcher);
        }
    }

    /**
     * @param UrlMatcherInterface $matcher
     */
    public function add(UrlMatcherInterface $matcher)
    {
        $this->matchers[] = $matcher;
    }

    /**
     * {@inheritDoc}
     */
    public function match($patchinfo)
    {
        foreach ($matchers as $matcher) {
            try {
                return $matcher->match($pathinfo);
            } catch (\Exception $e) {
                $exception = $e;
            }
        }

        return $exception;
    }

    /**
     * {@inheritDoc}
     */
    public function setContext(RequestContext $context)
    {
        $this->context = $contect;

        foreach ($this->matchers as $matcher) {
            $matcher->setContext($context);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getContext()
    {
        return $this->context;
    }
}
