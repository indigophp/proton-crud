<?php

/*
 * This file is part of the Proton CRUD package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Proton\Crud\CommandHandler;

use Proton\Crud\Command\CreateEntity;

/**
 * Handles entity creation
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class DoctrineEntityCreator extends EntityModifier
{
    /**
     * Creates a new entity
     *
     * @param CreateEntity $command
     */
    public function handle(CreateEntity $command)
    {
        $entityClass = $command->getConfig()->getEntityClass();
        $entity = new $entityClass;
        $data = $command->getData();

        // UGLY WORKAROUND BEGINS
        $data = array_merge($this->hydrator->extract($entity), $data);
        // UGLY WORKAROUND ENDS

        $this->hydrator->hydrate($entity, $data);

        $this->em->persist($entity);
        $this->em->flush();
    }
}
