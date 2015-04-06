<?php

namespace spec\Proton\Crud\Command;

use Proton\Crud\Configuration;
use Proton\Crud\Stub\Entity;
use spec\Proton\Crud\CommandBehavior;

class DeleteEntitySpec extends CommandBehavior
{
    function let(Configuration $config, Entity $entity)
    {
        $this->beConstructedWith($config, $entity);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Proton\Crud\Command\DeleteEntity');
    }

    function it_has_an_entity(Entity $entity)
    {
        $this->getEntity()->shouldReturn($entity);
    }
}
