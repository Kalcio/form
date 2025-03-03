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
use Derafu\Form\Contract\UiSchema\ControlInterface;
use Derafu\Form\Contract\UiSchema\ElementsAwareInterface;
use Derafu\Form\Contract\UiSchema\FormUiSchemaInterface;
use Derafu\Form\Contract\UiSchema\UiSchemaElementInterface;
use Derafu\Form\Data\FormData;
use Derafu\Form\Factory\FormUiSchemaFactory;
use Derafu\Form\Options\FormOptions;
use Derafu\Form\Schema\FormSchema;
use Derafu\Support\JsonSerializer;

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
            $this->collectFields($this->getUiSchema(), $this->fields);
        }

        return $this->fields;
    }

    /**
     * Recursively collects fields from UI elements.
     *
     * @param FormUiSchemaInterface|UiSchemaElementInterface $element The UI element to process.
     * @param array<string,FormFieldInterface> &$fields The collected fields (by reference).
     * @return void
     */
    private function collectFields(
        FormUiSchemaInterface|UiSchemaElementInterface $element,
        array &$fields
    ): void {
        // If it's a control, create a field.
        if ($element instanceof ControlInterface) {
            $scope = $element->getScope();
            // Extract property name from scope (assumed format:
            // "#/properties/propertyName").
            if (preg_match('~^#/properties/(.+)$~', $scope, $matches)) {
                $propertyName = $matches[1];
                $property = $this->getSchema()->getProperty($propertyName);

                if ($property) {
                    $fields[$propertyName] = new FormField($property, $element);
                }
            }
        }

        // If it has child elements, process them recursively.
        $childElements = $element instanceof ElementsAwareInterface
            ? $element->getElements()
            : null
        ;
        if ($childElements) {
            foreach ($childElements as $childElement) {
                $this->collectFields($childElement, $fields);
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
        $options = isset($definition['options']) ? FormOptions::fromArray($definition['options']) : null;

        return new self($schema, $uischema, $data, $options);
    }
}
