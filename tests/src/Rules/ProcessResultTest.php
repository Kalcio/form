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

use Derafu\Form\Rules\ProcessResult;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Tests for ProcessResult.
 */
#[CoversClass(\Derafu\Form\Rules\ProcessResult::class)]
final class ProcessResultTest extends TestCase
{
    public function testValidResult(): void
    {
        $data = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $result = new ProcessResult($data);

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
        $result = new ProcessResult($data, $errors, false);

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
        $result = new ProcessResult($data, $errors, false);

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
        $result = new ProcessResult($data, $errors, false);

        $this->assertTrue($result->hasFieldErrors('email'));
        $this->assertFalse($result->hasFieldErrors('name'));
        $this->assertFalse($result->hasFieldErrors('non_existent_field'));
    }

    public function testEmptyErrorsArray(): void
    {
        $data = ['name' => 'John Doe'];
        $errors = [];
        $result = new ProcessResult($data, $errors, true);

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
        $result = new ProcessResult($data, $errors, false);

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
        $result = new ProcessResult($data, $errors, false);

        $this->assertSame($data, $result->getProcessedData());
        $this->assertSame($errors, $result->getErrors());
        $this->assertFalse($result->isValid());
        $this->assertTrue($result->hasErrors());
        $this->assertSame(['User data is invalid', 'Invalid theme value'], $result->getAllErrors());
    }

    public function testNullData(): void
    {
        $result = new ProcessResult(null, [], true);

        $this->assertNull($result->getProcessedData());
        $this->assertEmpty($result->getErrors());
        $this->assertTrue($result->isValid());
        $this->assertFalse($result->hasErrors());
    }

    public function testEmptyData(): void
    {
        $result = new ProcessResult([], [], true);

        $this->assertSame([], $result->getProcessedData());
        $this->assertEmpty($result->getErrors());
        $this->assertTrue($result->isValid());
        $this->assertFalse($result->hasErrors());
    }

    public function testValidResultWithDefaultParameters(): void
    {
        $data = ['name' => 'John Doe'];
        $result = new ProcessResult($data);

        $this->assertSame($data, $result->getProcessedData());
        $this->assertEmpty($result->getErrors());
        $this->assertTrue($result->isValid());
    }

    public function testInvalidResultWithDefaultParameters(): void
    {
        $data = ['name' => 'John Doe'];
        $errors = ['name' => ['Name is required']];
        $result = new ProcessResult($data, $errors);

        $this->assertSame($data, $result->getProcessedData());
        $this->assertSame($errors, $result->getErrors());
        $this->assertTrue($result->isValid()); // Default is true.
    }
}
