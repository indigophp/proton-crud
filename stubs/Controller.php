<?php

/*
 * This file is part of the Proton Crud package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Proton\Crud\Stub;

use Fuel\Fieldset\Form;
use Fuel\Validation\Validator;
use Proton\Crud\Controller as ParentController;

/**
 * Controller stub
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Controller extends ParentController
{
    /**
     * {@inheritdoc}
     */
    protected function createCreateForm()
    {
        return new Form;
    }

    /**
     * {@inheritdoc}
     */
    protected function createValidator()
    {
        return new Validator;
    }

    /**
     * {@inheritdoc}
     */
    protected function createUpdateForm()
    {
        return new Form;
    }
}
