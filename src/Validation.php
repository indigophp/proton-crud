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
     * Creates a CREATE validator
     *
     * @param Validator $validator
     */
    public function create(Validator $validator);

    /**
     * Creates an UPDATE validator
     *
     * @param Validator $validator
     */
    public function update(Validator $validator);
}
