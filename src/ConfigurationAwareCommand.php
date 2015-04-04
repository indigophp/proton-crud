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
 * Exposes a configuration
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
trait ConfigurationAwareCommand
{
    /**
     * @var Configuration
     */
    protected $config;

    /**
     * Returns the configuration
     *
     * @return Configuration
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
            $service = $this->config->getServiceName();
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
        $class = explode('\\', __CLASS__);

        return lcfirst(array_pop($class));
    }
}
