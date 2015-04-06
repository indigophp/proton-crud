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

use Doctrine\ORM\EntityManagerInterface;
use Proton\Crud\Command\SaveEntity;

/**
 * Handles entity save
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class DoctrineEntitySaver
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Saves an entity
     *
     * @param UpdateEntity $command
     */
    public function handle(SaveEntity $command)
    {
        $this->em->flush();
    }
}
