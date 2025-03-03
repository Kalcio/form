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

use Derafu\Form\Contract\Schema\FormSchemaInterface;
use Derafu\Form\Contract\Schema\PropertySchemaInterface;
use ReflectionClass;
use ReflectionProperty;

/**
 * Implementation of SchemaInterface.
 *
 * This class represents the root schema of a form and adds support for
 * definitions section, which can be used for schema references.
 */
class FormSchema extends ObjectSchema implements FormSchemaInterface
{
    /**
     * The definitions section used for schema references.
     *
     * @var array<string,PropertySchemaInterface>
     */
    protected array $definitions = [];

    /**
     * {@inheritDoc}
     */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefinitions(array $definitions): self
    {
        $this->definitions = $definitions;
        return $this;
    }

    /**
     * Adds a definition to the schema.
     *
     * @param string $name The name of the definition.
     * @param PropertySchemaInterface $schema The schema to add as a definition.
     * @return self The current instance.
     */
    public function addDefinition(string $name, PropertySchemaInterface $schema): self
    {
        $this->definitions[$name] = $schema;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $array = parent::toArray();

        // Convert definitions to array.
        if (!empty($this->definitions)) {
            $definitionsArray = [];
            foreach ($this->definitions as $name => $definition) {
                $definitionsArray[$name] = $definition->toArray();
            }
            // Using $defs as per JSON Schema 2020-12.
            $array['$defs'] = $definitionsArray;
        }

        return $array;
    }

    /**
     * {@inheritDoc}
     */
    public static function fromArray(array $definition): self
    {
        $schema = new self($definition['name'] ?? '');

        // First set all the parent properties by calling the parent fromArray.
        $parentSchema = parent::fromArray($definition);

        // Copy all properties from the parent schema.
        $reflectionClass = new ReflectionClass(ObjectSchema::class);
        $properties = $reflectionClass->getProperties(ReflectionProperty::IS_PROTECTED);
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $property->setValue($schema, $property->getValue($parentSchema));
        }

        // Set definitions.
        if (isset($definition['$defs']) && is_array($definition['$defs'])) {
            $definitions = [];
            foreach ($definition['$defs'] as $defName => $defDefinition) {
                // Determine the type of the definition.
                $type = $defDefinition['type'] ?? 'string';

                // Create the appropriate property schema based on type.
                $defSchema = null;
                switch ($type) {
                    case 'string':
                        $defSchema = StringSchema::fromArray(
                            array_merge(['name' => $defName], $defDefinition)
                        );
                        break;
                    case 'number':
                        $defSchema = NumberSchema::fromArray(
                            array_merge(['name' => $defName], $defDefinition)
                        );
                        break;
                    case 'integer':
                        $defSchema = IntegerSchema::fromArray(
                            array_merge(['name' => $defName], $defDefinition)
                        );
                        break;
                    case 'array':
                        $defSchema = ArraySchema::fromArray(
                            array_merge(['name' => $defName], $defDefinition)
                        );
                        break;
                    case 'object':
                        $defSchema = ObjectSchema::fromArray(
                            array_merge(['name' => $defName], $defDefinition)
                        );
                        break;
                    case 'boolean':
                        $defSchema = BooleanSchema::fromArray(
                            array_merge(['name' => $defName], $defDefinition)
                        );
                        break;
                    case 'null':
                        $defSchema = NullSchema::fromArray(
                            array_merge(['name' => $defName], $defDefinition)
                        );
                        break;
                    default:
                        // Default to string for unknown types
                        $defSchema = StringSchema::fromArray(
                            array_merge(['name' => $defName], $defDefinition)
                        );
                }

                $definitions[$defName] = $defSchema;
            }
            $schema->setDefinitions($definitions);
        }

        // Handle legacy "definitions" field.
        if (isset($definition['definitions']) && is_array($definition['definitions'])) {
            // Get any existing definitions.
            $definitions = $schema->getDefinitions();
            foreach ($definition['definitions'] as $defName => $defDefinition) {
                // Process same as $defs.
                $type = $defDefinition['type'] ?? 'string';

                $defSchema = null;
                switch ($type) {
                    case 'string':
                        $defSchema = StringSchema::fromArray(
                            array_merge(['name' => $defName], $defDefinition)
                        );
                        break;
                    case 'number':
                        $defSchema = NumberSchema::fromArray(
                            array_merge(['name' => $defName], $defDefinition)
                        );
                        break;
                    case 'integer':
                        $defSchema = IntegerSchema::fromArray(
                            array_merge(['name' => $defName], $defDefinition)
                        );
                        break;
                    case 'array':
                        $defSchema = ArraySchema::fromArray(
                            array_merge(['name' => $defName], $defDefinition)
                        );
                        break;
                    case 'object':
                        $defSchema = ObjectSchema::fromArray(
                            array_merge(['name' => $defName], $defDefinition)
                        );
                        break;
                    case 'boolean':
                        $defSchema = BooleanSchema::fromArray(
                            array_merge(['name' => $defName], $defDefinition)
                        );
                        break;
                    case 'null':
                        $defSchema = NullSchema::fromArray(
                            array_merge(['name' => $defName], $defDefinition)
                        );
                        break;
                    default:
                        $defSchema = StringSchema::fromArray(
                            array_merge(['name' => $defName], $defDefinition)
                        );
                }

                $definitions[$defName] = $defSchema;
            }
            $schema->setDefinitions($definitions);
        }

        return $schema;
    }
}
