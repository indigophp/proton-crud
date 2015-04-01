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
abstract class CrudServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $serviceName;

    /**
     * @var string
     */
    protected $controller;

    /**
     * Provides handler map
     *
     * @var array
     */
    protected $handlerMap = [
        'Proton\Crud\Command\CreateEntity'  => 'Proton\Crud\CommandHandler\DoctrineEntityCreator',
        'Proton\Crud\Command\UpdateEntity'  => 'Proton\Crud\CommandHandler\DoctrineEntityUpdater',
        'Proton\Crud\Command\DeleteEntity'  => 'Proton\Crud\CommandHandler\DoctrineEntityRemover',
        'Proton\Crud\Query\FindEntity'      => 'Proton\Crud\QueryHandler\DoctrineEntityFinder',
        'Proton\Crud\Query\FindAllEntities' => 'Proton\Crud\QueryHandler\DoctrineAllEntityFinder',
        'Proton\Crud\Query\LoadEntity'      => 'Proton\Crud\QueryHandler\DoctrineEntityLoader',
    ];

    public function __construct()
    {
        if (!isset($this->serviceName)) {
            throw new \LogicException('Service name must be set');
        }

        if (!isset($this->controller)) {
            throw new \LogicException('Controller must be set');
        }

        $this->provides = [
            $this->serviceName.'.command_bus',
            $this->serviceName.'.controller',
        ];
    }

    public function register()
    {
        $this->getContainer()->add($this->serviceName.'.command_locator', 'League\Tactician\Container\ContainerLocator')
            ->withArgument('League\Container\Container')
            ->withArgument($this->handlerMap);

        $this->getContainer()->add($this->serviceName.'.command_inflector', 'League\Tactician\Handler\MethodNameInflector\HandleInflector');
        $this->getContainer()->add($this->serviceName.'.command_middleware', 'League\Tactician\Handler\CommandHandlerMiddleware')
            ->withArgument($this->serviceName.'.command_locator')
            ->withArgument($this->serviceName.'.command_inflector');

        $this->getContainer()->add($this->serviceName.'.command_bus', function() {
            return new CommandBus(func_get_args());
        })
        ->withArgument($this->serviceName.'.command_middleware');

        $this->getContainer()->add($this->serviceName.'.controller', $this->controller)
            ->withArgument('Twig_Environment')
            ->withArgument($this->serviceName.'.command_bus');
    }
}
