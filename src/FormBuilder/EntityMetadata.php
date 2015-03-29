<?php

/*
 * This file is part of the Proton CRUD package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Proton\Crud\FormBuilder;

use Doctrine\ORM\EntityManagerInterface;
use Fuel\Fieldset\Builder\Basic;
use Fuel\Fieldset\Form;
use Proton\Crud\FormBuilder;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class EntityMetadata implements FormBuilder
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
    public function buildForm(Form $form, array $options = [])
    {
        if (!isset($options['entityClass'])) {
            throw new \InvalidArgumentException('This type expects an entityClass option passed');
        }

        $builder = new Basic;

        $metadata = $this->em->getClassMetadata($options['entityClass']);
        $fields = $metadata->fieldMappings;

        foreach ($fields as $name => $mappings) {
            if (isset($mappings['options']['form'])) {
                $data = array_merge([
                    'name'  => $name,
                    'label' => isset($mappings['options']['label']) ? $mappings['options']['label'] : null,
                ], $mappings['options']['form']);

                $form[$name] = $builder->generate([$data])[0];
            }
        }
    }
}
