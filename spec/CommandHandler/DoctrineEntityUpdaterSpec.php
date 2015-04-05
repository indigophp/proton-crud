<?php

namespace spec\Proton\Crud\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use Indigo\Hydra\Hydrator;
use Proton\Crud\Command\UpdateEntity;
use Proton\Crud\Stub\Entity;
use PhpSpec\ObjectBehavior;

class DoctrineEntityUpdaterSpec extends ObjectBehavior
{
    function let(Hydrator $hydra, EntityManagerInterface $em)
    {
        $this->beConstructedWith($hydra, $em);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Proton\Crud\CommandHandler\DoctrineEntityUpdater');
    }

    function it_handles_an_update_command(Entity $entity, UpdateEntity $command, Hydrator $hydra, EntityManagerInterface $em)
    {
        $data = ['data' => 'atad'];

        $command->getEntity()->willReturn($entity);
        $command->getData()->willReturn($data);

        $hydra->hydrate($entity, $data)->shouldBeCalled();
        $hydra->extract($entity)->willReturn([]);

        $em->flush()->shouldBeCalled();

        $this->handle($command);
    }
}
