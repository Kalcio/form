<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.dev>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Factory;

use Derafu\Form\Contract\Factory\FormFactoryInterface;
use Derafu\Form\Contract\FormInterface;
use Derafu\Form\Contract\Type\TypeResolverInterface;
use Derafu\Form\Form;
use InvalidArgumentException;

/**
 * Factory class for creating form instances with automatic schema generation.
 *
 * This factory provides methods to create forms from an array definition.
 *
 * It can automatically detecting types, formats, and validation rules based on
 * the provided values in the `data` index of the definition.
 */
final class FormFactory implements FormFactoryInterface
{
    /**
     * Constructor.
     *
     * @param TypeResolverInterface $typeResolver The type resolver.
     */
    public function __construct(private readonly TypeResolverInterface $typeResolver)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $definition): FormInterface
    {
        $definition = $this->normalizeDefinition($definition);

        return Form::fromArray($definition);
    }

    /**
     * Normalizes the form definition, ensuring all required components are
     * present.
     *
     * If `schema` or `uischema` are missing, they will be automatically
     * generated based on the provided data.
     *
     * @param array $definition The form definition to normalize.
     * @return array The normalized definition with schema and uischema.
     * @throws InvalidArgumentException If neither `schema` nor `data` are provided.
     */
    private function normalizeDefinition(array $definition): array
    {
        if (empty($definition['data'])) {
            $definition['data'] = null;
        }

        if (empty($definition['schema'])) {
            if ($definition['data'] === null) {
                throw new InvalidArgumentException(
                    'The form schema definition must be assigned if form data is not provided.'
                );
            }

            $definition['schema'] = $this->createSchemaDefinitionFromData(
                $definition['data']
            );
        }

        if (empty($definition['uischema'])) {
            $definition['uischema'] = $this->createUiSchemaDefinitionFromProperties(
                $definition['schema']['properties']
            );
        }

        if (is_array($definition['data'])) {
            foreach ($definition['data'] as $name => &$value) {
                if ($value !== null) {
                    continue;
                }
                if (($definition['schema']['properties'][$name]['type'] ?? 'string') === 'string') {
                    $value = '';
                }
            }
        }

        return $definition;
    }

    /**
     * Creates a schema definition based on the provided data.
     *
     * Automatically detects types, formats, and validation rules based on the
     * values in the data.
     *
     * @param array $data The data to create a schema from
     * @return array The generated schema definition
     */
    private function createSchemaDefinitionFromData(array $data): array
    {
        $schema = [
            'type' => 'object',
            'properties' => [],
            'required' => [],
        ];

        foreach ($data as $name => $value) {
            $type = $this->typeResolver->guess($value);

            $property = $type->getJsonSchema();

            $schema['properties'][$name] = $property;
        }

        return $schema;
    }

    /**
     * Creates a UI schema definition based on the provided properties.
     *
     * @param array<string,array<string,mixed>> $properties The properties to
     * create a UI schema from.
     * @return array The generated UI schema definition.
     */
    private function createUiSchemaDefinitionFromProperties(
        array $properties
    ): array {
        $uischema = [
            'type' => 'VerticalLayout',
            'elements' => [],
        ];

        foreach ($properties as $name => $property) {
            $uischema['elements'][] = [
                'type' => 'Control',
                'label' => $property['title']
                    ?? implode(' ', array_map('ucfirst', explode('_', $name)))
                ,
                'scope' => '#/properties/' . $name,
            ];
        }

        return $uischema;
    }
}
