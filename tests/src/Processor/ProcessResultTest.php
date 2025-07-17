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

use Derafu\Form\Contract\FormInterface;
use Derafu\Form\Data\FormData;
use Derafu\Form\Processor\ProcessResult;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Tests for ProcessResult.
 */
#[CoversClass(\Derafu\Form\Processor\ProcessResult::class)]
#[CoversClass(\Derafu\Form\Data\FormData::class)]
final class ProcessResultTest extends TestCase
{
    /** @var FormInterface&\PHPUnit\Framework\MockObject\MockObject */
    private FormInterface $mockForm;

    protected function setUp(): void
    {
        $this->mockForm = $this->createMock(FormInterface::class);
    }

    public function testValidResult(): void
    {
        $data = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $result = new ProcessResult($this->mockForm, $data);

        $this->assertSame($data, $result->getProcessedData());
        $this->assertEmpty($result->getErrors());
        $this->assertTrue($result->isValid());
        $this->assertFalse($result->hasErrors());
        $this->assertEmpty($result->getAllErrors());
    }

    public function testInvalidResultWithErrors(): void
    {
        $data = ['name' => 'John Doe', 'email' => 'invalid-email'];
        $errors = [
            'email' => ['Invalid email format'],
            'name' => ['Name is too short'],
        ];
        $result = new ProcessResult($this->mockForm, $data, $errors, false);

        $this->assertSame($data, $result->getProcessedData());
        $this->assertSame($errors, $result->getErrors());
        $this->assertFalse($result->isValid());
        $this->assertTrue($result->hasErrors());
        $this->assertSame(['Invalid email format', 'Name is too short'], $result->getAllErrors());
    }

    public function testGetFieldErrors(): void
    {
        $data = ['name' => 'John Doe', 'email' => 'invalid-email'];
        $errors = [
            'email' => ['Invalid email format'],
            'name' => ['Name is too short'],
        ];
        $result = new ProcessResult($this->mockForm, $data, $errors, false);

        $this->assertSame(['Invalid email format'], $result->getFieldErrors('email'));
        $this->assertSame(['Name is too short'], $result->getFieldErrors('name'));
        $this->assertEmpty($result->getFieldErrors('non_existent_field'));
    }

    public function testHasFieldErrors(): void
    {
        $data = ['name' => 'John Doe', 'email' => 'invalid-email'];
        $errors = [
            'email' => ['Invalid email format'],
            'name' => [],
        ];
        $result = new ProcessResult($this->mockForm, $data, $errors, false);

        $this->assertTrue($result->hasFieldErrors('email'));
        $this->assertFalse($result->hasFieldErrors('name'));
        $this->assertFalse($result->hasFieldErrors('non_existent_field'));
    }

    public function testEmptyErrorsArray(): void
    {
        $data = ['name' => 'John Doe'];
        $errors = [];
        $result = new ProcessResult($this->mockForm, $data, $errors, true);

        $this->assertSame($data, $result->getProcessedData());
        $this->assertEmpty($result->getErrors());
        $this->assertTrue($result->isValid());
        $this->assertFalse($result->hasErrors());
        $this->assertEmpty($result->getAllErrors());
    }

    public function testMultipleErrorsPerField(): void
    {
        $data = ['email' => 'invalid-email'];
        $errors = [
            'email' => [
                'Invalid email format',
                'Email must be from allowed domain',
            ],
        ];
        $result = new ProcessResult($this->mockForm, $data, $errors, false);

        $this->assertSame($errors, $result->getErrors());
        $this->assertSame(['Invalid email format', 'Email must be from allowed domain'], $result->getFieldErrors('email'));
        $this->assertSame(['Invalid email format', 'Email must be from allowed domain'], $result->getAllErrors());
    }

    public function testComplexDataTypes(): void
    {
        $data = [
            'user' => ['id' => 1, 'name' => 'John'],
            'settings' => ['theme' => 'dark', 'notifications' => true],
            'tags' => ['php', 'testing', 'forms'],
        ];
        $errors = [
            'user' => ['User data is invalid'],
            'settings' => ['Invalid theme value'],
        ];
        $result = new ProcessResult($this->mockForm, $data, $errors, false);

        $this->assertSame($data, $result->getProcessedData());
        $this->assertSame($errors, $result->getErrors());
        $this->assertFalse($result->isValid());
        $this->assertTrue($result->hasErrors());
        $this->assertSame(['User data is invalid', 'Invalid theme value'], $result->getAllErrors());
    }

    public function testNullData(): void
    {
        $result = new ProcessResult($this->mockForm, null, [], true);

        $this->assertNull($result->getProcessedData());
        $this->assertEmpty($result->getErrors());
        $this->assertTrue($result->isValid());
        $this->assertFalse($result->hasErrors());
    }

    public function testEmptyData(): void
    {
        $result = new ProcessResult($this->mockForm, [], [], true);

        $this->assertSame([], $result->getProcessedData());
        $this->assertEmpty($result->getErrors());
        $this->assertTrue($result->isValid());
        $this->assertFalse($result->hasErrors());
    }

    public function testValidResultWithDefaultParameters(): void
    {
        $data = ['name' => 'John Doe'];
        $result = new ProcessResult($this->mockForm, $data);

        $this->assertSame($data, $result->getProcessedData());
        $this->assertEmpty($result->getErrors());
        $this->assertTrue($result->isValid());
    }

    public function testInvalidResultWithDefaultParameters(): void
    {
        $data = ['name' => 'John Doe'];
        $errors = ['name' => ['Name is required']];
        $result = new ProcessResult($this->mockForm, $data, $errors, true);

        $this->assertSame($data, $result->getProcessedData());
        $this->assertSame($errors, $result->getErrors());
        $this->assertTrue($result->isValid());
    }

    public function testGetForm(): void
    {
        $data = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $formData = FormData::fromArray($data);
        $newForm = $this->createMock(FormInterface::class);

        $this->mockForm
            ->expects($this->once())
            ->method('withData')
            ->with($formData)
            ->willReturn($newForm);

        $result = new ProcessResult($this->mockForm, $data);

        $this->assertSame($newForm, $result->getForm());
    }

    public function testGetFormWithProcessedData(): void
    {
        $originalData = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $processedData = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $formData = FormData::fromArray($processedData);
        $newForm = $this->createMock(FormInterface::class);

        $this->mockForm
            ->expects($this->once())
            ->method('withData')
            ->with($formData)
            ->willReturn($newForm);

        $result = new ProcessResult($this->mockForm, $processedData);

        $this->assertSame($newForm, $result->getForm());
    }

    public function testGetFormPreservesInvalidData(): void
    {
        $invalidData = [
            'name' => 'Jo', // Too short
            'email' => 'invalid-email', // Invalid format
            'age' => 'not-a-number', // Invalid type
        ];
        $formData = FormData::fromArray($invalidData);
        $newForm = $this->createMock(FormInterface::class);

        $this->mockForm
            ->expects($this->once())
            ->method('withData')
            ->with($formData)
            ->willReturn($newForm);

        $result = new ProcessResult($this->mockForm, $invalidData, [
            'name' => ['Name must be at least 3 characters'],
            'email' => ['Invalid email format'],
            'age' => ['Age must be a number'],
        ], false);

        $formWithData = $result->getForm();
        $this->assertSame($newForm, $formWithData);
    }

    public function testGetFormPreservesMixedValidAndInvalidData(): void
    {
        $mixedData = [
            'name' => 'John Doe', // Valid
            'email' => 'invalid-email', // Invalid
            'age' => 25, // Valid
            'phone' => '123', // Invalid (too short)
        ];
        $formData = FormData::fromArray($mixedData);
        $newForm = $this->createMock(FormInterface::class);

        $this->mockForm
            ->expects($this->once())
            ->method('withData')
            ->with($formData)
            ->willReturn($newForm);

        $result = new ProcessResult($this->mockForm, $mixedData, [
            'email' => ['Invalid email format'],
            'phone' => ['Phone number is too short'],
        ], false);

        $formWithData = $result->getForm();
        $this->assertSame($newForm, $formWithData);
    }

    public function testGetFormPreservesEmptyAndNullValues(): void
    {
        $dataWithEmptyValues = [
            'name' => '', // Empty string
            'email' => null, // Null value
            'age' => 0, // Zero value
            'active' => false, // Boolean false
        ];
        $formData = FormData::fromArray($dataWithEmptyValues);
        $newForm = $this->createMock(FormInterface::class);

        $this->mockForm
            ->expects($this->once())
            ->method('withData')
            ->with($formData)
            ->willReturn($newForm);

        $result = new ProcessResult($this->mockForm, $dataWithEmptyValues, [
            'name' => ['Name is required'],
            'email' => ['Email is required'],
        ], false);

        $formWithData = $result->getForm();
        $this->assertSame($newForm, $formWithData);
    }
}
