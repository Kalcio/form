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

/**
 * Represents a UI Element within a UI Schema.
 *
 * UI Elements define how specific parts of the form should be rendered, such as
 * controls, groups, or layouts.
 */
interface ControlInterface extends UiSchemaElementInterface
{
    /**
     * Gets the scope of the element, which links it to a schema property.
     *
     * @return string The scope of the element.
     */
    public function getScope(): string;

    /**
     * Gets the label for the element.
     *
     * @return string|null The label or `null` if not defined.
     */
    public function getLabel(): ?string;
}
