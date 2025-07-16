<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.dev>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Rules;

use Derafu\Form\Contract\Rules\ProcessResultInterface;

/**
 * Result of processing form data through rules.
 */
final class ProcessResult implements ProcessResultInterface
{
    /**
     * @param mixed $processedData The processed data after applying all rules.
     * @param array<string, string[]> $errors Validation errors by field name.
     * @param bool $isValid Whether the data passed all validations.
     */
    public function __construct(
        private readonly mixed $processedData,
        private readonly array $errors = [],
        private readonly bool $isValid = true
    ) {
    }

    /**
     * Get the processed data.
     *
     * @return mixed The processed data.
     */
    public function getProcessedData(): mixed
    {
        return $this->processedData;
    }

    /**
     * Get validation errors by field name.
     *
     * @return array<string, string[]>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Check if the data is valid.
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * Check if there are any errors.
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Get all error messages as a flat array.
     *
     * @return string[]
     */
    public function getAllErrors(): array
    {
        $allErrors = [];
        foreach ($this->errors as $fieldErrors) {
            $allErrors = array_merge($allErrors, $fieldErrors);
        }

        return $allErrors;
    }

    /**
     * Get errors for a specific field.
     *
     * @return string[]
     */
    public function getFieldErrors(string $fieldName): array
    {
        return $this->errors[$fieldName] ?? [];
    }

    /**
     * Check if a specific field has errors.
     */
    public function hasFieldErrors(string $fieldName): bool
    {
        return
            isset($this->errors[$fieldName])
            && !empty($this->errors[$fieldName])
        ;
    }
}
