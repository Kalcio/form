<?php

declare(strict_types=1);

/**
 * Derafu: Form - Form Builder with Easy Definition and Layouts.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Contract;

use JsonSerializable;

/**
 * Represents a Schema definition for a form.
 *
 * The Schema defines the data structure, types, and validation rules for form
 * fields.
 */
interface SchemaInterface extends JsonSerializable
{
    /**
     * Gets the schema type (usually "object" for forms).
     *
     * @return string The schema type
     */
    public function getType(): string;

    /**
     * Gets all properties defined in the schema.
     *
     * @return array<string,PropertyInterface> An associative array of property
     * definitions.
     */
    public function getProperties(): array;

    /**
     * Checks if a specific property exists in the schema.
     *
     * @param string $name The property name to check.
     * @return bool True if the property exists, false otherwise.
     */
    public function hasProperty(string $name): bool;

    /**
     * Gets the definition for a specific property.
     *
     * @param string $name The property name.
     * @return PropertyInterface|null The property definition or null if not found.
     */
    public function getProperty(string $name): ?PropertyInterface;

    /**
     * Gets the list of required properties.
     *
     * @return array List of property names that are required.
     */
    public function getRequired(): array;

    /**
     * Gets the definitions section used for schema references.
     *
     * @return array<string,array> The definitions section.
     */
    public function getDefinitions(): array;

    /**
     * Gets the `additionalProperties` value, which determines if properties not
     * defined in the schema are allowed.
     *
     * @return bool|array A boolean indicating if additional properties are
     * allowed, or an array defining their schema.
     */
    public function getAdditionalProperties(): bool|array;

    /**
     * Converts the Schema to an array representation.
     *
     * @return array The complete schema as an array.
     */
    public function toArray(): array;

    /**
     * Converts the Schema to a JSON representation.
     *
     * @return string
     */
    public function toJson(): string;

    /**
     * Create a Schema instance from an array.
     *
     * @param array $definition The schema definition as an array.
     * @return self
     */
    public static function fromArray(array $definition): self;
}
