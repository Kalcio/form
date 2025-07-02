<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.dev>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Contract\Type;

/**
 * Interface for a type provider.
 */
interface TypeProviderInterface
{
    /**
     * Get the types.
     *
     * The order of the types is important if the type resolver is used. Types
     * that are more specific should be listed first.
     *
     * @return array<class-string<TypeInterface>|TypeInterface>
     */
    public function getTypes(): array;
}
