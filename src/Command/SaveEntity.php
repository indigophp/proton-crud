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
 * Saves an entity
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class SaveEntity implements NamedCommand, ConfigurationAware
{
    use CrudCommand;

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
