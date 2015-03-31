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

use Proton\Crud\Command\UpdateEntity;

/**
 * Handles entity update
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class DoctrineEntityUpdater extends EntityModifier
{
    /**
     * Updates an entity
     *
     * @param UpdateEntity $command
     */
    public function handle(UpdateEntity $command)
    {
        $entity = $command->getEntity();
        $data = $command->getData();

        // UGLY WORKAROUND BEGINS
        $data = array_merge($this->hydrator->extract($entity), $data);
        // UGLY WORKAROUND ENDS

        $this->hydrator->hydrate($entity, $data);

        $this->em->flush();
    }
}
