<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Abstract;

use Derafu\Form\Contract\UiSchema\UiSchemaElementInterface;
use Derafu\Support\JsonSerializer;

/**
 * Abstract base class for UI Schema elements.
 *
 * This class provides a common implementation for UI Schema elements, including
 * type handling and JSON serialization.
 */
abstract class AbstractUiSchemaElement implements UiSchemaElementInterface
{
    /**
     * Creates a new Control UI Element from its definition.
     *
     * @param array $definition
     */
    public function __construct(protected readonly array $definition)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * {@inheritDoc}
     */
    public function toJson(): string
    {
        return JsonSerializer::serialize($this->jsonSerialize());
    }
}
