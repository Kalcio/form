<?php

declare(strict_types=1);

/**
 * Derafu: Form - JSON Forms inspired form generation library.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.dev>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Contract\Rules;

use Derafu\Form\Contract\FormInterface;
use Derafu\Form\Rules\ProcessResult;

/**
 * Interface for form data processing services.
 *
 * This interface defines the contract for services that process form data
 * using schema definitions and data processor rules.
 */
interface FormDataProcessorInterface
{
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
    public function process(array $data, FormInterface $form): ProcessResult;
}
