<?php

namespace spec\Proton\Crud\CommandHandler;

use Doctrine\Instantiator\InstantiatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Indigo\Hydra\Hydrator;
use Proton\Crud\Command\CreateEntity;
use Proton\Crud\Stub\Entity;
use PhpSpec\ObjectBehavior;

class DoctrineEntityCreatorSpec extends ObjectBehavior
{
    function let(InstantiatorInterface $instantiator, Hydrator $hydra, EntityManagerInterface $em)
    {
        $this->beConstructedWith($instantiator, $hydra, $em);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Proton\Crud\CommandHandler\DoctrineEntityCreator');
    }

    function it_handles_a_create_command(Entity $entity, CreateEntity $command, InstantiatorInterface $instantiator, Hydrator $hydra, EntityManagerInterface $em)
    {
        $entityClass = 'Proton\Crud\Stub\Entity';
        $data = [
            'estimatedEnd' => 'now',
        ];

        $command->getEntityClass()->willReturn($entityClass);
        $command->getData()->willReturn($data);

        $instantiator->instantiate($entityClass)->willReturn($entity);

        $hydra->extract($entity)->willReturn([]);
        $hydra->hydrate($entity, $data)->shouldBeCalled();

        $em->persist($entity)->shouldBeCalled();
        $em->flush()->shouldBeCalled();

        $this->handle($command);
    }
}
