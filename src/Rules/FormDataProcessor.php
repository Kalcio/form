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

use Derafu\DataProcessor\Contract\ProcessorInterface;
use Derafu\Form\Contract\FormInterface;
use Derafu\Form\Contract\Rules\FormDataProcessorInterface;
use Derafu\Form\Contract\Rules\SchemaToRulesMapperInterface;
use Derafu\Form\Exception\ValidationException;

/**
 * Service to process form data using form definitions and data processor rules.
 */
final class FormDataProcessor implements FormDataProcessorInterface
{
    public function __construct(
        private readonly SchemaToRulesMapperInterface $mapper,
        private readonly ProcessorInterface $processor
    ) {
    }

    /**
     * Process form data using form definition.
     *
     * This method:
     * 1. Maps the form to data processor rules
     * 2. Processes each field through the rules (cast, transform, sanitize, validate)
     * 3. Returns a result with processed data, errors, and validation status
     *
     * @param array<string, mixed> $data The form data to process
     * @param FormInterface $form The form definition
     * @return ProcessResult The processing result with data, errors, and validation status
     */
    public function process(array $data, FormInterface $form): ProcessResult
    {
        $processedData = [];
        $errors = [];
        $isValid = true;

        // Map form to rules
        $rules = $this->mapper->mapFormToRules($form);

        // Process each field
        foreach ($rules as $fieldName => $fieldRules) {
            $fieldValue = $data[$fieldName] ?? null;

            try {
                // Process the field value through all rules
                $processedValue = $this->processor->process($fieldValue, $fieldRules);
                $processedData[$fieldName] = $processedValue;
            } catch (ValidationException $e) {
                // Collect validation errors
                $errors[$fieldName] = [$e->getMessage()];
                $isValid = false;
                // Keep original value for invalid fields
                $processedData[$fieldName] = $fieldValue;
            } catch (\Throwable $e) {
                // Handle other processing errors
                $errors[$fieldName] = ['Error processing field: ' . $e->getMessage()];
                $isValid = false;
                $processedData[$fieldName] = $fieldValue;
            }
        }

        // Add any fields from data that weren't in the schema
        foreach ($data as $fieldName => $fieldValue) {
            if (!isset($processedData[$fieldName])) {
                $processedData[$fieldName] = $fieldValue;
            }
        }

        return new ProcessResult($processedData, $errors, $isValid);
    }
}
