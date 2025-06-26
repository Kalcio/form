<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.dev>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Contract\UiSchema;

/**
 * Represents a group in a UI Schema.
 *
 * Groups are used to organize UI elements into a logical collection.
 */
interface GroupInterface extends FormUiSchemaInterface
{
    /**
     * Gets the label of the group.
     *
     * @return string The label of the group.
     */
    public function getLabel(): string;
}
