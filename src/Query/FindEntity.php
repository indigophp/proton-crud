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
 * Finds an entity
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class FindEntity implements NamedCommand, ConfigurationAware
{
    use CrudCommand;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @param Configuration $config
     * @param integer       $id
     */
    public function __construct(Configuration $config, $id)
    {
        $this->config = $config;
        $this->id = $id;
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
     * Returns the ID
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
