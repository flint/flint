<?php

namespace Flint\Tests;

use Flint\Application;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function testInjectSetParameters()
    {
        $app = new Application(__DIR__, true);
        $app->inject(array(
            'key.param' => 'key.value',
        ));

        $this->assertEquals($app['key.param'], 'key.value');
    }

    public function testRootDirAndDebugIsSet()
    {
        $app = new Application('/my/root_dir', true);

        $this->assertTrue($app['debug']);
        $this->assertEquals('/my/root_dir', $app['root_dir']);
    }
}
