<?php

namespace spec\Proton\Crud\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use Indigo\Hydra\Hydrator;
use Proton\Crud\Command\CreateEntity;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DoctrineEntityCreatorSpec extends ObjectBehavior
{
    function let(EntityManagerInterface $em, Hydrator $hydra)
    {
        $this->beConstructedWith($em, $hydra);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Proton\Crud\CommandHandler\DoctrineEntityCreator');
    }

    function it_handles_a_create_command(CreateEntity $command, EntityManagerInterface $em, Hydrator $hydra)
    {
        $entityClass = 'stdClass';

        $command->getData()->willReturn([
            'estimatedEnd' => 'now',
        ]);
        $command->getEntityClass()->willReturn($entityClass);

        $hydra->hydrate(Argument::type($entityClass), Argument::type('array'))->shouldBeCalled();
        $hydra->extract(Argument::type($entityClass))->willReturn([]);
        $em->persist(Argument::type($entityClass))->shouldBeCalled();
        $em->flush()->shouldBeCalled();

        $this->handle($command);
    }
}
