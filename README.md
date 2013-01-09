Flint
=====

Flint is an enhanced version of Silex. It adds a more Symfony'ish way of using Controllers aswell as Application injection
into Controllers implementing the `ApplicationAwareInterface`.

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

### Configurations from json file(s)

Remains to be done
