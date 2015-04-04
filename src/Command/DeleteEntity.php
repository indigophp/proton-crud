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

use Tactician\CommandBus\Plugins\NamedCommand\NamedCommand;
use Proton\Crud\Configuration;
use Proton\Crud\ConfigurationAwareCommand;

/**
 * Deletes an entity
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class DeleteEntity implements NamedCommand
{
    use ConfigurationAwareCommand;

    /**
     * Entity object
     *
     * @var object
     */
    protected $entity;

    /**
     * @param Configuration $config
     * @param object        $entity
     */
    public function __construct(Configuration $config, $entity)
    {
        $this->config = $config;
        $this->entity = $entity;
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
}