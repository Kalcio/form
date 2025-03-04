<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Contract;

use Derafu\Form\Contract\Data\FormDataInterface;
use Derafu\Form\Contract\Options\FormOptionsInterface;
use Derafu\Form\Contract\Schema\FormSchemaInterface;
use Derafu\Form\Contract\Schema\PropertySchemaInterface;
use Derafu\Form\Contract\UiSchema\FormUiSchemaInterface;
use JsonSerializable;

/**
 * Represents a form defined using a declarative approach.
 *
 * A form consists of three main components:
 *
 *   - Schema: Defines the data structure and validation rules.
 *   - UiSchema: Defines how the form should be rendered.
 *   - Data: The actual data values for the form fields.
 *   - Options: Additional options for the form.
 */
interface FormInterface extends JsonSerializable
{
    /**
     * Gets the schema that defines the data structure and validation rules.
     *
     * @return FormSchemaInterface The form's schema.
     */
    public function getSchema(): FormSchemaInterface;

    /**
     * Gets the UI schema that defines how the form should be rendered.
     *
     * @return FormUiSchemaInterface The form's UI schema
     */
    public function getUiSchema(): FormUiSchemaInterface;

    /**
     * Gets the current data values of the form.
     *
     * @return FormDataInterface|null The current form data or `null` if no data is set.
     */
    public function getData(): ?FormDataInterface;

    /**
     * Gets the options of the form.
     *
     * @return FormOptionsInterface|null The form options or `null` if no options are set.
     */
    public function getOptions(): ?FormOptionsInterface;

    /**
     * Gets all fields in the form that have a related control ui schema element.
     *
     * @return array<string,FormFieldInterface> An associative array of fields.
     */
    public function getFields(): array;

    /**
     * Gets a specific field by name.
     *
     * @param string $name The field name.
     * @return FormFieldInterface|null The field or `null` if not found or not
     * related to a control ui schema element.
     */
    public function getField(string $name): ?FormFieldInterface;

    /**
     * Finds a property by scope.
     *
     * @param string $scope The scope of the property.
     * @return PropertySchemaInterface|null The property or `null` if not found.
     */
    public function findPropertyByScope(string $scope): ?PropertySchemaInterface;

    /**
     * Creates a new instance of the form with the provided data.
     *
     * This method follows the immutability principle, returning a new instance
     * rather than modifying the current one.
     *
     * @param FormDataInterface $data The data to use for the new form instance.
     * @return self A new form instance with the updated data.
     */
    public function withData(FormDataInterface $data): self;

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
