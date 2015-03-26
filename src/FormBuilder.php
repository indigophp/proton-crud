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
     * Creates a CREATE form
     *
     * @param Form $form
     */
    public function create(Form $form);

    /**
     * Creates an UPDATE form
     *
     * @param Form $form
     */
    public function update(Form $form);
}
