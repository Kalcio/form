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
use InvalidArgumentException;

/**
 * Interface for form factories.
 */
interface FormFactoryInterface
{
    /**
     * Creates a new form instance from the provided definition.
     *
     * If `schema` or `uischema` are not provided, they will be automatically
     * generated based on the data.
     *
     * @param array $definition The form definition with schema, uischema and data.
     * @return FormInterface A new form instance.
     * @throws InvalidArgumentException If the definition is invalid.
     */
    public static function create(array $definition): FormInterface;
}
