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

/**
 * Holds some basic configuration
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Configuration
{
    /**
     * @var string
     */
    protected $serviceName;

    /**
     * @var string
     */
    protected $controllerClass;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @var string
     */
    protected $route;

    /**
     * @var array
     */
    protected $handlerMap = [];

    /**
     * @var array
     */
    protected $views = [
        'create' => 'create.twig',
        'read'   => 'read.twig',
        'update' => 'update.twig',
        'list'   => 'list.twig',
    ];

    /**
     * @param string $serviceName
     * @param string $controllerClass
     * @param string $entityClass
     * @param string $route
     * @param array  $handlerMap
     * @param array  $views
     */
    public function __construct(
        $serviceName,
        $controllerClass,
        $entityClass,
        $route,
        array $handlerMap = [],
        array $views = []
    ) {
        $this->serviceName = $serviceName;
        $this->controllerClass = $controllerClass;
        $this->entityClass = $entityClass;
        $this->handlerMap = array_merge($this->handlerMap, $handlerMap);
        $this->view = array_merge($this->views, $views);
    }

    /**
     * Returns the service name
     *
     * @return string
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * Returns the controller class
     *
     * @return string
     */
    public function getControllerClass()
    {
        return $this->controllerClass;
    }

    /**
     * Returns the entity class
     *
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * Returns the route
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Returns the handler map
     *
     * @return array
     */
    public function getHandlerMap()
    {
        return $this->handlerMap;
    }

    /**
     * Checks if there is a custom handler registered for a command
     *
     * @param string $command
     *
     * @return boolean
     */
    public function hasHandlerFor($command)
    {
        return isset($this->handlerMap[$command]);
    }

    /**
     * Returns the list of views
     *
     * @return array
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Returns the view for an action
     *
     * @param string $action
     *
     * @return string
     */
    public function getViewFor($action)
    {
        if (!$this->hasViewFor($action)) {
            throw new \InvalidArgumentException('There is no view registered for this action: '.$action);
        }

        return $this->views[$action];
    }

    /**
     * Checks if a view is available for an action
     *
     * @param string $action
     *
     * @return boolean
     */
    public function hasViewFor($action)
    {
        return isset($this->views[$action]);
    }
}
