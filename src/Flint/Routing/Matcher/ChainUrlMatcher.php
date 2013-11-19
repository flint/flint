<?php

namespace Flint\Routing\Matcher;

use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

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
    public function match($pathinfo)
    {
        try {
            foreach ($this->matchers as $matcher) {
                return $matcher->match($pathinfo);
            }
        } catch (MethodNotAllowedException $e) {
            $exception = $e;
        } catch (ResourceNotFoundException $e) {
            if (!isset($exception)) {
                $exception = $e;
            }
        }

        throw $exception;
    }

    /**
     * {@inheritDoc}
     */
    public function setContext(RequestContext $context)
    {
        $this->context = $context;

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
