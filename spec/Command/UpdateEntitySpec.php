<?php

namespace spec\Proton\Crud\Command;

use Proton\Crud\Configuration;
use Proton\Crud\Stub\Entity;
use spec\Proton\Crud\CommandBehavior;

class UpdateEntitySpec extends CommandBehavior
{
    function let(Configuration $config, Entity $entity)
    {
        $this->beConstructedWith($config, $entity, []);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Proton\Crud\Command\UpdateEntity');
    }

    function it_has_an_entity(Entity $entity)
    {
        $this->getEntity()->shouldReturn($entity);
    }

    function it_has_data()
    {
        $this->setData(['data']);
        $this->getData()->shouldReturn(['data']);
    }
}
