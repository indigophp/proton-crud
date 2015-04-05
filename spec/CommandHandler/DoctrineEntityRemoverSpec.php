<?php

namespace spec\Proton\Crud\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use Proton\Crud\Command\DeleteEntity;
use Proton\Crud\Stub\Entity;
use PhpSpec\ObjectBehavior;

class DoctrineEntityRemoverSpec extends ObjectBehavior
{
    function let(EntityManagerInterface $em)
    {
        $this->beConstructedWith($em);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Proton\Crud\CommandHandler\DoctrineEntityRemover');
    }

    function it_handles_a_delete_command(Entity $entity, DeleteEntity $command, EntityManagerInterface $em)
    {
        $command->getEntity()->willReturn($entity);

        $em->remove($entity)->shouldBeCalled();
        $em->flush()->shouldBeCalled();

        $this->handle($command);
    }
}
