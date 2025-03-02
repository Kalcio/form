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
use Derafu\Form\Serializer\JsonSerializer;

/**
 * Implementation of the PropertyInterface that represents a schema property.
 *
 * This class provides a concrete implementation for form fields including their
 * type, validation rules, and other constraints.
 */
class Property implements PropertyInterface
{
    /**
     * The name of the property.
     *
     * @var string
     */
    protected string $name;

    /**
     * Short description about the purpose of the property.
     *
     * @var string|null
     */
    protected ?string $title = null;

    /**
     * Explanation about the purpose of the property.
     *
     * @var string|null
     */
    protected ?string $description = null;

    /**
     * Default value associated with the property.
     *
     * @var mixed
     */
    protected mixed $default = null;

    /**
     * Indicates that users should refrain from using the property.
     *
     * @var bool|null
     */
    protected ?bool $deprecated = null;

    /**
     * Sample values associated with a particular property.
     *
     * @var array|null
     */
    protected ?array $examples = null;

    /**
     * Indicates that the value of the property is managed exclusively by the
     * backend.
     *
     * @var bool|null
     */
    protected ?bool $readOnly = null;

    /**
     * Indicates that the value is never present when the instance is retrieved
     * from the backend.
     *
     * @var bool|null
     */
    protected ?bool $writeOnly = null;

    /**
     * Type of the property.
     *
     * @var string|string[]
     */
    protected string|array $type;

    /**
     * Semantic information about a string property.
     *
     * @var string|null
     */
    protected ?string $format = null;

    /**
     * Allowed values for the property.
     *
     * @var array|null
     */
    protected ?array $enum = null;

    /**
     * Constant value that the property value must be equal to.
     *
     * @var mixed
     */
    protected mixed $const = null;

    /**
     * Maximum length of a string.
     *
     * @var int|null
     */
    protected ?int $maxLength = null;

    /**
     * Minimum length of a string.
     *
     * @var int|null
     */
    protected ?int $minLength = null;

    /**
     * Pattern that a string must match.
     *
     * @var string|null
     */
    protected ?string $pattern = null;

    /**
     * Exclusive maximum that the property value can reach.
     *
     * @var int|float|null
     */
    protected int|float|null $exclusiveMaximum = null;

    /**
     * Exclusive minimum that the property value can reach.
     *
     * @var int|float|null
     */
    protected int|float|null $exclusiveMinimum = null;

    /**
     * Maximum that the property value can reach.
     *
     * @var int|float|null
     */
    protected int|float|null $maximum = null;

    /**
     * Minimum that the property value can reach.
     *
     * @var int|float|null
     */
    protected int|float|null $minimum = null;

    /**
     * Divisor of the value property that results in an integer value.
     *
     * @var int|float|null
     */
    protected int|float|null $multipleOf = null;

    /**
     * List of properties that depend on others to be used.
     *
     * @var array<string,string[]>|null
     */
    protected ?array $dependentRequired = null;

    /**
     * Maximum quantity of properties.
     *
     * @var int|null
     */
    protected ?int $maxProperties = null;

    /**
     * Minimum quantity of properties.
     *
     * @var int|null
     */
    protected ?int $minProperties = null;

    /**
     * Whether the property is required.
     *
     * @var bool|null
     */
    protected ?bool $required = null;

    /**
     * Maximum number of items on array properties.
     *
     * @var int|null
     */
    protected ?int $maxItems = null;

    /**
     * Minimum number of items on array properties.
     *
     * @var int|null
     */
    protected ?int $minItems = null;

    /**
     * Sub-schemas that at least one element in an array property must match.
     *
     * @var array|null
     */
    protected ?array $contains = null;

    /**
     * Maximum times that the value of contains can be.
     *
     * @var int|null
     */
    protected ?int $maxContains = null;

    /**
     * Minimum times that the value of contains can be.
     *
     * @var int|null
     */
    protected ?int $minContains = null;

    /**
     * Indicates if the values of a property must be all equals.
     *
     * @var bool|null
     */
    protected ?bool $uniqueItems = null;

    /**
     * List of options where the property value must match all of them.
     *
     * @var array|null
     */
    protected ?array $allOf = null;

    /**
     * List of options where the property value must match at least one.
     *
     * @var array|null
     */
    protected ?array $anyOf = null;

    /**
     * List of options where the property value must match exactly one.
     *
     * @var array|null
     */
    protected ?array $oneOf = null;

    /**
     * Constructor for creating a Property instance with individual properties.
     *
     * @param string $name The name of the property.
     * @param string|string[] $type The type(s) of the property.
     * @param string|null $title Short description about the purpose of the property.
     * @param string|null $description Explanation about the purpose of the property.
     * @param mixed $default Default value associated with the property.
     * @param bool|null $deprecated Indicates that users should refrain from using the property.
     * @param array|null $examples Sample values associated with a particular property.
     * @param bool|null $readOnly Indicates that the value is managed exclusively by the backend.
     * @param bool|null $writeOnly Indicates that the value is never present when retrieved from backend.
     * @param string|null $format Semantic information about a string property.
     * @param array|null $enum Allowed values for the property.
     * @param mixed $const Constant value that the property value must be equal to.
     * @param int|null $maxLength Maximum length of a string.
     * @param int|null $minLength Minimum length of a string.
     * @param string|null $pattern Pattern that a string must match.
     * @param int|float|null $exclusiveMaximum Exclusive maximum that the property value can reach.
     * @param int|float|null $exclusiveMinimum Exclusive minimum that the property value can reach.
     * @param int|float|null $maximum Maximum that the property value can reach.
     * @param int|float|null $minimum Minimum that the property value can reach.
     * @param int|float|null $multipleOf Divisor of the value property that results in an integer value.
     * @param array|null $dependentRequired List of properties that depend on others to be used.
     * @param int|null $maxProperties Maximum quantity of properties.
     * @param int|null $minProperties Minimum quantity of properties.
     * @param bool|null $required Whether the property is required.
     * @param int|null $maxItems Maximum number of items on array properties.
     * @param int|null $minItems Minimum number of items on array properties.
     * @param array|null $contains Sub-schemas that at least one element in an array property must match.
     * @param int|null $maxContains Maximum times that the value of contains can be.
     * @param int|null $minContains Minimum times that the value of contains can be.
     * @param bool|null $uniqueItems Indicates if the values of a property must be all equals.
     * @param array|null $allOf List of options where the property value must match all of them.
     * @param array|null $anyOf List of options where the property value must match at least one.
     * @param array|null $oneOf List of options where the property value must match exactly one.
     */
    public function __construct(
        string $name,
        string|array $type,
        ?string $title = null,
        ?string $description = null,
        mixed $default = null,
        ?bool $deprecated = null,
        ?array $examples = null,
        ?bool $readOnly = null,
        ?bool $writeOnly = null,
        ?string $format = null,
        ?array $enum = null,
        mixed $const = null,
        ?int $maxLength = null,
        ?int $minLength = null,
        ?string $pattern = null,
        int|float|null $exclusiveMaximum = null,
        int|float|null $exclusiveMinimum = null,
        int|float|null $maximum = null,
        int|float|null $minimum = null,
        int|float|null $multipleOf = null,
        ?array $dependentRequired = null,
        ?int $maxProperties = null,
        ?int $minProperties = null,
        ?bool $required = null,
        ?int $maxItems = null,
        ?int $minItems = null,
        ?array $contains = null,
        ?int $maxContains = null,
        ?int $minContains = null,
        ?bool $uniqueItems = null,
        ?array $allOf = null,
        ?array $anyOf = null,
        ?array $oneOf = null
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->title = $title;
        $this->description = $description;
        $this->default = $default;
        $this->deprecated = $deprecated;
        $this->examples = $examples;
        $this->readOnly = $readOnly;
        $this->writeOnly = $writeOnly;
        $this->format = $format;
        $this->enum = $enum;
        $this->const = $const;
        $this->maxLength = $maxLength;
        $this->minLength = $minLength;
        $this->pattern = $pattern;
        $this->exclusiveMaximum = $exclusiveMaximum;
        $this->exclusiveMinimum = $exclusiveMinimum;
        $this->maximum = $maximum;
        $this->minimum = $minimum;
        $this->multipleOf = $multipleOf;
        $this->dependentRequired = $dependentRequired;
        $this->maxProperties = $maxProperties;
        $this->minProperties = $minProperties;
        $this->required = $required;
        $this->maxItems = $maxItems;
        $this->minItems = $minItems;
        $this->contains = $contains;
        $this->maxContains = $maxContains;
        $this->minContains = $minContains;
        $this->uniqueItems = $uniqueItems;
        $this->allOf = $allOf;
        $this->anyOf = $anyOf;
        $this->oneOf = $oneOf;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * {@inheritDoc}
     */
    public function getDefault(): mixed
    {
        return $this->default;
    }

    /**
     * {@inheritDoc}
     */
    public function isDeprecated(): ?bool
    {
        return $this->deprecated;
    }

    /**
     * {@inheritDoc}
     */
    public function getExamples(): ?array
    {
        return $this->examples;
    }

    /**
     * {@inheritDoc}
     */
    public function isReadOnly(): ?bool
    {
        return $this->readOnly;
    }

    /**
     * {@inheritDoc}
     */
    public function isWriteOnly(): ?bool
    {
        return $this->writeOnly;
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): string|array
    {
        return $this->type;
    }

    /**
     * {@inheritDoc}
     */
    public function getFormat(): ?string
    {
        return $this->format;
    }

    /**
     * {@inheritDoc}
     */
    public function getEnum(): ?array
    {
        return $this->enum;
    }

    /**
     * {@inheritDoc}
     */
    public function getConst(): mixed
    {
        return $this->const;
    }

    /**
     * {@inheritDoc}
     */
    public function getMaxLength(): ?int
    {
        return $this->maxLength;
    }

    /**
     * {@inheritDoc}
     */
    public function getMinLength(): ?int
    {
        return $this->minLength;
    }

    /**
     * {@inheritDoc}
     */
    public function getPattern(): ?string
    {
        return $this->pattern;
    }

    /**
     * {@inheritDoc}
     */
    public function getExclusiveMaximum(): int|float|null
    {
        return $this->exclusiveMaximum;
    }

    /**
     * {@inheritDoc}
     */
    public function getExclusiveMinimum(): int|float|null
    {
        return $this->exclusiveMinimum;
    }

    /**
     * {@inheritDoc}
     */
    public function getMaximum(): int|float|null
    {
        return $this->maximum;
    }

    /**
     * {@inheritDoc}
     */
    public function getMinimum(): int|float|null
    {
        return $this->minimum;
    }

    /**
     * {@inheritDoc}
     */
    public function getMultipleOf(): int|float|null
    {
        return $this->multipleOf;
    }

    /**
     * {@inheritDoc}
     */
    public function getDependentRequired(): ?array
    {
        return $this->dependentRequired;
    }

    /**
     * {@inheritDoc}
     */
    public function getMaxProperties(): ?int
    {
        return $this->maxProperties;
    }

    /**
     * {@inheritDoc}
     */
    public function getMinProperties(): ?int
    {
        return $this->minProperties;
    }

    /**
     * {@inheritDoc}
     */
    public function isRequired(): ?bool
    {
        return $this->required;
    }

    /**
     * {@inheritDoc}
     */
    public function getMaxItems(): ?int
    {
        return $this->maxItems;
    }

    /**
     * {@inheritDoc}
     */
    public function getMinItems(): ?int
    {
        return $this->minItems;
    }

    /**
     * {@inheritDoc}
     */
    public function getContains(): ?array
    {
        return $this->contains;
    }

    /**
     * {@inheritDoc}
     */
    public function getMaxContains(): ?int
    {
        return $this->maxContains;
    }

    /**
     * {@inheritDoc}
     */
    public function getMinContains(): ?int
    {
        return $this->minContains;
    }

    /**
     * {@inheritDoc}
     */
    public function needUniqueItems(): ?bool
    {
        return $this->uniqueItems;
    }

    /**
     * {@inheritDoc}
     */
    public function getAllOf(): ?array
    {
        return $this->allOf;
    }

    /**
     * {@inheritDoc}
     */
    public function getAnyOf(): ?array
    {
        return $this->anyOf;
    }

    /**
     * {@inheritDoc}
     */
    public function getOneOf(): ?array
    {
        return $this->oneOf;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $array = [
            'name' => $this->name,
            'type' => $this->type,
        ];

        // Add non-null properties to the array.
        $propertyMap = [
            'title' => 'title',
            'description' => 'description',
            'default' => 'default',
            'deprecated' => 'deprecated',
            'examples' => 'examples',
            'readOnly' => 'readOnly',
            'writeOnly' => 'writeOnly',
            'format' => 'format',
            'enum' => 'enum',
            'const' => 'const',
            'maxLength' => 'maxLength',
            'minLength' => 'minLength',
            'pattern' => 'pattern',
            'exclusiveMaximum' => 'exclusiveMaximum',
            'exclusiveMinimum' => 'exclusiveMinimum',
            'maximum' => 'maximum',
            'minimum' => 'minimum',
            'multipleOf' => 'multipleOf',
            'dependentRequired' => 'dependentRequired',
            'maxProperties' => 'maxProperties',
            'minProperties' => 'minProperties',
            'required' => 'required',
            'maxItems' => 'maxItems',
            'minItems' => 'minItems',
            'contains' => 'contains',
            'maxContains' => 'maxContains',
            'minContains' => 'minContains',
            'uniqueItems' => 'uniqueItems',
            'allOf' => 'allOf',
            'anyOf' => 'anyOf',
            'oneOf' => 'oneOf',
        ];

        foreach ($propertyMap as $property => $key) {
            if ($this->$property !== null) {
                $array[$key] = $this->$property;
            }
        }

        return $array;
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
    public static function fromArray(
        string $name,
        array $definition
    ): self {
        return new self(
            $name,
            $definition['type'] ?? 'string',
            $definition['title'] ?? null,
            $definition['description'] ?? null,
            $definition['default'] ?? null,
            $definition['deprecated'] ?? null,
            $definition['examples'] ?? null,
            $definition['readOnly'] ?? null,
            $definition['writeOnly'] ?? null,
            $definition['format'] ?? null,
            $definition['enum'] ?? null,
            $definition['const'] ?? null,
            $definition['maxLength'] ?? null,
            $definition['minLength'] ?? null,
            $definition['pattern'] ?? null,
            $definition['exclusiveMaximum'] ?? null,
            $definition['exclusiveMinimum'] ?? null,
            $definition['maximum'] ?? null,
            $definition['minimum'] ?? null,
            $definition['multipleOf'] ?? null,
            $definition['dependentRequired'] ?? null,
            $definition['maxProperties'] ?? null,
            $definition['minProperties'] ?? null,
            $definition['required'] ?? null,
            $definition['maxItems'] ?? null,
            $definition['minItems'] ?? null,
            $definition['contains'] ?? null,
            $definition['maxContains'] ?? null,
            $definition['minContains'] ?? null,
            $definition['uniqueItems'] ?? null,
            $definition['allOf'] ?? null,
            $definition['anyOf'] ?? null,
            $definition['oneOf'] ?? null
        );
    }
}
