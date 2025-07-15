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

use Derafu\Form\Contract\FormFieldInterface;
use Derafu\Form\Contract\FormInterface;
use Derafu\Form\Contract\Schema\PropertySchemaInterface;
use Derafu\Form\Contract\UiSchema\ControlInterface;
use Derafu\Form\Rules\SchemaToRulesMapper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SchemaToRulesMapper::class)]
final class SchemaToRulesMapperAdvancedTest extends TestCase
{
    private SchemaToRulesMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new SchemaToRulesMapper();
    }

    public function testMapFieldToRulesWithEditorControl(): void
    {
        // Create mock property schema.
        $propertySchema = $this->createMock(PropertySchemaInterface::class);
        $propertySchema->method('toArray')
            ->willReturn([
                'type' => 'string',
                'title' => 'Content',
            ]);

        // Create mock control with editor type.
        $control = $this->createMock(ControlInterface::class);
        $control->method('getOptions')
            ->willReturn(['type' => 'editor']);

        // Create mock field.
        $field = $this->createMock(FormFieldInterface::class);
        $field->method('getProperty')
            ->willReturn($propertySchema);
        $field->method('getControl')
            ->willReturn($control);

        $rules = $this->mapper->mapFieldToRules($field);

        $this->assertArrayHasKey('cast', $rules);
        $this->assertArrayHasKey('sanitize', $rules);
        $this->assertArrayHasKey('transform', $rules);
        $this->assertContains('strip_tags', $rules['transform']);
    }

    public function testMapFieldToRulesWithFileControl(): void
    {
        // Create mock property schema.
        $propertySchema = $this->createMock(PropertySchemaInterface::class);
        $propertySchema->method('toArray')
            ->willReturn([
                'type' => 'string',
                'title' => 'Upload',
            ]);

        // Create mock control with file type.
        $control = $this->createMock(ControlInterface::class);
        $control->method('getOptions')
            ->willReturn(['type' => 'file']);

        // Create mock field.
        $field = $this->createMock(FormFieldInterface::class);
        $field->method('getProperty')
            ->willReturn($propertySchema);
        $field->method('getControl')
            ->willReturn($control);

        $rules = $this->mapper->mapFieldToRules($field);

        $this->assertArrayHasKey('validate', $rules);
        $this->assertContains('file', $rules['validate']);
    }

    public function testMapFieldToRulesWithImageControl(): void
    {
        // Create mock property schema.
        $propertySchema = $this->createMock(PropertySchemaInterface::class);
        $propertySchema->method('toArray')
            ->willReturn([
                'type' => 'string',
                'title' => 'Image',
            ]);

        // Create mock control with image type.
        $control = $this->createMock(ControlInterface::class);
        $control->method('getOptions')
            ->willReturn(['type' => 'image']);

        // Create mock field.
        $field = $this->createMock(FormFieldInterface::class);
        $field->method('getProperty')
            ->willReturn($propertySchema);
        $field->method('getControl')
            ->willReturn($control);

        $rules = $this->mapper->mapFieldToRules($field);

        $this->assertArrayHasKey('validate', $rules);
        $this->assertContains('image', $rules['validate']);
    }

    public function testMapFormToFieldRules(): void
    {
        // Create mock fields.
        $field1 = $this->createMock(FormFieldInterface::class);
        $field1->method('getProperty')
            ->willReturn($this->createMock(PropertySchemaInterface::class));
        $field1->method('getControl')
            ->willReturn($this->createMock(ControlInterface::class));

        $field2 = $this->createMock(FormFieldInterface::class);
        $field2->method('getProperty')
            ->willReturn($this->createMock(PropertySchemaInterface::class));
        $field2->method('getControl')
            ->willReturn($this->createMock(ControlInterface::class));

        // Create mock form.
        $form = $this->createMock(FormInterface::class);
        $form->method('getFields')
            ->willReturn([
                'name' => $field1,
                'email' => $field2,
            ]);

        $fieldRules = $this->mapper->mapFormToRules($form);

        $this->assertArrayHasKey('name', $fieldRules);
        $this->assertArrayHasKey('email', $fieldRules);
        $this->assertIsArray($fieldRules['name']);
        $this->assertIsArray($fieldRules['email']);
    }

    public function testMapFieldToRulesCombinesSchemaAndUIControls(): void
    {
        // Create mock property schema with email format.
        $propertySchema = $this->createMock(PropertySchemaInterface::class);
        $propertySchema->method('toArray')
            ->willReturn([
                'type' => 'string',
                'format' => 'email',
                'minLength' => 3,
                'maxLength' => 80,
            ]);

        // Create mock control with editor type.
        $control = $this->createMock(ControlInterface::class);
        $control->method('getOptions')
            ->willReturn(['type' => 'editor']);

        // Create mock field.
        $field = $this->createMock(FormFieldInterface::class);
        $field->method('getProperty')
            ->willReturn($propertySchema);
        $field->method('getControl')
            ->willReturn($control);

        $rules = $this->mapper->mapFieldToRules($field);

        // Should have schema-based rules.
        $this->assertArrayHasKey('cast', $rules);
        $this->assertArrayHasKey('sanitize', $rules);
        $this->assertArrayHasKey('transform', $rules);
        $this->assertArrayHasKey('validate', $rules);

        // Should have email-specific rules from schema.
        $this->assertContains('trim', $rules['sanitize']);
        $this->assertContains('lowercase', $rules['transform']);
        $this->assertContains('email', $rules['validate']);
        $this->assertContains('min_length:3', $rules['validate']);
        $this->assertContains('max_length:80', $rules['validate']);

        // Should have editor-specific rules from UI control.
        $this->assertContains('strip_tags', $rules['transform']);
    }
}
