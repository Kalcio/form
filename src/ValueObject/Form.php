<?php

declare(strict_types=1);

/**
 * Derafu: Form - Form Builder with Easy Definition and Layouts.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\ValueObject;

use Derafu\Form\Contract\ValueObject\DataInterface;
use Derafu\Form\Contract\ValueObject\FormInterface;
use Derafu\Form\Contract\ValueObject\SchemaInterface;
use Derafu\Form\Contract\ValueObject\UiSchemaInterface;
use Derafu\Form\Serializer\JsonSerializer;

/**
 * Represents a form defined using a declarative approach.
 *
 * A form consists of three main components:
 *
 *   - Schema: Defines the data structure and validation rules.
 *   - UiSchema: Defines how the form should be rendered.
 *   - Data: The actual data values for the form fields.
 */
final class Form implements FormInterface
{
    /**
     * Creates a new Form.
     *
     * @param SchemaInterface $schema
     * @param UiSchemaInterface $uischema
     * @param DataInterface|null $data
     */
    public function __construct(
        private readonly SchemaInterface $schema,
        private readonly UiSchemaInterface $uischema,
        private readonly ?DataInterface $data = null
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getSchema(): SchemaInterface
    {
        return $this->schema;
    }

    /**
     * {@inheritDoc}
     */
    public function getUiSchema(): UiSchemaInterface
    {
        return $this->uischema;
    }

    /**
     * {@inheritDoc}
     */
    public function getData(): ?DataInterface
    {
        return $this->data;
    }

    /**
     * {@inheritDoc}
     */
    public function withData(DataInterface $data): self
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
            'data' => $this->data ? $this->data->toArray() : null,
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
            'data' => $this->data ? $this->data : null,
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
        $schema = Schema::fromArray($definition['schema'] ?? []);
        $uischema = UiSchema::fromArray($definition['uischema'] ?? []);
        $data = isset($definition['data']) ? Data::fromArray($definition['data']) : null;

        return new self($schema, $uischema, $data);
    }
}
