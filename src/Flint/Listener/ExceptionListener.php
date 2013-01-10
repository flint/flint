<?php

namespace Flint\Listener;

use Silex\Application;
use Silex\ExceptionHandler;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @package Flint
 */
class ExceptionListener implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    protected $controller;
    protected $handler;

    /**
     * @param string|array $controller
     * @param ExceptionHandler $handler
     */
    public function __construct($controller, ExceptionHandler $handler)
    {
        $this->controller = $controller;
        $this->handler = $handler;
    }

    /**
     * Sends the Exception to a controller
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function onSilexError(GetResponseForExceptionEvent $event)
    {
        $attributes = array('_controller' => $this->controller);

        $request = $event->getRequest()->duplicate(null, null, $attributes);
        $request->setMethod('GET');

        try {
            $response = $event->getKernel()->handle($request, HttpKernelInterface::SUB_REQUEST, false);

            $event->setResponse($response);
        } catch (\Exception $e) {
            $this->handler->onSilexError($event);
        }
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => array('onSilexError', -255)
        );
    }
}
