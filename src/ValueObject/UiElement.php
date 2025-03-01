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

use Derafu\Form\Contract\UiElementInterface;
use Derafu\Form\Serializer\JsonSerializer;

/**
 * Represents a UI Element within a UI Schema.
 *
 * UI Elements define how specific parts of the form should be rendered, such as
 * controls, groups, or layouts.
 */
final class UiElement implements UiElementInterface
{
    private const DEFAULT_TYPE = 'Control';

    /**
     * Creates a new UiElement from its definition.
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
    public function getScope(): ?string
    {
        return $this->definition['scope'] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function getLabel(): ?string
    {
        return $this->definition['label'] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function getOptions(): ?array
    {
        return $this->definition['options'] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function getElements(): ?array
    {
        return $this->definition['elements'] ?? null;
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
    public function toJson(): string
    {
        return JsonSerializer::serialize($this->jsonSerialize());
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
