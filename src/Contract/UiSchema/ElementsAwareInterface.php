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

interface ElementsAwareInterface
{
    /**
     * Gets the elements of the category.
     *
     * @return array<UiSchemaElementInterface> The elements of the category.
     */
    public function getElements(): array;

    /**
     * Adds an element to the category.
     *
     * @param UiSchemaElementInterface $element The element to add.
     * @return self The current instance.
     */
    public function addElement(UiSchemaElementInterface $element): self;
}
