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

/**
 * Finds all entities
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class FindAllEntities
{
    /**
     * Entity class
     *
     * @var string
     */
    protected $entityClass;

    /**
     * @param string $entityClass
     */
    public function __construct($entityClass)
    {
        $this->entityClass = $entityClass;
    }

    /**
     * Returns the entity class name
     *
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }
}
