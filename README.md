Flint
=====

![Flint](http://upload.wikimedia.org/wikipedia/commons/thumb/e/ed/Miorcani_flint.jpg/220px-Miorcani_flint.jpg)

Flint is an enhanced version of Silex. It adds a more Symfony'ish way of using Controllers aswell as Application injection
into Controllers implementing the `ApplicationAwareInterface`.

Testing
-------

Flint is tested with PHPSpec2 and Travis-CI.

[![Build Status](https://travis-ci.org/henrikbjorn/flint.png?branch=master)](https://travis-ci.org/henrikbjorn/flint)

Usage
-----

The usage difference are subtle and Flint should not be noticed.

``` php
<?php

use Flint\Application;

$app = new Application($rootDir, $debug = false);
$app->get('/my/route/{param}', 'My\\Custom\\Controller::indexAction');

$app->run();
```

### Controllers like a Boss

Flint contains a base controller like the one found in FrameworkBundle, just not with all the helper
methods (yet).

All controllers extending the base controller or implements `ApplicationAwareInterface` will have the
application injected by the controller resolver;


``` php
<?php

namespace My\Awesome\Controller;

class DefaultController extends \Flint\Controller\Controller
{
    public function indexAction()
    {
        return $this->render('template.html.twig');
    }
}
```

### Pretty error pages

Symfony have a nice feature where it can render a nice error page with twig. This is not in Silex per default.
Flint uses the default `ExceptionListener` and implements an `ExceptionController` which looks for error templates
in `views/Exception`. The logic for when to get what template is the same as specified in the symfony cookbook.

### Configurations from json file(s)

Remains to be done
