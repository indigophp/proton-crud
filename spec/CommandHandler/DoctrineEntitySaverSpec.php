<?php

namespace spec\Proton\Crud\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use Proton\Crud\Command\SaveEntity;
use Proton\Crud\Stub\Entity;
use PhpSpec\ObjectBehavior;

class DoctrineEntitySaverSpec extends ObjectBehavior
{
    function let(EntityManagerInterface $em)
    {
        $this->beConstructedWith($em);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Proton\Crud\CommandHandler\DoctrineEntitySaver');
    }

    function it_handles_an_update_command(Entity $entity, SaveEntity $command, EntityManagerInterface $em)
    {
        $command->getEntity()->willReturn($entity);

        $em->flush()->shouldBeCalled();

        $this->handle($command);
    }
}
