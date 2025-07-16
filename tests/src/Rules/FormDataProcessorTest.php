<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.dev>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Tests\Rules;

use Derafu\DataProcessor\Contract\ProcessorInterface;
use Derafu\DataProcessor\ProcessorFactory;
use Derafu\Form\Contract\FormFieldInterface;
use Derafu\Form\Contract\FormInterface;
use Derafu\Form\Contract\Rules\FormDataProcessorInterface;
use Derafu\Form\Contract\Schema\PropertySchemaInterface;
use Derafu\Form\Contract\UiSchema\ControlInterface;
use Derafu\Form\Rules\FormDataProcessor;
use Derafu\Form\Rules\ProcessResult;
use Derafu\Form\Rules\SchemaToRulesMapper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(\Derafu\Form\Rules\FormDataProcessor::class)]
#[CoversClass(\Derafu\Form\Rules\ProcessResult::class)]
#[CoversClass(\Derafu\Form\Exception\ValidationException::class)]
#[CoversClass(\Derafu\Form\Rules\SchemaToRulesMapper::class)]
#[CoversClass(\Derafu\Form\Data\FormData::class)]
final class FormDataProcessorTest extends TestCase
{
    private FormDataProcessorInterface $processor;

    private SchemaToRulesMapper $mapper;

    private ProcessorInterface $dataProcessor;

    protected function setUp(): void
    {
        $this->mapper = new SchemaToRulesMapper();
        $this->dataProcessor = (new ProcessorFactory())->create();
        $this->processor = new FormDataProcessor(
            $this->mapper,
            $this->dataProcessor
        );
    }

    public function testProcessValidData(): void
    {
        $form = $this->createFormWithFields([
            'name' => ['type' => 'string', 'required' => true],
            'email' => ['type' => 'string', 'format' => 'email', 'required' => true],
        ]);

        $data = ['name' => 'John Doe', 'email' => 'john@example.com'];

        $result = $this->processor->process($form, $data);

        $this->assertInstanceOf(ProcessResult::class, $result);
        $this->assertTrue($result->isValid());
        $this->assertEmpty($result->getErrors());

        $processedData = $result->getProcessedData();
        $this->assertSame('John Doe', $processedData['name']);
        $this->assertSame('john@example.com', $processedData['email']);
    }

    public function testProcessDataWithValidationErrors(): void
    {
        $form = $this->createFormWithFields([
            'name' => ['type' => 'string', 'required' => true],
            'email' => ['type' => 'string', 'format' => 'email', 'required' => true],
        ]);

        $data = ['name' => '', 'email' => 'invalid-email'];

        $result = $this->processor->process($form, $data);

        $this->assertInstanceOf(ProcessResult::class, $result);
        $this->assertFalse($result->isValid());
        $this->assertTrue($result->hasErrors());

        $errors = $result->getErrors();
        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('email', $errors);
        $this->assertNotEmpty($errors['name']);
        $this->assertNotEmpty($errors['email']);

        // Should keep original values for invalid fields.
        $processedData = $result->getProcessedData();
        $this->assertSame('', $processedData['name']);
        $this->assertSame('invalid-email', $processedData['email']);
    }

    public function testProcessDataWithPartialErrors(): void
    {
        $form = $this->createFormWithFields([
            'name' => ['type' => 'string', 'required' => true],
            'email' => ['type' => 'string', 'format' => 'email', 'required' => true],
        ]);

        $data = ['name' => 'John Doe', 'email' => 'invalid-email'];

        $result = $this->processor->process($form, $data);

        $this->assertInstanceOf(ProcessResult::class, $result);
        $this->assertFalse($result->isValid());
        $this->assertTrue($result->hasErrors());

        $errors = $result->getErrors();
        $this->assertArrayHasKey('email', $errors);
        $this->assertArrayNotHasKey('name', $errors);

        $processedData = $result->getProcessedData();
        $this->assertSame('John Doe', $processedData['name']);
        $this->assertSame('invalid-email', $processedData['email']);
    }

    public function testProcessDataWithExtraFields(): void
    {
        $form = $this->createFormWithFields([
            'name' => ['type' => 'string', 'required' => true],
        ]);

        $data = ['name' => 'John Doe', 'extra_field' => 'extra value'];

        $result = $this->processor->process($form, $data);

        $this->assertInstanceOf(ProcessResult::class, $result);
        $this->assertTrue($result->isValid());

        $processedData = $result->getProcessedData();
        $this->assertArrayHasKey('name', $processedData);
        $this->assertArrayHasKey('extra_field', $processedData);
        $this->assertSame('John Doe', $processedData['name']);
        $this->assertSame('extra value', $processedData['extra_field']);
    }

    public function testProcessDataWithProcessingError(): void
    {
        $form = $this->createFormWithFields([
            'name' => ['type' => 'string', 'required' => true],
        ]);

        $data = ['name' => 'John Doe'];

        $result = $this->processor->process($form, $data);

        $this->assertInstanceOf(ProcessResult::class, $result);
        $this->assertTrue($result->isValid());
        $this->assertFalse($result->hasErrors());

        $processedData = $result->getProcessedData();
        $this->assertSame('John Doe', $processedData['name']);
    }

    public function testGetFormReturnsFormWithProcessedData(): void
    {
        $form = $this->createFormWithFields([
            'name' => ['type' => 'string', 'required' => true],
            'email' => ['type' => 'string', 'format' => 'email', 'required' => true],
        ]);

        $data = ['name' => 'John Doe', 'email' => 'john@example.com'];

        $result = $this->processor->process($form, $data);

        $this->assertInstanceOf(ProcessResult::class, $result);
        $this->assertTrue($result->isValid());

        $formWithData = $result->getForm();
        $this->assertInstanceOf(FormInterface::class, $formWithData);
        $this->assertNotSame($form, $formWithData); // Should be a new instance.
    }

    public function testProcessDataPreservesInvalidData(): void
    {
        $form = $this->createFormWithFields([
            'name' => ['type' => 'string', 'minLength' => 3, 'required' => true],
            'email' => ['type' => 'string', 'format' => 'email', 'required' => true],
            'age' => ['type' => 'integer', 'minimum' => 18, 'required' => true],
        ]);

        $invalidData = [
            'name' => 'Jo', // Too short
            'email' => 'invalid-email', // Invalid format
            'age' => 'not-a-number', // Invalid type
        ];

        $result = $this->processor->process($form, $invalidData);

        $this->assertInstanceOf(ProcessResult::class, $result);
        $this->assertFalse($result->isValid());
        $this->assertTrue($result->hasErrors());

        // Verify that invalid data is preserved in processed data.
        $processedData = $result->getProcessedData();
        $this->assertSame('Jo', $processedData['name']);
        $this->assertSame('invalid-email', $processedData['email']);
        $this->assertSame('not-a-number', $processedData['age']);

        // Verify that the form contains the invalid data.
        $formWithData = $result->getForm();
        $this->assertInstanceOf(FormInterface::class, $formWithData);
    }

    public function testProcessDataPreservesMixedValidAndInvalidData(): void
    {
        $form = $this->createFormWithFields([
            'name' => ['type' => 'string', 'minLength' => 3, 'required' => true],
            'email' => ['type' => 'string', 'format' => 'email', 'required' => true],
            'age' => ['type' => 'integer', 'minimum' => 18, 'required' => true],
            'phone' => ['type' => 'string', 'minLength' => 10, 'required' => true],
        ]);

        $mixedData = [
            'name' => 'John Doe', // Valid
            'email' => 'invalid-email', // Invalid
            'age' => 25, // Valid
            'phone' => '123', // Invalid (too short)
        ];

        $result = $this->processor->process($form, $mixedData);

        $this->assertInstanceOf(ProcessResult::class, $result);
        $this->assertFalse($result->isValid());
        $this->assertTrue($result->hasErrors());

        // Verify that all data is preserved, both valid and invalid.
        $processedData = $result->getProcessedData();
        $this->assertSame('John Doe', $processedData['name']);
        $this->assertSame('invalid-email', $processedData['email']);
        $this->assertSame(25, $processedData['age']);
        $this->assertSame('123', $processedData['phone']);

        // Verify that the form contains all the data.
        $formWithData = $result->getForm();
        $this->assertInstanceOf(FormInterface::class, $formWithData);
    }

    public function testProcessDataPreservesEmptyAndNullValues(): void
    {
        $form = $this->createFormWithFields([
            'name' => ['type' => 'string', 'required' => true],
            'email' => ['type' => 'string', 'format' => 'email', 'required' => true],
            'age' => ['type' => 'integer', 'minimum' => 18, 'required' => true],
            'active' => ['type' => 'boolean', 'required' => true],
        ]);

        $dataWithEmptyValues = [
            'name' => '', // Empty string
            'email' => null, // Null value
            'age' => 0, // Zero value
            'active' => false, // Boolean false
        ];

        $result = $this->processor->process($form, $dataWithEmptyValues);

        $this->assertInstanceOf(ProcessResult::class, $result);
        $this->assertFalse($result->isValid());
        $this->assertTrue($result->hasErrors());

        // Verify that empty and null values are preserved.
        $processedData = $result->getProcessedData();
        $this->assertSame('', $processedData['name']);
        $this->assertNull($processedData['email']);
        $this->assertSame(0, $processedData['age']);
        $this->assertFalse($processedData['active']);

        // Verify that the form contains all the data.
        $formWithData = $result->getForm();
        $this->assertInstanceOf(FormInterface::class, $formWithData);
    }

    /**
     * Create a form with specific field definitions for testing.
     */
    private function createFormWithFields(array $fieldDefinitions): FormInterface
    {
        $form = $this->createMock(FormInterface::class);

        $fields = [];
        foreach ($fieldDefinitions as $fieldName => $schema) {
            $fields[$fieldName] = $this->createMockField($fieldName, $schema);
        }

        $form->method('getFields')->willReturn($fields);

        return $form;
    }

    /**
     * Create a mock field for testing.
     */
    private function createMockField(string $name, array $schema): FormFieldInterface
    {
        $field = $this->createMock(FormFieldInterface::class);

        $property = $this->createMock(PropertySchemaInterface::class);
        $property->method('toArray')->willReturn($schema);

        $control = $this->createMock(ControlInterface::class);
        $control->method('getOptions')->willReturn([]);

        $field->method('getProperty')->willReturn($property);
        $field->method('getControl')->willReturn($control);

        return $field;
    }
}
