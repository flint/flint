Routing
=======

Using a configuration file
--------------------------

Sometimes it is better to keep routing in a config file instead of inside the ``Application`` it self to have better
management of them, or to use the import feature.

This is possible by default with Flint but with full backwards compatibility. By setting the parameter
``routing.resource`` to a resource like ``config/routing.{xml,yml,php}`` it will be loaded.

Caching generated routes
------------------------

This also triggers a replacement of the default ``UrlMatcher`` into a real ``RouterInterface`` implementation.
This have the benefit of having access to a ``UrlGenerator`` and caching.

To enable caching and having a resource loaded.

.. code-block:: php

    <?php

    $app = new Flint\Application(__DIR__, true);
    $app->inject(array(
        'routing.resource' => $app['root_dir'] . '/config/routing.xml',
        'routing.options' => array(
            'cache_dir' => $app['root_dir'] . '/cache/routing',
        ),
    );

    $app->run();

Generate urls for named routes
------------------------------

Because Flint replaces the Routing system with a ``RouterInterface`` implementation there is no
need to enable `UrlGeneratorServiceProvider` because ``RouterInterface`` creates one on demand.

.. note::

    This is only useable for routes that have been bound by using ``->bind`` or with routes that have
    name loaded from a configuration file.

The router is avaiable in Twig templates and on the application as a service.

.. code-block:: jinja

    <a href="{{ app.router.generate('homepage') }}">Frontpage</a>

    {# if Twig bridge is available #}

    <a href="{{ path('homepage') }}">Frontpage</a>

    <a href="{{ url('homepage') }}">Absolute url to Frontpage</a>

.. code-block:: php

    <?php

    namespace Skeleton\Controller;

    class DefaultController
    {
        public function indexAction()
        {
            return $this->redirect($this->generateUrl('homepage'));
        }
    }
