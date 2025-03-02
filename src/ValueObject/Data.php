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

use Derafu\Form\Contract\ValueObject\DataInterface;
use Derafu\Form\Serializer\JsonSerializer;

/**
 * Represents the data for a form.
 *
 * Contains the actual values for the form fields, which can be used to populate
 * the form or extracted after submission.
 */
final class Data implements DataInterface
{
    /**
     * Creates a new Data.
     *
     * @param array $data
     */
    public function __construct(private readonly array $data)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $propertyPath): bool
    {
        $parts = $this->parsePath($propertyPath);
        $current = $this->data;

        foreach ($parts as $part) {
            if (!is_array($current) || !array_key_exists($part, $current)) {
                return false;
            }
            $current = $current[$part];
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $propertyPath, mixed $default = null): mixed
    {
        $parts = $this->parsePath($propertyPath);
        $current = $this->data;

        foreach ($parts as $part) {
            if (!is_array($current) || !array_key_exists($part, $current)) {
                return $default;
            }
            $current = $current[$part];
        }

        return $current;
    }

    /**
     * {@inheritDoc}
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return $this->data;
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
     * Handle dot notation.
     *
     * @param string $path
     * @return array
     */
    private function parsePath(string $path): array
    {
        if (str_contains($path, '.')) {
            return explode('.', $path);
        }

        return [$path];
    }

    /**
     * {@inheritDoc}
     */
    public static function fromArray(array $data): self
    {
        return new self($data);
    }
}
