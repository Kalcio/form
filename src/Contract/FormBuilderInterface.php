<?php

declare(strict_types=1);

/**
 * Derafu: Form - Form Builder with Easy Definition and Layouts.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Contract;

use Derafu\Form\Contract\ValueObject\FormInterface;

/**
 * Interface for form builders.
 */
interface FormBuilderInterface
{
    /**
     * Builds a new form instance from the builder configuration.
     *
     * @return FormInterface A new form instance.
     */
    public function build(): FormInterface;
}
