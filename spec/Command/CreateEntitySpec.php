<?php

namespace spec\Proton\Crud\Command;

use Proton\Crud\Configuration;
use spec\Proton\Crud\CommandBehavior;

class CreateEntitySpec extends CommandBehavior
{
    function let(Configuration $config)
    {
        $this->beConstructedWith($config, []);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Proton\Crud\Command\CreateEntity');
    }

    function it_has_an_entity_class(Configuration $config)
    {
        $config->getEntityClass()->willReturn('stdClass');

        $this->getEntityClass()->shouldReturn('stdClass');
    }

    function it_has_data()
    {
        $this->setData(['data']);
        $this->getData()->shouldReturn(['data']);
    }
}
