<?php

/*
 * This file is part of the Proton CRUD package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Proton\Crud\Validation;

use Doctrine\ORM\EntityManagerInterface;
use Fuel\Validation\RuleProvider\FromArray;
use Fuel\Validation\Validator;
use Proton\Crud\Validation;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class EntityMetadata implements Validation
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
    public function create(Validator $validator)
    {
        $this->build($validator);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Validator $validator)
    {
        $this->build($validator);
    }

    /**
     * Common validator building process (adding common elements, etc)
     *
     * @param Validator $validator
     */
    protected function build(Validator $validator)
    {
        $ruleProvider = new FromArray(true);

        $metadata = $this->em->getClassMetadata($this->entityClass);
        $fields = $metadata->fieldMappings;
        $data = [];

        foreach ($fields as $name => $mappings) {
            if (isset($mappings['options']['validation'])) {
                $data[$name] = [
                    'label' => isset($mappings['options']['label']) ? $mappings['options']['label'] : null,
                    'rules' => $mappings['options']['validation'],
                ];
            }
        }

        $ruleProvider->setData($data);
        $ruleProvider->populateValidator($validator);
    }
}
