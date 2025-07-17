<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.dev>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Contract\Processor;

use Derafu\Form\Contract\FormFieldInterface;
use Derafu\Form\Contract\FormInterface;

/**
 * Maps Derafu Form definitions to Derafu Data Processor rules.
 *
 * This interface provides methods to convert form schema and UI schema
 * definitions into validation and processing rules that can be used by the
 * Derafu Data Processor.
 */
interface SchemaToRulesMapperInterface
{
    /**
     * Maps a complete form to field rules mapping.
     *
     * This method processes all fields in a form and returns a mapping of field
     * names to their corresponding processing rules.
     *
     * @param FormInterface $form The form to map.
     * @return array<string, array> Associative array of field names to their
     * rules.
     */
    public function mapFormToRules(FormInterface $form): array;

    /**
     * Maps a form field to Derafu Data Processor rules.
     *
     * This method considers both the schema property definition and the UI
     * control configuration to generate comprehensive processing rules.
     *
     * @param FormFieldInterface $field The form field to map.
     * @return array The Derafu Data Processor rules array with 'cast',
     * 'transform', 'sanitize', 'validate' keys.
     */
    public function mapFieldToRules(FormFieldInterface $field): array;

    /**
     * Maps a Derafu Form Schema property to Derafu Data Processor rules.
     *
     * This method only considers the schema definition without UI context.
     * For complete mapping including UI-specific rules, use mapFieldToRules().
     *
     * @param array $propertySchema The Derafu Form Schema property definition.
     * @return array The Derafu Data Processor rules array with 'cast',
     * 'transform', 'sanitize', 'validate' keys.
     */
    public function mapSchemaToRules(array $propertySchema): array;
}
