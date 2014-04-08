<?php

namespace Flint\Routing;

use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;

/**
 * Uses multiple generators to try and generate an url. This is needed
 * if you use Silex closure routes with the router. The performance
 * impact should be minimal.
 *
 * @package Brick
 */
class ChainUrlGenerator implements UrlGeneratorInterface
{
    private $context;
    private $generators = array();

    public function __construct($generators = array())
    {
        array_walk($generators, array($this, 'add'));
    }

    public function add(UrlGeneratorInterface $generator)
    {
        $this->generators[] = $generator;
    }

    public function setContext(RequestContext $context)
    {
        $this->context = $context;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function generate($name, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        $notFound = null;
        $missingMandatory = null;
        $invalidParameters = null;

        foreach ($this->generators as $generator) {
            if ($this->context) {
                $generator->setContext($this->context);
            }

            try {
                return $generator->generate($name, $parameters, $referenceType);
            } catch (RouteNotFoundException $e) {
                $notFound = $e;
            } catch (MissingMandatoryParametersException $e) {
                $missingMandatory = $e;
            } catch (InvalidParameterException $e) {
                $invalidParameters = $e;
            }
        }

        if ($invalidParameters) {
            throw $invalidParameters;
        }

        throw $missingMandatory ?: $notFound;
    }
}
