<?php

namespace spec\Proton\Crud\Query;

use Proton\Crud\Configuration;
use spec\Proton\Crud\CommandBehavior;

class FindAllEntitiesSpec extends CommandBehavior
{
    function let(Configuration $config)
    {
        $this->beConstructedWith($config);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Proton\Crud\Query\FindAllEntities');
    }

    function it_has_an_entity_class(Configuration $config)
    {
        $config->getEntityClass()->willReturn('Proton\Crud\Stub\Entity');

        $this->getEntityClass()->shouldReturn('Proton\Crud\Stub\Entity');
    }
}
