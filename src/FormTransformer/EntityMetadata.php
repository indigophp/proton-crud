<?php

/*
 * This file is part of the Proton CRUD package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Proton\Crud\FormTransformer;

use Doctrine\ORM\EntityManagerInterface;
use Proton\Crud\FormTransformer;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class EntityMetadata implements FormTransformer
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @param EntityManagerInterface $em
     * @param string                 $entityClass
     */
    public function __construct(EntityManagerInterface $em, $entityClass)
    {
        $this->em = $em;
        $this->entityClass = $entityClass;
    }

    /**
     * {@inheritdoc}
     */
    public function transformToInternal(array $entity)
    {
        foreach ($entity as $name => &$value) {
            $metadata = $this->getMetadata($name);

            switch ($metadata['type']) {
                case 'datetime':
                    $value instanceof \DateTime or $value = new \DateTime($value);
                    break;
                default:
                    break;
            }
        }

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function transformToDisplay(array $entity)
    {
        foreach ($entity as $name => &$value) {
            $metadata = $this->getMetadata($name);

            switch ($metadata['type']) {
                case 'datetime':
                    $format = 'Y-m-d';

                    if (isset($metadata['options']['form']['format'])) {
                        $format = $metadata['options']['form']['format'];
                    }

                    $value = $value->format($format);
                    break;
                default:
                    break;
            }
        }

        return $entity;
    }

    /**
     * Returns the metadata for a field
     *
     * @param string $fieldName
     *
     * @return array
     */
    protected function getMetadata($fieldName)
    {
        $metadata = $this->em->getClassMetadata($this->entityClass);
        $fields = $metadata->fieldMappings;

        if (isset($fields[$fieldName])) {
            return $fields[$fieldName];
        }

        return ['type' => null];
    }
}
