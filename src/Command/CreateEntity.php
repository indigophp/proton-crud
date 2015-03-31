<?php

/*
 * This file is part of the Proton CRUD package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Proton\Crud\Command;

/**
 * Creates a new entity
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class CreateEntity
{
    /**
     * Entity class
     *
     * @var string
     */
    protected $entityClass;

    /**
     * @var array
     */
    protected $data;

    /**
     * @param string  $entityClass
     * @param integer $array
     */
    public function __construct($entityClass, $data)
    {
        $this->entityClass = $entityClass;
        $this->data = $data;
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
     * Returns the data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets the data
     *
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }
}
