<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Contract\Schema;

use JsonSerializable;

/**
 * Represents a base schema.
 *
 * Base schema defines the common properties for all schemas.
 */
interface PropertySchemaInterface extends JsonSerializable
{
    /**
     * Gets the property name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Sets the property name.
     *
     * @param string $name
     * @return self The current instance.
     */
    public function setName(string $name): self;

    /**
     * A preferably short description about the purpose of the property.
     *
     * @return string|null
     * @link https://www.learnjsonschema.com/2020-12/meta-data/title/
     */
    public function getTitle(): ?string;

    /**
     * Sets the property title.
     *
     * @param string $title
     * @return self The current instance.
     */
    public function setTitle(string $title): self;

    /**
     * An explanation about the purpose of the property.
     *
     * @return string|null
     * @link https://www.learnjsonschema.com/2020-12/meta-data/description/
     */
    public function getDescription(): ?string;

    /**
     * Sets the property description.
     *
     * @param string $description
     * @return self The current instance.
     */
    public function setDescription(string $description): self;

    /**
     * Supply a default value associated with the property.
     *
     * @return mixed
     * @link https://www.learnjsonschema.com/2020-12/meta-data/default/
     */
    public function getDefault(): mixed;

    /**
     * Sets the property default value.
     *
     * @param mixed $default
     * @return self The current instance.
     */
    public function setDefault(mixed $default): self;

    /**
     * Indicates that users should refrain from using the property.
     *
     * @return bool|null
     * @link https://www.learnjsonschema.com/2020-12/meta-data/deprecated/
     */
    public function isDeprecated(): ?bool;

    /**
     * Sets the property deprecated status.
     *
     * @param bool $deprecated
     * @return self The current instance.
     */
    public function setDeprecated(bool $deprecated = true): self;

    /**
     * Provide sample values associated with a particular property, for the
     * purpose of illustrating usage.
     *
     * @return array|null
     * @link https://www.learnjsonschema.com/2020-12/meta-data/examples/
     */
    public function getExamples(): ?array;

    /**
     * Sets the property examples.
     *
     * @param array $examples
     * @return self The current instance.
     */
    public function setExamples(array $examples): self;

    /**
     * Indicates that the value of the property is managed exclusively by the
     * backend, and attempts by an user to modify the value of this property are
     * expected to be ignored or rejected by that backend.
     *
     * @return bool|null
     * @link https://www.learnjsonschema.com/2020-12/meta-data/readonly/
     */
    public function isReadOnly(): ?bool;

    /**
     * Sets the property read-only status.
     *
     * @param bool $readOnly
     * @return self The current instance.
     */
    public function setReadOnly(bool $readOnly = true): self;

    /**
     * Indicates that the value is never present when the instance is retrieved
     * from the backend.
     *
     * @return bool|null
     * @link https://www.learnjsonschema.com/2020-12/meta-data/writeonly/
     */
    public function isWriteOnly(): ?bool;

    /**
     * Sets the property write-only status.
     *
     * @param bool $writeOnly
     * @return self The current instance.
     */
    public function setWriteOnly(bool $writeOnly = true): self;

    /**
     * Gets the schema type.
     *
     * @return string The schema type.
     * @link https://www.learnjsonschema.com/2020-12/validation/type/
     */
    public function getType(): string;

    /**
     * Gets the enum values for the property, if defined.
     *
     * Property value must be equal to one of the elements in this array.
     *
     * @return array|null Array of allowed values or `null` if not an enum.
     * @link https://www.learnjsonschema.com/2020-12/validation/enum/
     */
    public function getEnum(): ?array;

    /**
     * Sets the enum values for the property.
     *
     * @param array $enum
     * @return self The current instance.
     */
    public function setEnum(array $enum): self;

    /**
     * Gets the constant value that the property value must be equal.
     *
     * @return mixed
     * @link https://www.learnjsonschema.com/2020-12/validation/const/
     */
    public function getConst(): mixed;

    /**
     * Sets the constant value that the property value must be equal.
     *
     * @param mixed $const
     * @return self The current instance.
     */
    public function setConst(mixed $const): self;

    /**
     * Gets the list of options for a property where the property value must
     * match all of this options.
     *
     * @return array|null
     * @link https://www.learnjsonschema.com/2020-12/applicator/allof/
     */
    public function getAllOf(): ?array;

    /**
     * Sets the list of options for a property where the property value must
     * match all of this options.
     *
     * @param array $allOf
     * @return self The current instance.
     */
    public function setAllOf(array $allOf): self;

    /**
     * Gets the list of options for a property where the property value must
     * match at least one of this options.
     *
     * @return array|null
     * @link https://www.learnjsonschema.com/2020-12/applicator/anyof/
     */
    public function getAnyOf(): ?array;

    /**
     * Sets the list of options for a property where the property value must
     * match at least one of this options.
     *
     * @param array $anyOf
     * @return self The current instance.
     */
    public function setAnyOf(array $anyOf): self;

    /**
     * Gets the list of options for a property where the property value must
     * match exactly one of this options.
     *
     * @return array[]|null
     * @link https://www.learnjsonschema.com/2020-12/applicator/oneof/
     */
    public function getOneOf(): ?array;

    /**
     * Sets the list of options for a property where the property value must
     * match exactly one of this options.
     *
     * @param array[] $oneOf
     * @return self The current instance.
     */
    public function setOneOf(array $oneOf): self;

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
