<?php

namespace spec\Flint;

use PHPSpec2\ObjectBehavior;

class Application extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(sys_get_temp_dir());
    }

    function it_extends_silex()
    {
        $this->shouldBeAnInstanceOf('Silex\Application');
    }

    function it_defaults_to_false_for_debug()
    {
        $this->raw('debug')->shouldReturn(false);
        $this->raw('debug');
    }

    function it_saves_the_root_dir_and_debug_on_container()
    {
        $this->beConstructedWith('/My/Custom/Dir', true);

        $this->raw('root_dir')->shouldReturn('/My/Custom/Dir');
        $this->raw('debug')->shouldReturn(true);

        $this->raw('root_dir');
        $this->raw('debug');
    }
}
