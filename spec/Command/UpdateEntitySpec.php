<?php

namespace spec\Proton\Crud\Command;

use Proton\Crud\Configuration;
use spec\Proton\Crud\CommandBehavior;

class UpdateEntitySpec extends CommandBehavior
{
    function let(Configuration $config)
    {
        $this->beConstructedWith($config, new \stdClass, []);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Proton\Crud\Command\UpdateEntity');
    }

    function it_has_an_entity()
    {
        $this->getEntity()->shouldHaveType('stdClass');
    }

    function it_has_data()
    {
        $this->setData(['data']);
        $this->getData()->shouldReturn(['data']);
    }
}