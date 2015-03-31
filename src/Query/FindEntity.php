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
 * Finds an entity
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class FindEntity
{
    /**
     * Entity class
     *
     * @var string
     */
    protected $entityClass;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @param string  $entityClass
     * @param integer $id
     */
    public function __construct($entityClass, $id)
    {
        $this->entityClass = $entityClass;
        $this->id = $id;
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
