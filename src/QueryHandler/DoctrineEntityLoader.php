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

use Doctrine\ORM\EntityManagerInterface;
use Indigo\Hydra\Hydrator;
use Proton\Crud\Query\LoadEntity;

/**
 * Handles entity loading
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class DoctrineEntityLoader extends EntityManagerAware
{
    /**
     * @var Hydrator
     */
    protected $hydrator;

    /**
     * @param EntityManagerInterface $em
     * @param Hydrator               $hydrator
     */
    public function __construct(EntityManagerInterface $em, Hydrator $hydrator)
    {
        $this->em = $em;
        $this->hydrator = $hydrator;
    }

    /**
     * Returns an entity's data
     *
     * @param LoadEntity $query
     *
     * @return object|null
     */
    public function handle(LoadEntity $query)
    {
        $entity = $this->em->getRepository($query->getConfig()->getEntityClass())->find($query->getId());

        if ($entity) {
            return $this->hydrator->extract($entity);
        }

        return [];
    }
}
