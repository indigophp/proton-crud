<?php

/*
 * This file is part of the Proton CRUD package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Proton\Crud\Hydrator;

use GeneratedHydrator\Configuration;
use Proton\Crud\Hydrator;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class GeneratedHydrator implements Hydrator
{
    /**
     * {@inheritdoc}
     */
    public function hydrate($object, array $data)
    {
        $hydrator = $this->getHydratorFor($object);

        $hydrator->hydrate($data, $object);
    }

    /**
     * {@inheritdoc}
     */
    public function extract($object)
    {
        $hydrator = $this->getHydratorFor($object);

        return $hydrator->extract($object);
    }

    /**
     * Returns a GeneratedHydrator for the object
     *
     * @param object $object
     *
     * @return GeneratedHydrator
     */
    protected function getHydratorFor($object)
    {
        $class         = get_class($object);
        $config        = new Configuration($class);
        $hydratorClass = $config->createFactory()->getHydratorClass();

        return new $hydratorClass;
    }
}
