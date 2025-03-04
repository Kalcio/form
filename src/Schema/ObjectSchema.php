<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Schema;

use Derafu\Form\Abstract\AbstractPropertySchema;
use Derafu\Form\Contract\Schema\ObjectSchemaInterface;
use Derafu\Form\Contract\Schema\PropertySchemaInterface;

/**
 * Implementation of ObjectSchemaInterface.
 *
 * This class represents an object schema and provides object-specific
 * validations along with property management.
 */
class ObjectSchema extends AbstractPropertySchema implements ObjectSchemaInterface
{
    /**
     * The properties of the object.
     *
     * @var array<string,PropertySchemaInterface>
     */
    protected array $properties = [];

    /**
     * The properties that depend on others.
     *
     * @var array<string,string[]>|null
     */
    protected ?array $dependentRequired = null;

    /**
     * The maximum number of properties.
     *
     * @var int|null
     */
    protected ?int $maxProperties = null;

    /**
     * The minimum number of properties.
     *
     * @var int|null
     */
    protected ?int $minProperties = null;

    /**
     * The required properties.
     *
     * @var array<string>
     */
    protected array $required = [];

    /**
     * Whether additional properties are allowed.
     *
     * @var bool|array
     */
    protected bool|array $additionalProperties = true;

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return 'object';
    }

    /**
     * {@inheritDoc}
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * {@inheritDoc}
     */
    public function hasProperty(string $name): bool
    {
        return isset($this->properties[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function getProperty(string $name): ?PropertySchemaInterface
    {
        return $this->properties[$name] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function addProperty(PropertySchemaInterface $property): self
    {
        $this->properties[$property->getName()] = $property;

        return $this;
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
    public function setDependentRequired(array $dependentRequired): self
    {
        $this->dependentRequired = $dependentRequired;

        return $this;
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
    public function setMaxProperties(int $maxProperties): self
    {
        $this->maxProperties = $maxProperties;

        return $this;
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
    public function setMinProperties(int $minProperties): self
    {
        $this->minProperties = $minProperties;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getRequired(): array
    {
        return $this->required;
    }

    /**
     * {@inheritDoc}
     */
    public function setRequired(array $properties): self
    {
        $this->required = $properties;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getAdditionalProperties(): bool|array
    {
        return $this->additionalProperties;
    }

    /**
     * {@inheritDoc}
     */
    public function setAdditionalProperties(bool|array $additionalProperties = true): self
    {
        $this->additionalProperties = $additionalProperties;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $array = parent::toArray();

        // Convert properties to array.
        $propertiesArray = [];
        foreach ($this->properties as $name => $property) {
            $propertiesArray[$name] = $property->toArray();
        }

        if (!empty($propertiesArray)) {
            $array['properties'] = $propertiesArray;
        }

        if (!empty($this->required)) {
            $array['required'] = $this->required;
        }

        if ($this->dependentRequired !== null) {
            $array['dependentRequired'] = $this->dependentRequired;
        }

        if ($this->maxProperties !== null) {
            $array['maxProperties'] = $this->maxProperties;
        }

        if ($this->minProperties !== null) {
            $array['minProperties'] = $this->minProperties;
        }

        // Only include additionalProperties if it's not the default (true).
        if ($this->additionalProperties !== true) {
            $array['additionalProperties'] = $this->additionalProperties;
        }

        return $array;
    }

    /**
     * {@inheritDoc}
     */
    public static function fromArray(array $definition): self
    {
        $schema = new self($definition['name'] ?? '');

        // Set common properties.
        if (isset($definition['title'])) {
            $schema->setTitle($definition['title']);
        }

        if (isset($definition['description'])) {
            $schema->setDescription($definition['description']);
        }

        if (isset($definition['default'])) {
            $schema->setDefault($definition['default']);
        }

        if (isset($definition['deprecated'])) {
            $schema->setDeprecated($definition['deprecated']);
        }

        if (isset($definition['examples'])) {
            $schema->setExamples($definition['examples']);
        }

        if (isset($definition['readOnly'])) {
            $schema->setReadOnly($definition['readOnly']);
        }

        if (isset($definition['writeOnly'])) {
            $schema->setWriteOnly($definition['writeOnly']);
        }

        if (isset($definition['enum'])) {
            $schema->setEnum($definition['enum']);
        }

        if (isset($definition['const'])) {
            $schema->setConst($definition['const']);
        }

        if (isset($definition['allOf'])) {
            $schema->setAllOf($definition['allOf']);
        }

        if (isset($definition['anyOf'])) {
            $schema->setAnyOf($definition['anyOf']);
        }

        if (isset($definition['oneOf'])) {
            $schema->setOneOf($definition['oneOf']);
        }

        // Set object-specific properties.
        if (isset($definition['properties']) && is_array($definition['properties'])) {
            foreach ($definition['properties'] as $propName => $propDefinition) {
                // Determine the type of the property.
                $type = $propDefinition['type'] ?? 'string';

                // Create the appropriate property schema based on type.
                $propSchema = null;
                switch ($type) {
                    case 'string':
                        $propSchema = StringSchema::fromArray(
                            array_merge(['name' => $propName], $propDefinition)
                        );
                        break;
                    case 'number':
                        $propSchema = NumberSchema::fromArray(
                            array_merge(['name' => $propName], $propDefinition)
                        );
                        break;
                    case 'integer':
                        $propSchema = IntegerSchema::fromArray(
                            array_merge(['name' => $propName], $propDefinition)
                        );
                        break;
                    case 'array':
                        $propSchema = ArraySchema::fromArray(
                            array_merge(['name' => $propName], $propDefinition)
                        );
                        break;
                    case 'object':
                        $propSchema = self::fromArray(
                            array_merge(['name' => $propName], $propDefinition)
                        );
                        break;
                    case 'boolean':
                        $propSchema = BooleanSchema::fromArray(
                            array_merge(['name' => $propName], $propDefinition)
                        );
                        break;
                    case 'null':
                        $propSchema = NullSchema::fromArray(
                            array_merge(['name' => $propName], $propDefinition)
                        );
                        break;
                    default:
                        // Default to string for unknown types.
                        $propSchema = StringSchema::fromArray(
                            array_merge(['name' => $propName], $propDefinition)
                        );
                }

                $schema->addProperty($propSchema);
            }
        }

        if (isset($definition['required'])) {
            $schema->setRequired($definition['required']);
        }

        if (isset($definition['dependentRequired'])) {
            $schema->setDependentRequired($definition['dependentRequired']);
        }

        if (isset($definition['maxProperties'])) {
            $schema->setMaxProperties($definition['maxProperties']);
        }

        if (isset($definition['minProperties'])) {
            $schema->setMinProperties($definition['minProperties']);
        }

        if (isset($definition['additionalProperties'])) {
            $schema->setAdditionalProperties($definition['additionalProperties']);
        }

        return $schema;
    }
}
