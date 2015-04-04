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

/**
 * Provides CRUD setup
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
abstract class CrudProvider extends ServiceProvider
{
    /**
     * @var Configuration
     */
    protected $config;

    public function __construct()
    {
        $this->config = $this->getConfiguration();

        $this->provides = [
            $this->config->getServiceName().'.controller',
        ];
    }

    public function register()
    {
        $handlers = $this->config->getHandlerMap();

        if (count($handlers) > 0) {
            $handlers = array_flip($handlers);

            foreach ($handlers as $handler => &$commandOrQuery) {
                $commandOrQuery = $this->config->getServiceName().$commandOrQuery;
            }

            $handlers = array_flip($handlers);

            $this->getContainer()->extend('crud.command_locator')
                ->withMethodCall('addHandlers', [$handlers]);
        }

        $this->getContainer()->add($this->config->getServiceName().'.controller', $this->config->getControllerClass())
            ->withArgument('Twig_Environment')
            ->withArgument('crud.command_bus')
            ->withArgument($this->config);
    }

    /**
     * Returns a configuration instance
     *
     * @return Configuration
     */
    abstract protected function getConfiguration();
}
