<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.dev>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Tests\Form\Rules;

use Derafu\Form\Rules\SchemaToRulesMapper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(SchemaToRulesMapper::class)]
final class SchemaToRulesMapperTest extends TestCase
{
    private SchemaToRulesMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new SchemaToRulesMapper();
    }

    #[DataProvider('stringTypeProvider')]
    public function testStringTypeMapping(array $schema, array $expectedRules): void
    {
        $result = $this->mapper->mapSchemaToRules($schema);
        $this->assertSame($expectedRules, $result);
    }

    public static function stringTypeProvider(): array
    {
        return [
            'basic_string' => [
                ['type' => 'string'],
                [
                    'cast' => 'string',
                    'sanitize' => ['trim'],
                ],
            ],
            'string_with_length_constraints' => [
                [
                    'type' => 'string',
                    'minLength' => 3,
                    'maxLength' => 100,
                ],
                [
                    'cast' => 'string',
                    'sanitize' => ['trim'],
                    'validate' => ['min_length:3', 'max_length:100'],
                ],
            ],
            'email_string' => [
                [
                    'type' => 'string',
                    'format' => 'email',
                    'minLength' => 3,
                    'maxLength' => 80,
                ],
                [
                    'cast' => 'string',
                    'sanitize' => ['trim'],
                    'transform' => ['lowercase'],
                    'validate' => ['min_length:3', 'max_length:80', 'email'],
                ],
            ],
            'required_string' => [
                [
                    'type' => 'string',
                    'required' => true,
                ],
                [
                    'cast' => 'string',
                    'sanitize' => ['trim'],
                    'validate' => ['required'],
                ],
            ],
            'string_with_pattern' => [
                [
                    'type' => 'string',
                    'pattern' => '^[A-Za-z]+$',
                ],
                [
                    'cast' => 'string',
                    'sanitize' => ['trim'],
                    'validate' => ['regex:^[A-Za-z]+$'],
                ],
            ],
            'string_with_enum' => [
                [
                    'type' => 'string',
                    'enum' => ['pending', 'approved', 'rejected'],
                ],
                [
                    'cast' => 'string',
                    'sanitize' => ['trim'],
                    'transform' => ['lowercase'],
                    'validate' => ['in:pending,approved,rejected'],
                ],
            ],
        ];
    }

    #[DataProvider('numericTypeProvider')]
    public function testNumericTypeMapping(array $schema, array $expectedRules): void
    {
        $result = $this->mapper->mapSchemaToRules($schema);
        $this->assertSame($expectedRules, $result);
    }

    public static function numericTypeProvider(): array
    {
        return [
            'integer_type' => [
                ['type' => 'integer'],
                ['cast' => 'integer'],
            ],
            'integer_with_range' => [
                [
                    'type' => 'integer',
                    'minimum' => 0,
                    'maximum' => 100,
                ],
                [
                    'cast' => 'integer',
                    'validate' => ['gte:0', 'lte:100'],
                ],
            ],
            'number_type' => [
                ['type' => 'number'],
                ['cast' => 'float'],
            ],
            'number_with_range' => [
                [
                    'type' => 'number',
                    'minimum' => 0.0,
                    'maximum' => 100.0,
                ],
                [
                    'cast' => 'float',
                    'validate' => ['gte:0', 'lte:100'],
                ],
            ],
        ];
    }

    #[DataProvider('arrayTypeProvider')]
    public function testArrayTypeMapping(array $schema, array $expectedRules): void
    {
        $result = $this->mapper->mapSchemaToRules($schema);
        $this->assertSame($expectedRules, $result);
    }

    public static function arrayTypeProvider(): array
    {
        return [
            'array_type' => [
                ['type' => 'array'],
                [],
            ],
            'array_with_size_constraints' => [
                [
                    'type' => 'array',
                    'minItems' => 1,
                    'maxItems' => 10,
                ],
                [
                    'validate' => ['min_items:1', 'max_items:10'],
                ],
            ],
            'array_with_unique_items' => [
                [
                    'type' => 'array',
                    'uniqueItems' => true,
                ],
                [
                    'validate' => ['unique'],
                ],
            ],
        ];
    }

    #[DataProvider('booleanTypeProvider')]
    public function testBooleanTypeMapping(array $schema, array $expectedRules): void
    {
        $result = $this->mapper->mapSchemaToRules($schema);
        $this->assertSame($expectedRules, $result);
    }

    public static function booleanTypeProvider(): array
    {
        return [
            'boolean_type' => [
                ['type' => 'boolean'],
                ['cast' => 'boolean'],
            ],
        ];
    }

    #[DataProvider('formatMappingProvider')]
    public function testFormatMapping(array $schema, array $expectedRules): void
    {
        $result = $this->mapper->mapSchemaToRules($schema);
        $this->assertSame($expectedRules, $result);
    }

    public static function formatMappingProvider(): array
    {
        return [
            'email_format' => [
                [
                    'type' => 'string',
                    'format' => 'email',
                ],
                [
                    'cast' => 'string',
                    'sanitize' => ['trim'],
                    'transform' => ['lowercase'],
                    'validate' => ['email'],
                ],
            ],
            'uri_format' => [
                [
                    'type' => 'string',
                    'format' => 'uri',
                ],
                [
                    'cast' => 'string',
                    'sanitize' => ['trim'],
                    'validate' => ['url'],
                ],
            ],
            'date_format' => [
                [
                    'type' => 'string',
                    'format' => 'date',
                ],
                [
                    'cast' => 'string',
                    'sanitize' => ['trim'],
                    'validate' => ['date'],
                ],
            ],
            'date_time_format' => [
                [
                    'type' => 'string',
                    'format' => 'date-time',
                ],
                [
                    'cast' => 'string',
                    'sanitize' => ['trim'],
                    'validate' => ['datetime'],
                ],
            ],
            'tel_format' => [
                [
                    'type' => 'string',
                    'format' => 'tel',
                ],
                [
                    'cast' => 'string',
                    'sanitize' => ['trim'],
                    'validate' => ['phone'],
                ],
            ],
        ];
    }

    public function testEmptySchemaReturnsEmptyRules(): void
    {
        $result = $this->mapper->mapSchemaToRules([]);
        $this->assertSame([], $result);
    }

    public function testSchemaWithoutTypeReturnsEmptyRules(): void
    {
        $result = $this->mapper->mapSchemaToRules(['title' => 'Test Field']);
        $this->assertSame([], $result);
    }
}
