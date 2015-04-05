<?php

/*
 * This file is part of the Proton CRUD package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Proton\Crud\Command;

use League\Tactician\Plugins\NamedCommand\NamedCommand;
use Proton\Crud\Configuration;
use Proton\Crud\ConfigurationAware;
use Proton\Crud\CrudCommand;

/**
 * Creates a new entity
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class CreateEntity implements NamedCommand, ConfigurationAware
{
    use CrudCommand;

    /**
     * @var array
     */
    protected $data;

    /**
     * @param Configuration $config
     * @param array         $data
     */
    public function __construct(Configuration $config, array $data)
    {
        $this->config = $config;
        $this->data = $data;
    }

    /**
     * Returns the entity class
     *
     * @return string
     */
    public function getEntityClass()
    {
        return $this->config->getEntityClass();
    }

    /**
     * Returns the data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets the data
     *
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }
}
