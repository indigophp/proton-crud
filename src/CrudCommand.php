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
 * Commands and Queries accept a configuration object
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
trait CrudCommand
{
    /**
     * @var Configuration
     */
    protected $config;

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * {@inheritdoc}
     */
    public function getCommandName()
    {
        $service = 'crud';
        $name = $this->getOriginalCommandName();

        if ($this->config->hasHandlerFor($name)) {
            $service = $this->config->getName();
        }

        return $service.'.'.$name;
    }

    /**
     * Returns the original name of the command
     *
     * @return string
     */
    public function getOriginalCommandName()
    {
        return lcfirst(substr(strrchr(get_class($this), '\\'), 1));
    }
}
