<?php

namespace spec\Proton\Crud;

use Proton\Crud\Configuration;
use PhpSpec\ObjectBehavior;

abstract class CommandBehavior extends ObjectBehavior
{
    function it_is_a_command()
    {
        $this->shouldImplement('Proton\Crud\ConfigurationAware');
        $this->shouldImplement('League\Tactician\Plugins\NamedCommand\NamedCommand');
    }

    function it_has_a_configuration(Configuration $config)
    {
        $this->getConfig()->shouldReturn($config);
    }

    function it_has_a_command_name(Configuration $config)
    {
        $commandName = $this->guessCommandName();

        $config->getName()->willReturn('name');
        $config->hasHandlerFor($commandName)->willReturn(true);

        $this->getCommandName()->shouldReturn('name.'.$commandName);
    }

    function it_falls_back_to_the_defult_name_when_no_handler(Configuration $config)
    {
        $commandName = $this->guessCommandName();

        $config->getName()->shouldNotBeCalled();
        $config->hasHandlerFor($commandName)->willReturn(false);

        $this->getCommandName()->shouldReturn('crud.'.$commandName);
    }

    function it_has_an_original_name()
    {
        $this->getOriginalCommandName()->shouldReturn($this->guessCommandName());
    }

    protected function guessCommandName()
    {
        return lcfirst(substr(strrchr(get_class($this->getWrappedObject()), '\\'), 1));
    }
}
