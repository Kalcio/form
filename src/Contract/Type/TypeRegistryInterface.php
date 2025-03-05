<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Contract\Type;

use InvalidArgumentException;

/**
 * Interface for a type registry.
 */
interface TypeRegistryInterface
{
    /**
     * Register a type in the registry.
     *
     * @param TypeInterface $type The type to register.
     */
    public function register(TypeInterface $type): static;

    /**
     * Get a type from the registry.
     *
     * @param string|class-string<TypeInterface> $type The type to get.
     * @return TypeInterface The type.
     * @throws InvalidArgumentException If no type is registered for the given name.
     */
    public function get(string $type): TypeInterface;

    /**
     * Check if a type is registered in the registry.
     *
     * @param string|class-string<TypeInterface> $type The type to check.
     * @return bool Whether the type is registered.
     */
    public function has(string $type): bool;

    /**
     * Get all guessable types.
     *
     * @return array<TypeInterface>
     */
    public function getGuessableTypes(): array;
}
