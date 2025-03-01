<?php

declare(strict_types=1);

/**
 * Derafu: Form - Form Builder with Easy Definition and Layouts.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Contract;

use JsonSerializable;

/**
 * Represents a form defined using a declarative approach.
 *
 * A form consists of three main components:
 *
 *   - Schema: Defines the data structure and validation rules.
 *   - UiSchema: Defines how the form should be rendered.
 *   - Data: The actual data values for the form fields.
 */
interface FormInterface extends JsonSerializable
{
    /**
     * Gets the schema that defines the data structure and validation rules.
     *
     * @return SchemaInterface The form's schema.
     */
    public function getSchema(): SchemaInterface;

    /**
     * Gets the UI schema that defines how the form should be rendered.
     *
     * @return UiSchemaInterface The form's UI schema
     */
    public function getUiSchema(): UiSchemaInterface;

    /**
     * Gets the current data values of the form.
     *
     * @return DataInterface|null The current form data or null if no data is set.
     */
    public function getData(): ?DataInterface;

    /**
     * Creates a new instance of the form with the provided data.
     *
     * This method follows the immutability principle, returning a new instance
     * rather than modifying the current one.
     *
     * @param DataInterface $data The data to use for the new form instance.
     * @return self A new form instance with the updated data.
     */
    public function withData(DataInterface $data): self;

    /**
     * Converts the Form to an array representation.
     *
     * @return array The complete form as an array.
     */
    public function toArray(): array;

    /**
     * Converts the Form to a JSON representation.
     *
     * @return string
     */
    public function toJson(): string;

    /**
     * Creates a form from an array definition.
     *
     * The array should contain 'schema', 'uischema', and optionally 'data' keys.
     *
     * @param array $definition The array definition of the form.
     * @return self A new form instance based on the provided definition.
     */
    public static function fromArray(array $definition): self;
}
