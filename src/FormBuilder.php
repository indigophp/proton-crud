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

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface FormBuilder
{
    /**
     * Builds form
     *
     * @param Form  $form
     * @param array $options
     */
    public function buildForm(Form $form, array $options = []);
}
