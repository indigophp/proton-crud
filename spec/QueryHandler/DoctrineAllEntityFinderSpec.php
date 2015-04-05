<?php

namespace spec\Proton\Crud\QueryHandler;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Proton\Crud\Query\FindAllEntities;
use PhpSpec\ObjectBehavior;

class DoctrineAllEntityFinderSpec extends ObjectBehavior
{
    function let(EntityManagerInterface $em)
    {
        $this->beConstructedWith($em);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Proton\Crud\QueryHandler\DoctrineAllEntityFinder');
    }

    function it_handles_a_find_all_query(FindAllEntities $query, EntityRepository $repository, EntityManagerInterface $em)
    {
        $query->getEntityClass()->willReturn('Proton\Crud\Stub\Entity');

        $repository->findAll()->shouldBeCalled();

        $em->getRepository('Proton\Crud\Stub\Entity')->willReturn($repository);

        $this->handle($query);
    }
}
