<?php

namespace spec\Proton\Crud\QueryHandler;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Proton\Crud\Query\FindEntity;
use PhpSpec\ObjectBehavior;

class DoctrineEntityFinderSpec extends ObjectBehavior
{
    function let(EntityManagerInterface $em)
    {
        $this->beConstructedWith($em);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Proton\Crud\QueryHandler\DoctrineEntityFinder');
    }

    function it_handles_a_find_query(FindEntity $query, EntityRepository $repository, EntityManagerInterface $em)
    {
        $query->getEntityClass()->willReturn('stdClass');
        $query->getId()->willReturn(1);

        $repository->find(1)->shouldBeCalled();

        $em->getRepository('stdClass')->willReturn($repository);

        $this->handle($query);
    }
}
