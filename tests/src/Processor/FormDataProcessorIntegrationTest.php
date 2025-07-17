<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.dev>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Tests\Processor;

use Derafu\DataProcessor\Contract\ProcessorInterface;
use Derafu\DataProcessor\ProcessorFactory;
use Derafu\Form\Contract\FormFieldInterface;
use Derafu\Form\Contract\FormInterface;
use Derafu\Form\Contract\Processor\FormDataProcessorInterface;
use Derafu\Form\Contract\Processor\ProcessResultInterface;
use Derafu\Form\Contract\Schema\PropertySchemaInterface;
use Derafu\Form\Contract\UiSchema\ControlInterface;
use Derafu\Form\Processor\FormDataProcessor;
use Derafu\Form\Processor\SchemaToRulesMapper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests for FormDataProcessor.
 */
#[CoversClass(\Derafu\Form\Processor\FormDataProcessor::class)]
#[CoversClass(\Derafu\Form\Processor\ProcessResult::class)]
#[CoversClass(\Derafu\Form\Processor\SchemaToRulesMapper::class)]
final class FormDataProcessorIntegrationTest extends TestCase
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

    public function testProcessValidFormData(): void
    {
        // Create a simple form definition.
        $form = $this->createSimpleForm();
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'age' => '25',
        ];

        $result = $this->processor->process($form, $data);

        $this->assertInstanceOf(ProcessResultInterface::class, $result);
        $this->assertTrue($result->isValid());
        $this->assertEmpty($result->getErrors());

        $processedData = $result->getProcessedData();
        $this->assertSame('John Doe', $processedData['name']);
        $this->assertSame('john@example.com', $processedData['email']);
        $this->assertSame(25, $processedData['age']); // Should be cast to integer.
    }

    public function testProcessInvalidFormData(): void
    {
        $form = $this->createSimpleForm();
        $data = [
            'name' => '', // Empty required field.
            'email' => 'invalid-email', // Invalid email format.
            'age' => 'not-a-number', // Invalid number.
        ];

        $result = $this->processor->process($form, $data);

        $this->assertInstanceOf(ProcessResultInterface::class, $result);
        $this->assertFalse($result->isValid());
        $this->assertTrue($result->hasErrors());

        $errors = $result->getErrors();
        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('email', $errors);
        $this->assertArrayHasKey('age', $errors);
    }

    public function testProcessDataWithExtraFields(): void
    {
        $form = $this->createSimpleForm();
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'age' => '25',
            'extra_field' => 'extra value', // Field not in schema.
        ];

        $result = $this->processor->process($form, $data);

        $this->assertTrue($result->isValid());
        $processedData = $result->getProcessedData();
        $this->assertArrayHasKey('extra_field', $processedData);
        $this->assertSame('extra value', $processedData['extra_field']);
    }

    /**
     * Create a simple form for testing.
     */
    private function createSimpleForm(): FormInterface
    {
        // This is a simplified form creation for testing purposes.
        // In a real scenario, you would use the FormFactory or create a proper
        // Form instance.
        $form = $this->createMock(FormInterface::class);

        // Mock the getFields method to return a simple field structure.
        $form->method('getFields')->willReturn([
            'name' => $this->createMockField('name', 'string', true),
            'email' => $this->createMockField('email', 'string', true, 'email'),
            'age' => $this->createMockField('age', 'integer', false),
        ]);

        return $form;
    }

    /**
     * Create a mock field for testing.
     */
    private function createMockField(
        string $name,
        string $type,
        bool $required,
        ?string $format = null
    ): FormFieldInterface {
        $field = $this->createMock(FormFieldInterface::class);

        $propertySchema = [
            'type' => $type,
            'required' => $required,
        ];

        if ($format) {
            $propertySchema['format'] = $format;
        }

        $property = $this->createMock(PropertySchemaInterface::class);
        $property->method('toArray')->willReturn($propertySchema);

        $control = $this->createMock(ControlInterface::class);
        $control->method('getOptions')->willReturn([]);

        $field->method('getProperty')->willReturn($property);
        $field->method('getControl')->willReturn($control);

        return $field;
    }
}
