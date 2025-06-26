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
 * Represents a category in a UI Schema.
 *
 * Categories are used to organize UI elements into a logical collection.
 */
interface CategoryInterface extends UiSchemaElementInterface, ElementsAwareInterface
{
    /**
     * Gets the label of the category.
     *
     * @return string The label of the category.
     */
    public function getLabel(): string;
}
