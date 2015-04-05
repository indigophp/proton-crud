<?php

/*
 * This file is part of the Proton CRUD package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Proton\Crud\Query;

use League\Tactician\Plugins\NamedCommand\NamedCommand;
use Proton\Crud\Configuration;
use Proton\Crud\ConfigurationAware;
use Proton\Crud\CrudCommand;

/**
 * Finds all entities
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class FindAllEntities implements NamedCommand, ConfigurationAware
{
    use CrudCommand;

    /**
     * @param Configuration $config
     */
    public function __construct(Configuration $config)
    {
        $this->config = $config;
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
}
