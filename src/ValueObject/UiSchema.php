<?php

declare(strict_types=1);

/**
 * Derafu: Form - Form Builder with Easy Definition and Layouts.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\ValueObject;

use Derafu\Form\Contract\ValueObject\UiElementInterface;
use Derafu\Form\Contract\ValueObject\UiSchemaInterface;
use Derafu\Form\Serializer\JsonSerializer;

/**
 * Represents a UI Schema definition for a form.
 *
 * The UI Schema defines how the form should be presented to the user, including
 * layouts and UI-specific options that aren't part of the data schema.
 */
final class UiSchema implements UiSchemaInterface
{
    private const DEFAULT_TYPE = 'VerticalLayout';

    /**
     * Creates a new UiSchema from its definition.
     *
     * @param array $definition
     */
    public function __construct(private readonly array $definition)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return $this->definition['type'] ?? self::DEFAULT_TYPE;
    }

    /**
     * {@inheritDoc}
     */
    public function getElements(): array
    {
        return $this->definition['elements'] ?? [];
    }

    /**
     * {@inheritDoc}
     */
    public function getElement(int $index): ?UiElementInterface
    {
        return $this->getElements()[$index] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return $this->definition;
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

    /**
     * {@inheritDoc}
     */
    public static function fromArray(array $definition): self
    {
        return new self($definition);
    }
}
