Error pages
===========

Overriding error pages
----------------------

By default Silex does not allow you to override error pages. This is possible in Flint when
run in non-debug mode (when ``debug`` is ``false``).

At `the Symfony Cookbook it is described <http://symfony.com/doc/current/cookbook/controller/error_pages.html>`_ how
to customize error pages. Flint uses the same logic. So drop a new template in ``$app['twig.path']/Exception/`` and 
it will be used instead of the default that comes with Flint.

The default that comes with Flint are taken from ``TwigBundle``.
