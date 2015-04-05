<?php

namespace spec\Proton\Crud\Query;

use Proton\Crud\Configuration;
use spec\Proton\Crud\CommandBehavior;

class LoadEntitySpec extends CommandBehavior
{
    function let(Configuration $config)
    {
        $this->beConstructedWith($config, 1);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Proton\Crud\Query\LoadEntity');
    }

    function it_has_an_entity_class(Configuration $config)
    {
        $config->getEntityClass()->willReturn('stdClass');

        $this->getEntityClass()->shouldReturn('stdClass');
    }

    function it_has_an_id()
    {
        $this->getId()->shouldReturn(1);
    }
}
