<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Contract\UiSchema;

use Stringable;

/**
 * Represents a label in a UI Schema.
 *
 * Labels are used to provide a human-readable name for a form element.
 */
interface LabelInterface extends UiSchemaElementInterface, Stringable
{
    /**
     * Gets the label text.
     *
     * @return string The label text.
     */
    public function getText(): string;
}
