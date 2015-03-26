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

use Fuel\Fieldset\Form;
use Proton\Crud\FormBuilder;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
abstract class Decorator implements FormBuilder
{
    /**
     * @var FormBuilder
     */
    protected $formBuilder;

    /**
     * @param FormBuilder $formBuilder
     */
    public function __construct(FormBuilder $formBuilder)
    {
        $this->formBuilder = $formBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function create(Form $form)
    {
        $this->formBuilder->create($form);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Form $form)
    {
        $this->formBuilder->update($form);
    }
}
