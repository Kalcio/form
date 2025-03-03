<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form;

use Derafu\Form\Contract\FormFieldInterface;
use Derafu\Form\Contract\Schema\PropertySchemaInterface;
use Derafu\Form\Contract\UiSchema\ControlInterface;

/**
 * Implementation of the FormFieldInterface.
 *
 * This class represents a field within a form, connecting a schema property
 * with its UI representation.
 */
final class FormField implements FormFieldInterface
{
    /**
     * Constructs a new form field.
     *
     * @param PropertySchemaInterface $property The property associated with
     * this field, defining its data structure and validation rules.
     * @param ControlInterface $control The control associated with this field,
     * defining how it should be visually represented.
     */
    public function __construct(
        private readonly PropertySchemaInterface $property,
        private readonly ControlInterface $control
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getProperty(): PropertySchemaInterface
    {
        return $this->property;
    }

    /**
     * {@inheritDoc}
     */
    public function getControl(): ControlInterface
    {
        return $this->control;
    }
}
