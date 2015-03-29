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
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildValidation(Validator $validator, array $options = [])
    {
        if (!isset($options['entityClass'])) {
            throw new \InvalidArgumentException('This type expects an entityClass option passed');
        }

        $ruleProvider = new FromArray(true);

        $metadata = $this->em->getClassMetadata($options['entityClass']);
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
