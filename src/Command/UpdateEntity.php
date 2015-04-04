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
use Proton\Crud\ConfigurationAwareCommand;

/**
 * Updates an entity
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class UpdateEntity implements NamedCommand
{
    use ConfigurationAwareCommand;

    /**
     * Entity object
     *
     * @var object
     */
    protected $entity;

    /**
     * @var array
     */
    protected $data;

    /**
     * @param Configuration $config
     * @param object        $entity
     * @param array         $data
     */
    public function __construct(Configuration $config, $entity, array $data)
    {
        $this->config = $config;
        $this->entity = $entity;
        $this->data = $data;
    }

    /**
     * Returns the entity
     *
     * @return object
     */
    public function getEntity()
    {
        return $this->entity;
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
