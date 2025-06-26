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
 * Represents a UI Schema definition for a form.
 *
 * The UI Schema defines how the form should be presented to the user, including
 * layouts and UI-specific options that aren't part of the data schema.
 */
interface FormUiSchemaInterface extends UiSchemaElementInterface, ElementsAwareInterface
{
}
