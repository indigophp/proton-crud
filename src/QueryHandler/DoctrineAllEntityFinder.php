<?php

/*
 * This file is part of the Proton CRUD package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Proton\Crud\QueryHandler;

use Proton\Crud\Query\FindAllEntities;

/**
 * Handles all entities fetching
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class DoctrineAllEntityFinder extends EntityManagerAware
{
    /**
     * Returns an entity
     *
     * @param FindAllEntities $query
     *
     * @return object|null
     */
    public function handle(FindAllEntities $query)
    {
        return $this->em->getRepository($query->getConfig()->getEntityClass())->findAll();
    }
}
