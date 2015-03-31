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
use League\Tactician\Container\ContainerLocator;

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
        'crud.command_inflector',
    ];

    /**
     * Provides handler map
     *
     * @var array
     */
    protected $handlerMap = [
        'Proton\Crud\Command\CreateEntity' => 'Proton\Crud\CommandHandler\DoctrineEntityCreator',
        'Proton\Crud\Command\UpdateEntity' => 'Proton\Crud\CommandHandler\DoctrineEntityUpdater',
        'Proton\Crud\Command\DeleteEntity' => 'Proton\Crud\CommandHandler\DoctrineEntityRemover',
        'Proton\Crud\Query\FindEntity'     => 'Proton\Crud\QueryHandler\DoctrineEntityFinder',
        'Proton\Crud\Query\LoadEntity'     => 'Proton\Crud\QueryHandler\DoctrineEntityLoader',
    ];

    public function register()
    {
        $this->getContainer()->add('crud.command_locator', function() {
            return new ContainerLocator(
                $this->getContainer(),
                $this->handlerMap
            );
        });

        $this->getContainer()->add('crud.command_inflector', 'League\Tactician\Handler\MethodNameInflector\HandleInflector');
        $this->getContainer()->add('crud.command_middleware', 'League\Tactician\Handler\CommandHandlerMiddleware', [
            'crud.command_locator',
            'crud.command_inflector',
        ]);
    }
}
