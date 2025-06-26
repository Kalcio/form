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
 * Interface for a type resolver.
 */
interface TypeResolverInterface
{
    /**
     * Guess the type of a value.
     *
     * @param mixed $value The value to guess the type of.
     * @return TypeInterface The type of the value.
     */
    public function guess(mixed $value): TypeInterface;
}
