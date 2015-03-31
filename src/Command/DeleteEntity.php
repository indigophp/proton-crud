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
 * Deletes an entity
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class DeleteEntity
{
    /**
     * Entity object
     *
     * @var object
     */
    protected $entity;

    /**
     * @param object  $entity
     */
    public function __construct($entity)
    {
        $this->entity = $entity;
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
}
