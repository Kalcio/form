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

use JsonSerializable;

/**
 * Represents a UI Schema definition for a form.
 *
 * The UI Schema defines how the form should be presented to the user, including
 * layouts and UI-specific options that aren't part of the data schema.
 */
interface UiSchemaInterface extends JsonSerializable
{
    /**
     * Gets the UI Schema type (e.g., "VerticalLayout", "HorizontalLayout").
     *
     * @return string The UI Schema type.
     */
    public function getType(): string;

    /**
     * Gets all UI elements contained in this UI Schema.
     *
     * @return UiElementInterface[] An array of UI elements.
     */
    public function getElements(): array;

    /**
     * Gets a specific UI element by its index.
     *
     * @param int $index The index of the element to retrieve.
     * @return UiElementInterface|null The element or `null` if not found.
     */
    public function getElement(int $index): ?UiElementInterface;

    /**
     * Converts the UI Schema to an array representation.
     *
     * @return array The complete UI Schema as an array.
     */
    public function toArray(): array;

    /**
     * Converts the UI Schema to a JSON representation.
     *
     * @return string
     */
    public function toJson(): string;

    /**
     * Create a UiSchema instance from an array.
     *
     * @param array $definition The uischema definition as an array.
     * @return self
     */
    public static function fromArray(array $definition): self;
}
