<?php

namespace spec\Proton\Crud;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigurationSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(
            'name',
            'controllerClass',
            'Proton\Crud\Stub\Entity',
            '/route',
            ['command' => 'handler']
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Proton\Crud\Configuration');
    }

    function it_has_a_name()
    {
        $this->getName()->shouldReturn('name');
    }

    function it_has_a_controller_class()
    {
        $this->getControllerClass()->shouldReturn('controllerClass');
    }

    function it_has_an_entity_class()
    {
        $this->getEntityClass()->shouldReturn('Proton\Crud\Stub\Entity');
    }

    function it_has_a_route()
    {
        $this->getRoute()->shouldReturn('/route');
    }

    function it_has_a_handler_map()
    {
        $this->getHandlerMap()->shouldReturn(['command' => 'handler']);
    }

    function it_checks_if_there_is_a_handler_override()
    {
        $this->hasHandlerFor('command')->shouldReturn(true);
        $this->hasHandlerFor('non_command')->shouldReturn(false);
    }

    function it_has_a_list_of_views()
    {
        $this->getViews()->shouldReturn([
            'create' => 'create.twig',
            'read'   => 'read.twig',
            'update' => 'update.twig',
            'list'   => 'list.twig',
        ]);
    }

    function it_returns_a_view_for_a_given_action()
    {
        $this->hasViewFor('create')->shouldReturn(true);
        $this->getViewFor('create')->shouldReturn('create.twig');
    }

    function it_throws_an_exception_if_there_is_no_view_for_an_action()
    {
        $this->hasViewFor('invalid')->shouldReturn(false);
        $this->shouldThrow('InvalidArgumentException')->duringGetViewFor('invalid');
    }
}
