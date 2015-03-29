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

use Fuel\Validation\Validator;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface Validation
{
    /**
     * Builds validation rules
     *
     * @param Validator $validator
     * @param array     $options
     */
    public function buildValidation(Validator $validator, array $options = []);
}
