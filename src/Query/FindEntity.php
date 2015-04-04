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

use Tactician\CommandBus\Plugins\NamedCommand\NamedCommand;
use Proton\Crud\Configuration;
use Proton\Crud\ConfigurationAwareCommand;

/**
 * Finds an entity
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class FindEntity implements NamedCommand
{
    use ConfigurationAwareCommand;

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
     * Returns the ID
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
