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

use Fuel\Validation\Validator;
use Proton\Crud\Validation;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
abstract class Decorator implements Validation
{
    /**
     * @var Validation
     */
    protected $validation;

    /**
     * @param Validation $validation
     */
    public function __construct(Validation $validation)
    {
        $this->validation = $validation;
    }

    /**
     * {@inheritdoc}
     */
    public function create(Validator $validator)
    {
        $this->validation->create($validator);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Validator $validator)
    {
        $this->validation->update($validator);
    }
}
