<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Factory;

use DateTimeInterface;
use Derafu\Form\Contract\Factory\FormFactoryInterface;
use Derafu\Form\Contract\FormInterface;
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
     * {@inheritDoc}
     */
    public static function create(array $definition): FormInterface
    {
        $definition = self::normalizeDefinition($definition);

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
    private static function normalizeDefinition(array $definition): array
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

            $definition['schema'] = self::createSchemaDefinitionFromData(
                $definition['data']
            );
        }

        if (empty($definition['uischema'])) {
            $definition['uischema'] = self::createUiSchemaDefinitionFromProperties(
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
    private static function createSchemaDefinitionFromData(array $data): array
    {
        $schema = [
            'type' => 'object',
            'properties' => [],
            'required' => [],
        ];

        foreach ($data as $name => $value) {
            [$type, $format, $rules] = self::resolvePropertyFromValue($value);

            $property = [
                'type' => $type,
            ];

            if ($format !== null) {
                $property['format'] = $format;
            }

            foreach ($rules as $ruleName => $ruleValue) {
                $property[$ruleName] = $ruleValue;
            }

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
    private static function createUiSchemaDefinitionFromProperties(
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

    /**
     * Resolves property type, format, and validation rules from a value.
     *
     * @param mixed $value The value to analyze.
     * @return array Array containing [type, format, rules].
     */
    private static function resolvePropertyFromValue(mixed $value): array
    {
        $type = self::getJsonSchemaType($value);
        $format = self::getJsonSchemaFormat($value, $type);
        $rules = self::getJsonSchemaRules($type, $format);

        return [$type, $format, $rules];
    }

    /**
     * Determines the JSON Schema type for a given value.
     *
     * @param mixed $value The value to analyze.
     * @return string The JSON Schema type.
     */
    private static function getJsonSchemaType(mixed $value): string
    {
        // Handle large integer strings that exceed PHP's integer range.
        if (is_string($value) && preg_match('/^-?\d+$/', $value)) {
            // Check if string represents a number too large for PHP integer.
            if (
                bccomp($value, (string)PHP_INT_MAX, 0) > 0
                || bccomp($value, (string)PHP_INT_MIN, 0) < 0
            ) {
                return 'string';
            }

            return 'integer';
        }

        if ($value instanceof DateTimeInterface) {
            return 'string';
        }

        return match (true) {
            is_string($value) => 'string',
            is_int($value) => 'integer',
            is_float($value) => 'number',
            is_bool($value) => 'boolean',
            is_array($value) => 'array',
            is_object($value) => 'object',
            default => 'string'
        };
    }

    /**
     * Determines the JSON Schema format for a given value and type.
     *
     * @param mixed $value The value to analyze.
     * @param string $type The JSON Schema type of the value.
     * @return string|null The JSON Schema format or `null` if no specific format applies.
     */
    private static function getJsonSchemaFormat(mixed $value, string $type): ?string
    {
        if ($type !== 'string' || $value === null) {
            return null;
        }

        if ($value instanceof DateTimeInterface) {
            // Check if the time component is midnight (00:00:00).
            if ($value->format('H:i:s') === '00:00:00') {
                return 'date';
            }

            return 'date-time';
        }

        if (is_object($value)) {
            return null;
        }

        return match (true) {
            filter_var($value, FILTER_VALIDATE_EMAIL) !== false => 'email',
            filter_var($value, FILTER_VALIDATE_URL) !== false => 'uri',
            filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false => 'ipv4',
            filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false => 'ipv6',
            preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) === 1 => 'date',
            preg_match('/^\d{2}:\d{2}:\d{2}(\.\d+)?(Z|[+-]\d{2}:\d{2})?$/', $value) === 1 => 'time',
            preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(\.\d+)?(Z|[+-]\d{2}:\d{2})?$/', $value) === 1 => 'date-time',
            preg_match('/^P(?:\d+Y)?(?:\d+M)?(?:\d+D)?(?:T(?:\d+H)?(?:\d+M)?(?:\d+S)?)?$/', $value) === 1 => 'duration',
            preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $value) === 1 => 'uuid',
            default => null
        };
    }

    /**
     * Determines validation rules for a JSON Schema property based on type,
     * format, and value.
     *
     * @param string $type The JSON Schema type.
     * @param string|null $format The JSON Schema format, if applicable.
     * @return array Validation rules appropriate for the property.
     */
    private static function getJsonSchemaRules(string $type, ?string $format): array
    {
        return match ($type) {
            'string' => match ($format) {
                // RFC 5321.
                'email' => [
                    'maxLength' => 320,
                ],

                'uri' => [
                    'maxLength' => 2048,
                    'minLength' => 2,
                ],

                // RFC 4122.
                'uuid' => [
                    'maxLength' => 36,
                    'minLength' => 36,
                ],

                // "255.255.255.255" (15), "0.0.0.0" (7).
                'ipv4' => [
                    'maxLength' => 15,
                    'minLength' => 7,
                ],

                // RFC 4291.
                'ipv6' => [
                    'maxLength' => 39,
                    'minLength' => 2,
                ],

                // RFC 1035.
                'hostname' => [
                    'maxLength' => 253,
                ],

                // ISO 8601.
                'date' => [
                    'maxLength' => 10,
                    'minLength' => 10,
                ],

                // hh:mm:ss
                'time' => [
                    'maxLength' => 14,
                    'minLength' => 8,
                ],

                // ISO 8601
                'date-time' => [
                    'maxLength' => 35,
                    'minLength' => 16,
                ],

                default => [] // No restrictions for others string formats.
            },

            default => [] // No restrictions for others types.
        };
    }
}
