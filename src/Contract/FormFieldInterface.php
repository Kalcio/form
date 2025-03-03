<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Contract;

use Derafu\Form\Contract\Schema\PropertySchemaInterface;
use Derafu\Form\Contract\UiSchema\ControlInterface;

/**
 * Represents a field within a form.
 *
 * A form field connects a schema property (data definition) with a UI element
 * (visual representation), creating a complete field that can be rendered and
 * processed.
 */
interface FormFieldInterface
{
    /**
     * Gets the property associated with this field.
     *
     * @return PropertySchemaInterface The property that defines the data
     * structure and validation rules for this field.
     */
    public function getProperty(): PropertySchemaInterface;

    /**
     * Gets the control associated with this field.
     *
     * @return ControlInterface The control that defines how the field should be
     * visually represented.
     */
    public function getControl(): ControlInterface;
}
