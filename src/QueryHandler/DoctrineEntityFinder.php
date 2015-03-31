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

use Proton\Crud\Query\FindEntity;

/**
 * Handles entity searching
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class DoctrineEntityFinder extends EntityManagerAware
{
    /**
     * Returns an entity
     *
     * @param FindEntity $query
     *
     * @return object|null
     */
    public function handle(FindEntity $query)
    {
        return $this->em->getRepository($query->getEntityClass())->find($query->getId());
    }
}
