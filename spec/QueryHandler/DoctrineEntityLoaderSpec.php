<?php

namespace spec\Proton\Crud\QueryHandler;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Indigo\Hydra\Hydrator;
use Proton\Crud\Query\LoadEntity;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DoctrineEntityLoaderSpec extends ObjectBehavior
{
    function let(EntityManagerInterface $em, Hydrator $hydra)
    {
        $this->beConstructedWith($em, $hydra);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Proton\Crud\QueryHandler\DoctrineEntityLoader');
    }

    function it_handles_a_load_query(LoadEntity $query, EntityRepository $repository, EntityManagerInterface $em, Hydrator $hydra)
    {
        $entity = new \stdClass;
        $query->getEntityClass()->willReturn('stdClass');
        $query->getId()->willReturn(1);

        $repository->find(1)->willReturn($entity);

        $em->getRepository('stdClass')->willReturn($repository);

        $hydra->extract($entity)->shouldBeCalled();

        $this->handle($query);
    }

    function it_returns_an_empty_array_when_no_entity_found(LoadEntity $query, EntityRepository $repository, EntityManagerInterface $em)
    {
        $query->getEntityClass()->willReturn('stdClass');
        $query->getId()->willReturn(1);

        $repository->find(1)->willReturn(null);

        $em->getRepository('stdClass')->willReturn($repository);

        $this->handle($query)->shouldReturn([]);
    }
}
