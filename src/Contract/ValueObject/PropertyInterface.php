<?php

declare(strict_types=1);

/**
 * Derafu: Form - Form Builder with Easy Definition and Layouts.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Contract\ValueObject;

use JsonSerializable;

/**
 * Represents a schema property within a Schema.
 *
 * Properties define individual fields in a form, including their type,
 * validation rules, and other constraints.
 */
interface PropertyInterface extends JsonSerializable
{
    /**
     * Gets the property name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * A preferably short description about the purpose of the property.
     *
     * @return string|null
     * @link https://www.learnjsonschema.com/2020-12/meta-data/title/
     */
    public function getTitle(): ?string;

    /**
     * An explanation about the purpose of the property.
     *
     * @return string|null
     * @link https://www.learnjsonschema.com/2020-12/meta-data/description/
     */
    public function getDescription(): ?string;

    /**
     * Supply a default value associated with the property.
     *
     * @return mixed
     * @link https://www.learnjsonschema.com/2020-12/meta-data/default/
     */
    public function getDefault(): mixed;

    /**
     * Indicates that users should refrain from using the property.
     *
     * @return bool|null
     * @link https://www.learnjsonschema.com/2020-12/meta-data/deprecated/
     */
    public function isDeprecated(): ?bool;

    /**
     * Provide sample values associated with a particular property, for the
     * purpose of illustrating usage.
     *
     * @return array|null
     * @link https://www.learnjsonschema.com/2020-12/meta-data/examples/
     */
    public function getExamples(): ?array;

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
     * Indicates that the value is never present when the instance is retrieved
     * from the backend.
     *
     * @return bool|null
     * @link https://www.learnjsonschema.com/2020-12/meta-data/writeonly/
     */
    public function isWriteOnly(): ?bool;

    /**
     * Gets the type of the property must match.
     *
     * @return string|string[]
     * @link https://www.learnjsonschema.com/2020-12/validation/type/
     */
    public function getType(): string|array;

    /**
     * Gets the semantic information about a string property.
     *
     * @return string|null The format or `null` if not defined.
     * @link https://www.learnjsonschema.com/2020-12/format-annotation/format/
     */
    public function getFormat(): ?string;

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
     * Gets the constant value that the property value must be equal.
     *
     * @return mixed
     * @link https://www.learnjsonschema.com/2020-12/validation/const/
     */
    public function getConst(): mixed;

    /**
     * Gets the maximum length of a string.
     *
     * @return integer|null
     * @link https://www.learnjsonschema.com/2020-12/validation/maxlength/
     */
    public function getMaxLength(): ?int;

    /**
     * Gets the minimum length of a string.
     *
     * @return integer|null
     * @link https://www.learnjsonschema.com/2020-12/validation/minlength/
     */
    public function getMinLength(): ?int;

    /**
     * Gets the pattern that a string must match.
     *
     * @return string|null
     * @link https://www.learnjsonschema.com/2020-12/validation/pattern/
     */
    public function getPattern(): ?string;

    /**
     * Gets the exclusive maximum that the property value can reach.
     *
     * @return integer|float|null
     * @link https://www.learnjsonschema.com/2020-12/validation/exclusivemaximum/
     */
    public function getExclusiveMaximum(): int|float|null;

    /**
     * Gets the exclusive minimum that the property value can reach.
     *
     * @return integer|float|null
     * @link https://www.learnjsonschema.com/2020-12/validation/exclusiveminimum/
     */
    public function getExclusiveMinimum(): int|float|null;

    /**
     * Gets the maximum that the property value can reach.
     *
     * @return integer|float|null
     * @link https://www.learnjsonschema.com/2020-12/validation/maximum/
     */
    public function getMaximum(): int|float|null;

    /**
     * Gets the minimum that the property value can reach.
     *
     * @return integer|float|null
     * @link https://www.learnjsonschema.com/2020-12/validation/minimum/
     */
    public function getMinimum(): int|float|null;

    /**
     * Gets the divisor of the value property that results in an integer value.
     *
     * @return integer|float|null
     * @link https://www.learnjsonschema.com/2020-12/validation/multipleof/
     */
    public function getMultipleOf(): int|float|null;

    /**
     * Gets the list of properties that depend on others in order to be used.
     *
     * @return array<string,string[]>
     * @link https://www.learnjsonschema.com/2020-12/validation/dependentrequired/
     */
    public function getDependentRequired(): ?array;

    /**
     * Gets the maximum quantity of properties.
     *
     * @return integer|null
     * @link https://www.learnjsonschema.com/2020-12/validation/maxproperties/
     */
    public function getMaxProperties(): ?int;

    /**
     * Gets the minimum quantity of properties.
     *
     * @return integer|null
     * @link https://www.learnjsonschema.com/2020-12/validation/minproperties/
     */
    public function getMinProperties(): ?int;

    /**
     * Checks if the property is required.
     *
     * Note: This is typically defined at the parent schema level.
     *
     * @return bool True if required, false otherwise.
     * @link https://www.learnjsonschema.com/2020-12/validation/required/
     */
    public function isRequired(): ?bool;

    /**
     * Gets the maximum number of items on array properties.
     *
     * @return integer|null
     * @link https://www.learnjsonschema.com/2020-12/validation/maxitems/
     */
    public function getMaxItems(): ?int;

    /**
     * Gets the minimum number of items on array properties.
     *
     * @return integer|null
     * @link https://www.learnjsonschema.com/2020-12/validation/minitems/
     */
    public function getMinItems(): ?int;

    /**
     * Gets the sub-schemas that at least one element in an array property mus
     * match.
     *
     * @return array|null
     * @link https://www.learnjsonschema.com/2020-12/applicator/contains/
     */
    public function getContains(): ?array;

    /**
     * Gets the maximum times that the value of `getContains()` can be.
     *
     * @return integer|null
     * @link https://www.learnjsonschema.com/2020-12/validation/maxcontains/
     */
    public function getMaxContains(): ?int;

    /**
     * Gets the minimum times that the value of `getContains()` can be.
     *
     * @return integer|null
     * @link https://www.learnjsonschema.com/2020-12/validation/mincontains/
     */
    public function getMinContains(): ?int;

    /**
     * Indicates if the values of a property must be all equals.
     *
     * @return boolean|null
     * @link https://www.learnjsonschema.com/2020-12/validation/uniqueitems/
     */
    public function needUniqueItems(): ?bool;

    /**
     * Gets the list of options for a property where the property value must
     * match all of this options.
     *
     * @return array|null
     * @link https://www.learnjsonschema.com/2020-12/applicator/allof/
     */
    public function getAllOf(): ?array;

    /**
     * Gets the list of options for a property where the property value must
     * match at least one of this options.
     *
     * @return array|null
     * @link https://www.learnjsonschema.com/2020-12/applicator/anyof/
     */
    public function getAnyOf(): ?array;

    /**
     * Gets the list of options for a property where the property value must
     * match exactly one of this options.
     *
     * @return array[]|null
     * @link https://www.learnjsonschema.com/2020-12/applicator/oneof/
     */
    public function getOneOf(): ?array;

    /**
     * Converts the Property to an array representation.
     *
     * @return array The complete property as an array.
     */
    public function toArray(): array;

    /**
     * Converts the Property to a JSON representation.
     *
     * @return string
     */
    public function toJson(): string;

    /**
     * Create a Property instance from an array.
     *
     * @param string $name The name of the property.
     * @param array $definition The property definition as an array.
     * @return self
     */
    public static function fromArray(
        string $name,
        array $definition
    ): self;
}
