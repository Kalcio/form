<?php

declare(strict_types=1);

/**
 * Derafu: Form - Form Builder with Easy Definition and Layouts.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Contract\ValueObject;

use JsonSerializable;

/**
 * Represents a UI Element within a UI Schema.
 *
 * UI Elements define how specific parts of the form should be rendered, such as
 * controls, groups, or layouts.
 */
interface UiElementInterface extends JsonSerializable
{
    /**
     * Gets the type of UI element (e.g., "Control", "Group", "VerticalLayout").
     *
     * @return string The element type.
     */
    public function getType(): string;

    /**
     * Gets the scope of the element, which links it to a schema property.
     * Only applicable for Control elements (e.g., "#/properties/firstName").
     *
     * @return string|null The scope or `null` if not applicable.
     */
    public function getScope(): ?string;

    /**
     * Gets the label for the element.
     *
     * @return string|null The label or `null` if not defined.
     */
    public function getLabel(): ?string;

    /**
     * Gets additional options for the element.
     * These can include custom rendering hints, rules, etc.
     *
     * @return array|null Additional options or `null` if none defined.
     */
    public function getOptions(): ?array;

    /**
     * Gets child elements if this element is a container (like layouts or groups).
     *
     * @return UiElementInterface[]|null Array of child elements or `null` if not a container.
     */
    public function getElements(): ?array;

    /**
     * Converts the UI Element to an array representation.
     *
     * @return array The complete UI Element as an array.
     */
    public function toArray(): array;

    /**
     * Converts the UI Element to a JSON representation.
     *
     * @return string
     */
    public function toJson(): string;
}
