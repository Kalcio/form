<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Type;

use Derafu\Form\Abstract\AbstractType;

/**
 * Represents an integer type in the form.
 *
 * An integer type allows users to enter a numeric value.
 */
final class IntegerType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'integer';
    }

    /**
     * {@inheritDoc}
     */
    public function validateValue(mixed $value): bool
    {
        return is_numeric($value) && (int)$value == $value;
    }

    /**
     * {@inheritDoc}
     */
    public function castValue(mixed $value): mixed
    {
        return (int)$value;
    }

    /**
     * {@inheritDoc}
     */
    public function getJsonSchema(): array
    {
        return [
            'type' => 'integer',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function isGuessable(): bool
    {
        return true;
    }
}
