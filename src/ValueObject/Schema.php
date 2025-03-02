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

use Derafu\Form\Contract\ValueObject\PropertyInterface;
use Derafu\Form\Contract\ValueObject\SchemaInterface;
use Derafu\Form\Serializer\JsonSerializer;

/**
 * Represents a Schema definition for a form.
 *
 * The Schema defines the data structure, types, and validation rules for form
 * fields.
 */
final class Schema implements SchemaInterface
{
    private const DEFAULT_TYPE = 'object';

    /**
     * Creates a new Schema from its definition.
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
    public function getProperties(): array
    {
        return $this->definition['properties'] ?? [];
    }

    /**
     * {@inheritDoc}
     */
    public function hasProperty(string $name): bool
    {
        return isset($this->definition['properties'][$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function getProperty(string $name): ?PropertyInterface
    {
        return $this->definition['properties'][$name] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function getRequired(): array
    {
        return $this->definition['required'] ?? [];
    }

    /**
     * {@inheritDoc}
     */
    public function getDefinitions(): array
    {
        return $this->definition['definitions'] ?? $this->definition['$defs'] ?? [];
    }

    /**
     * {@inheritDoc}
     */
    public function getAdditionalProperties(): bool|array
    {
        $additionalProps = $this->definition['additionalProperties'] ?? true;

        return is_bool($additionalProps) || is_array($additionalProps)
            ? $additionalProps
            : true
        ;
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
