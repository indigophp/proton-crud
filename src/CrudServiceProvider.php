<?php

/*
 * This file is part of the Proton CRUD package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Proton\Crud;

use League\Container\ServiceProvider;
use League\Tactician\CommandBus;

/**
 * Provides CRUD services
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class CrudServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        'crud.command_locator',
        'crud.command_bus',
    ];

    /**
     * Provides the default handler map
     *
     * @var array
     */
    protected $defaultHandlerMap = [
        'crud.createEntity'    => 'Proton\Crud\CommandHandler\DoctrineEntityCreator',
        'crud.updateEntity'    => 'Proton\Crud\CommandHandler\DoctrineEntityUpdater',
        'crud.deleteEntity'    => 'Proton\Crud\CommandHandler\DoctrineEntityRemover',
        'crud.findEntity'      => 'Proton\Crud\QueryHandler\DoctrineEntityFinder',
        'crud.findAllEntities' => 'Proton\Crud\QueryHandler\DoctrineAllEntityFinder',
        'crud.loadEntity'      => 'Proton\Crud\QueryHandler\DoctrineEntityLoader',
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->getContainer()->add('crud.command_locator', 'League\Tactician\Container\ContainerLocator')
            ->withArgument('League\Container\Container')
            ->withArgument($this->defaultHandlerMap);

        $this->getContainer()->add('crud.command_inflector', 'League\Tactician\Handler\MethodNameInflector\HandleInflector');
        $this->getContainer()->add('crud.command_middleware', 'League\Tactician\Handler\CommandHandlerMiddleware')
            ->withArgument('crud.command_locator')
            ->withArgument('crud.command_inflector');

        $this->getContainer()->add('crud.command_bus', function() {
            return new CommandBus(func_get_args());
        })
        ->withArgument('crud.command_middleware');
    }
}
