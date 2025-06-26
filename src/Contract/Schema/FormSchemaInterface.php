<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.dev>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Contract\Schema;

/**
 * Represents a form schema.
 */
interface FormSchemaInterface extends ObjectSchemaInterface
{
    /**
     * Gets the definitions section used for schema references.
     *
     * @return array<string,PropertySchemaInterface>
     */
    public function getDefinitions(): array;

    /**
     * Sets the definitions section used for schema references.
     *
     * @param array<string,PropertySchemaInterface> $definitions
     * @return static The current instance.
     */
    public function setDefinitions(array $definitions): static;
}
