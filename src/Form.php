<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form;

use Derafu\Form\Contract\Data\FormDataInterface;
use Derafu\Form\Contract\FormFieldInterface;
use Derafu\Form\Contract\FormInterface;
use Derafu\Form\Contract\Options\FormOptionsInterface;
use Derafu\Form\Contract\Schema\FormSchemaInterface;
use Derafu\Form\Contract\Schema\ObjectSchemaInterface;
use Derafu\Form\Contract\Schema\PropertySchemaInterface;
use Derafu\Form\Contract\UiSchema\ControlInterface;
use Derafu\Form\Contract\UiSchema\ElementsAwareInterface;
use Derafu\Form\Contract\UiSchema\FormUiSchemaInterface;
use Derafu\Form\Contract\UiSchema\UiSchemaElementInterface;
use Derafu\Form\Data\FormData;
use Derafu\Form\Factory\FormUiSchemaFactory;
use Derafu\Form\Options\FormOptions;
use Derafu\Form\Schema\FormSchema;
use Derafu\Support\JsonSerializer;
use InvalidArgumentException;

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
final class Form implements FormInterface
{
    /**
     * Fields of the form.
     *
     * @var array<string,FormFieldInterface>
     */
    private array $fields;

    /**
     * Creates a new Form.
     *
     * @param FormSchemaInterface $schema
     * @param FormUiSchemaInterface $uischema
     * @param FormDataInterface|null $data
     * @param FormOptionsInterface|null $options
     */
    public function __construct(
        private readonly FormSchemaInterface $schema,
        private readonly FormUiSchemaInterface $uischema,
        private readonly ?FormDataInterface $data = null,
        private readonly ?FormOptionsInterface $options = null,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getSchema(): FormSchemaInterface
    {
        return $this->schema;
    }

    /**
     * {@inheritDoc}
     */
    public function getUiSchema(): FormUiSchemaInterface
    {
        return $this->uischema;
    }

    /**
     * {@inheritDoc}
     */
    public function getData(): ?FormDataInterface
    {
        return $this->data;
    }

    /**
     * {@inheritDoc}
     */
    public function getOptions(): ?FormOptionsInterface
    {
        return $this->options;
    }

    /**
     * {@inheritDoc}
     */
    public function getFields(): array
    {
        if (!isset($this->fields)) {
            $this->fields = [];
            $this->collectFieldsFromElements(
                $this->getUiSchema()->getElements(),
                $this->fields
            );
        }

        return $this->fields;
    }

    /**
     * Recursively collects fields from UI elements.
     *
     * @param array<UiSchemaElementInterface> $elements The UI elements to process.
     * @param array<string,FormFieldInterface> &$fields The collected fields.
     * @return void
     */
    private function collectFieldsFromElements(array $elements, array &$fields): void
    {
        foreach ($elements as $element) {
            // If it's a control, create a field.
            if ($element instanceof ControlInterface) {
                $scope = $element->getScope();
                // Extract property name from scope (assumed format:
                // "#/properties/propertyName").
                if (preg_match('~^#/properties/(.+)$~', $scope, $matches)) {
                    $propertyName = $matches[1];
                    $property = $this->getSchema()->getProperty($propertyName);

                    if ($property) {
                        $fields[$propertyName] = new FormField(
                            $property,
                            $element
                        );
                    }
                }
            }

            // If it's a layout element with child elements (VerticalLayout,
            // HorizontalLayout, Group, etc.)
            if ($element instanceof ElementsAwareInterface) {
                $this->collectFieldsFromElements(
                    $element->getElements(),
                    $fields
                );
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getField(string $name): ?FormFieldInterface
    {
        $fields = $this->getFields();

        return $fields[$name] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function findPropertyByScope(string $scope): ?PropertySchemaInterface
    {
        // Check the format of the scope.
        if (!preg_match('~^#/properties/(.+)$~', $scope, $matches)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid scope format: %s. Expected format: #/properties/path/to/property',
                $scope
            ));
        }

        // Get the full path of the property.
        $fullPath = $matches[1];

        // Split by "properties" to extract each property name.
        $segments = preg_split('~/properties/~', $fullPath);

        // The first segment is the root property name.
        $rootPropertyName = $segments[0];
        $property = $this->schema->getProperty($rootPropertyName);

        if (!$property) {
            return null;
        }

        // Navigate through the remaining segments.
        for ($i = 1; $i < count($segments); $i++) {
            if (!$property instanceof ObjectSchemaInterface) {
                return null; // Cannot navigate deeper.
            }

            $property = $property->getProperty($segments[$i]);

            if (!$property) {
                return null;
            }
        }

        return $property;
    }

    /**
     * {@inheritDoc}
     */
    public function withData(FormDataInterface $data): self
    {
        return new self($this->schema, $this->uischema, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'schema' => $this->schema->toArray(),
            'uischema' => $this->uischema->toArray(),
            'data' => $this->data?->toArray(),
            'options' => $this->options?->toArray(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): array
    {
        return [
            'schema' => $this->schema,
            'uischema' => $this->uischema,
            'data' => $this->data,
            'options' => $this->options,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function toJson(): string
    {
        return JsonSerializer::serialize($this->jsonSerialize());
    }

    /**
     * {@inheritDoc}
     */
    public static function fromArray(array $definition): self
    {
        $schema = FormSchema::fromArray($definition['schema'] ?? []);
        $uischema = FormUiSchemaFactory::create($definition['uischema'] ?? []);
        $data = isset($definition['data']) ? FormData::fromArray($definition['data']) : null;
        $options = FormOptions::fromArray($definition['options'] ?? []);

        return new self($schema, $uischema, $data, $options);
    }
}
