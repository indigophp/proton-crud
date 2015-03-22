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
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface Hydrator
{
    /**
     * Hydrate data
     *
     * @param object $object
     * @param array  $data
     */
    public function hydrate($object, array $data);

    /**
     * Extract data from an object
     *
     * @param object $object
     *
     * @return array
     */
    public function extract($object);
}
