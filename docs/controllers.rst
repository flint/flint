Controllers
===========

Using a base controller
-----------------------

Flint makes it easy to have controllers behave like in Symfony. This is done by having a 
base controller ``Flint\Controller\Controller`` which implements most of the methods
found in the base controller from Symfony

.. code-block:: php

    <?php

    namespace Skeleton\Controller;

    use Flint\Controller\Controller;

    class DefaultController extends Controller
    {
        public function indexAction()
        {
            return $this->render('index.html.twig');
        }

        public function showAction($id)
        {
            // Theoretic example where the application is used inside an action.
            $model = $this->app['repository']->get($id);

            return $this->render('show.html.twig', compact('model'));
        }
    }

ApplicationAware controllers
----------------------------

The base controller gets the ``Application`` injected into it because it implements a
special interface called ``Flint\ApplicationAwareInterface``. The default behaviour
with injecting the ``Application`` or ``Request`` is kept intact if they are typehinted
in the actions parameters.

.. code-block:: php

    <?php

    namespace Skeleton\Controller;

    use Flint\Application;
    use Flint\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;

    class DefaultController extends Controller
    {
        public function indexAction(Request $request, Application $app)
        {
            // Request is injected when method is called by ControllerResolver
            // Application is also injected.
        }
    }
