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
use Indigo\Hydra\Hydrator;

/**
 * Accepts an EntityManagerInterface and a Hydrator
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
abstract class EntityModifier
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

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
}
