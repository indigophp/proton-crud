<?php

/*
 * This file is part of the Proton CRUD package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Proton\Crud;

use Fuel\Fieldset\Form;
use Fuel\Validation\Validator;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface FormType
{
    /**
     * Builds a form
     *
     * @param Form $form
     */
    public function buildForm(Form $form);

    /**
     * Builds a validation
     *
     * @param Validator $validator
     */
    public function buildValidation(Validator $validator);
}
