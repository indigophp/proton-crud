<?php

namespace spec\Proton\Crud\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use Indigo\Hydra\Hydrator;
use Proton\Crud\Command\UpdateEntity;
use PhpSpec\ObjectBehavior;

class DoctrineEntityUpdaterSpec extends ObjectBehavior
{
    function let(EntityManagerInterface $em, Hydrator $hydra)
    {
        $this->beConstructedWith($em, $hydra);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Proton\Crud\CommandHandler\DoctrineEntityUpdater');
    }

    function it_handles_an_update_command(UpdateEntity $command, EntityManagerInterface $em, Hydrator $hydra)
    {
        $entity = new \stdClass;
        $data = ['data' => 'atad'];

        $command->getEntity()->willReturn($entity);
        $command->getData()->willReturn($data);

        $hydra->hydrate($entity, $data)->shouldBeCalled();
        $hydra->extract($entity)->willReturn([]);
        $em->flush()->shouldBeCalled();

        $this->handle($command);
    }
}
