Flint
=====

[![Build Status](https://travis-ci.org/henrikbjorn/Flint.png?branch=master)](https://travis-ci.org/henrikbjorn/Flint)

Flint is build on top of [Silex](http://silex.sensionlabs.org) and tries to bring structure, conventions and a couple of [Symfony](http://symfony.com) features.

What is Different from Silex
----------------------------

Nothing and everything. Everything Silex does, Flint does aswell. This means that it is fully backwards compatible and that closures can still be used.

* [Twig](http://twig.sensiolabs.org) is enabled by default.
* Uses the full router instead of the url matcher for more power and flexibility.
* Supports `xml|yml|php` for router configuration.
* Custom controller resolver that knows how to inject your application.
* A base controller with convenient helper methods.
* Custom error pages by using the default exception handler from Symfony.

Documentation
-------------

* [Getting started](#getting-started)
* [Controllers](#controllers)
* [Routing](#routing)
* [Custom Error Pages](#custom-error-pages)
* [Default Parameters](#default-parameters)
* [Injecting Configuration Parameters](#injecting-configuration-parameters)

### Getting started

To start a new project with Flint the easiest way is to use [Composer](http://getcomposer.org) and [Flint-Skeleton](http://github.com/henrikbjorn/flint-skeleton).

``` bash
$ php composer.phar create-project -s dev henrikbjorn/flint-skeleton my-flint-application
```

Or if you are migrating from a Silex project you can change your `composer.json` file to require Flint and change the Application class that is used.

``` bash
$ php composer.phar require henrikbjorn/flint:~1.0
```

``` php
<?php

use Flint\Application;

$application = new Application($rootDir, $debug);
```

It is recommended to subclass `Flint\Application` instead of using the application class directly.

### Controllers

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

Another way is to use a base controller which have convenience methods for the most frequently used services. Theese methods can be seen in the source code
if you look at the implementation for `Flint\Controller\Controller`.

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

### Routing

Because Flint replaces the url matcher used in Symfony with the full router implementation a lot of new things is possible.

Caching is one of thoose things. It makes your application faster as it does not need to register routes on every request.
Together with loading your routes from a configuration file like Symfony it will also bring more structure to your application.

To enable caching you just need to point the router to the directory you want to use and if it should cache or not. By default the
`debug` parameter will be used as to determaine if cache should be used or not.

``` php
<?php

// .. create a $app before this line
$app->inject(array(
    'routing.options' => array(
        'cache_dir' => '/my/cache/directory/routing',
    ),
));
```

Before it is possible to use the full power of caching it is needed to use configuration files because Silex will always call
add routes via its convenience methods `get|post|delete|put`. Furtunately this is baked right in.

``` php
<?php

// .. create $app
$app->inject(array(
    'routing.resource' => 'config/routing.xml',
));
```

``` xml
<!-- config/routing.xml -->
<?xml version="1.0" encoding="UTF-8" ?>
<routes xmlns="http://symfony.com/schema/routing"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/routing http://symfony.com/schema/routing/routing-1.0.xsd">

    <route id="homepage" pattern="/">
        <default key="_controller">Acme\\Controller\\DefaultController::indexAction</default>
    </route>
</routes>
```

This will make the router load that resource by default. Here xml is used as an example but `php` is also supported together with
`yml` if `Symfony\Component\Yaml\Yaml` is autoloadable.

The benefit from doing it this way is of course they can be cached but also it allows you to import routing files that are included
in libraries and even other Symfony bundles such as the [WebProfilerBundle](https://github.com/symfony/webprofilerbundle). Also it will make it easier to generate routes from
inside your views.

``` jinja
<a href="{{ app.router.generate('homepage') }}">Homepage</a>
```

This is also possible with Silex but with a more verbose syntax.

### Default Parameters

The two contructor arguments `$rootDir` and `$debug` are also registered on the application as parameters. This makes it easier 
for services to add paths for caching, logs or other directories.

``` php
<?php

// .. create $app
$app->inject(array(
    'twig.path' => $app['root_dir'] . '/views',
));
```

### Custom Error Pages

When finished a project or application it is the small things that matter the most. Such as having a custom error page instead of the one
Silex provides by default. Also it can help a lost user navigate back. Flint makes this possible by using the exception handler from Symfony 
and a dedicated controller. Both the views and the controller can be overrriden.

This will only work when debug is turned off.

To override the error pages the same logic is used as inside Symfony.
The logic is very well described [in their documentation](http://symfony.com/doc/master/cookbook/controller/error_pages.html).

Only difference from Symfony is the templates must be created inside `views/Excetion/` directory. Inside the templates there is
access to `app` which in turns gives you access to all of the services defined. 

To override the controller used by the exception handler change the `exception_controller` parameter. This parameter will by default
be set to `Flint\\Controller\\ExceptionController::showAction`.

``` php
<?php

// .. create $app
$app->inject(array(
    'exception_controller' => 'Acme\\Controller\\ExceptionController::showAction',
));
```

To see what parameter the controller action takes look at the one provided by default. Normally it should not be overwritten as it already
gives a lot of flexibilty with the template lookup.

### Injecting Configuration Parameters

Some times it is more useful to inject an array of parameters instead of setting them on the application one-by-one. Flint have a method that 
does this. It does the same thing as the second parameter of Silex `register` method.

``` php
<?php

// .. $app
$app->inject(array(
    'twig.paths' => '/my/path/to/views',
));
```

Feedback
--------

Please provide feedback on everything (code, structure, idea etc) over twitter or email.

Who
---

Build by [@henrikbjorn](http://twitter.com/henrikbjorn) at [Peytz & Co](http://peytz.dk). With the help of [other contributors](https://github.com/henrikbjorn/flint/graphs/contributors).
