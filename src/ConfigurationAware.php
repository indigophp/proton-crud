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
interface ConfigurationAware
{
    /**
     * Returns the configuration
     *
     * @return Configuration
     */
    public function getConfig();
}
