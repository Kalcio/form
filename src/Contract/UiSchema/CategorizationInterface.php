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
 * Represents a categorization in a UI Schema.
 *
 * Categorizations are used to organize UI elements into a logical collection.
 */
interface CategorizationInterface extends FormUiSchemaInterface
{
    /**
     * Gets the categories of the categorization.
     *
     * @return array<CategoryInterface> The categories of the categorization.
     */
    public function getCategories(): array;

    /**
     * Adds a category to the categorization.
     *
     * @param CategoryInterface $category The category to add.
     * @return static The current instance.
     */
    public function addCategory(CategoryInterface $category): static;

    /**
     * Gets the options of the categorization.
     *
     * @return array<string, mixed> The options of the categorization.
     */
    public function getOptions(): array;
}
