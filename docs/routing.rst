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


