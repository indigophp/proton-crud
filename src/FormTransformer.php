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
interface FormTransformer
{
    /**
     * Transforms a data structure to its internal representation
     *
     * @param array $entity
     *
     * @return array
     */
    public function transformToInternal(array $entity);

    /**
     * Transforms a data structure to its display representation
     *
     * @param array $entity
     *
     * @return array
     */
    public function transformToDisplay(array $entity);
}
