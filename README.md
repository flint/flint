Flint - The almost microframework
=================================

[![Build Status](https://travis-ci.org/henrikbjorn/Flint.png?branch=master)](https://travis-ci.org/henrikbjorn/Flint)

Flint is build on top of [Silex](http://silex.sensionlabs.org) and tries to bring structure, conventions and a couple of [Symfony](http://symfony.com) features.

What is different (from Silex)
------------------------------

Nothing and everything. Everything Silex does, Flint does aswell. This means that it is fully backwards compatible. So if closures are your thing, then dont stop using does but still get some benefit.

* Uses a real `Router` instead of just the `UrlMatcher`. This gives the possiblity of caching for faster matching and generation.
* Supports using `xml`, `yml` files for router configuration.
* Custom `ControllerResolver` that knows how to inject your `Application` into controllers.
* A base `Controller` with helper methods that matches the one found in [FrameworBundle](http://github.com/symfony/frameworkbundle).
* Custom error pages by using the default `ExceptionHandler`.
* Uses [Twig](http://twig.sensiolabs.org) by default for rendering.

Getting started
---------------

For starting up a new project the easiest way is to use [Composer](http://getcomposer.org) and [Flint-Skeleton](http://github.com/henrikbjorn/flint-skeleton).

``` bash
$ php composer.phar create-project -s dev henrikbjorn/flint-skeleton my-flint-application
```

Or if you are migrating from a Silex project change your `composer.json` file to require Flint and change the Application class that is used.

``` bash
$ php composer.phar require henrikbjorn/flint:~1.0
```

``` php
<?php

use Flint\Application;

$application = new Application($rootDir, $debug);
```

It is recommended to subclass `Flint\Application` instead of using the application class directly.

Controllers
-----------

Flint tries to make Silex more like Symfony. And by using closures it is hard to seperate controllers in a logic way when you have more than a
couple of them. To make it better it is recommended to use classes and methods for controllers. The basics is [explained here](http://silex.sensiolabs.org/doc/usage.html#controllers-in-classes)
but Flint takes it further and allows the application to be injected into a controller.

The first way to accomplish this is by implementing `ApplicationAwareInterface` or extending `ApplicationAware`. This works exactly [as described in Symfony](http://symfony.com/doc/2.0/book/controller.html#the-base-controller-class).
With the only exception that the property is called `$app` instead of `$container`.

``` php
<?php

namespace Acme\Controller;

use Flint\ApplicationAware;

class HelloController extends ApplicationAware
{
    public function indexAction()
    {
        return $this->app['twig']->render('Hello/index.html.twig');
    }
}
```

The other way is to use a base controller. Flint ships with one that mimics most of the one provider with Symfony. To see what methods it implements
go look at the source code for `Flint\Controller\Controller`.

``` php
<?php

namespace Acme\Controller;

use Flint\Controller\Controller;

class HelloController extends Controller
{
    public function indexAction()
    {
        return $this->render('Hello/index.html.twig');
    }
}
```

Documentation
-------------

More documentation [can be found at flint.readthedocs.org](https://flint.readthedocs.org/).

Feedback
--------

Please provide feedback on everything (code, structure, idea etc) over twitter or email.

Who
---

Build by [@henrikbjorn](http://twitter.com/henrikbjorn), [Peytz & Co](http://peytz.dk) and [other contributors](https://github.com/henrikbjorn/flint/graphs/contributors).
