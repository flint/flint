.. Flint documentation master file, created by
   sphinx-quickstart on Wed Jan 23 17:41:55 2013.
   You can adapt this file completely to your liking, but it should at least
   contain the root `toctree` directive.

Welcome to Flint's documentation!
=================================

Flint is a enhancement build ontop of Silex. It adds some additional features
that brings to closer to Symfony than Silex is per default. This make the process
of moving a small app into a full blown Symfony application easier later down a
development cycle.

Basic usage
-----------

Flint uses a ``root_dir`` and a ``debug`` parameter as one of the essential things.
In various providers you create this can be very useful, especially the ``root_dir`` for
configuration for Twig and so on. This is also why both are required constructor parameters.

.. code-block:: php

    <?php

    $app = new Flint\Application($rootDir = __DIR__, $debug = true);
    $app->run();

Both of theese are available as ``$app['root_dir']`` and ``$app['debug']``.

Injecting parameters
--------------------

To make injecting parameters easier Flint implements a ``inject`` method that takes an array of
key, values and sets them on the application.

.. code-block:: php

    <?php

    // .. create $app
    $app->inject(array(
        'twig.path' => $app['root_dir'] . '/views',
    ));

More advanced stuff
-------------------

.. toctree::

    controllers
    routing
    error_pages
