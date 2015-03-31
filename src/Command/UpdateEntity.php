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
 * Updates an entity
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class UpdateEntity
{
    /**
     * Entity object
     *
     * @var object
     */
    protected $entity;

    /**
     * @var array
     */
    protected $data;

    /**
     * @param object  $entity
     * @param integer $array
     */
    public function __construct($entity, $data)
    {
        $this->entity = $entity;
        $this->data = $data;
    }

    /**
     * Returns the entity
     *
     * @return object
     */
    public function getEntity()
    {
        return $this->entity;
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
