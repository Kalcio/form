<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Schema;

use Derafu\Form\Abstract\AbstractPropertySchema;
use Derafu\Form\Contract\Schema\IntegerSchemaInterface;

/**
 * Implementation of IntegerSchemaInterface.
 *
 * This class represents an integer schema and provides integer-specific validations.
 */
class IntegerSchema extends AbstractPropertySchema implements IntegerSchemaInterface
{
    /**
     * The exclusive maximum value.
     *
     * @var int|null
     */
    protected ?int $exclusiveMaximum = null;

    /**
     * The exclusive minimum value.
     *
     * @var int|null
     */
    protected ?int $exclusiveMinimum = null;

    /**
     * The maximum value.
     *
     * @var int|null
     */
    protected ?int $maximum = null;

    /**
     * The minimum value.
     *
     * @var int|null
     */
    protected ?int $minimum = null;

    /**
     * The divisor for the value.
     *
     * @var int|null
     */
    protected ?int $multipleOf = null;

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return 'integer';
    }

    /**
     * {@inheritDoc}
     */
    public function getExclusiveMaximum(): ?int
    {
        return $this->exclusiveMaximum;
    }

    /**
     * {@inheritDoc}
     */
    public function setExclusiveMaximum(int $exclusiveMaximum): self
    {
        $this->exclusiveMaximum = $exclusiveMaximum;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getExclusiveMinimum(): ?int
    {
        return $this->exclusiveMinimum;
    }

    /**
     * {@inheritDoc}
     */
    public function setExclusiveMinimum(int $exclusiveMinimum): self
    {
        $this->exclusiveMinimum = $exclusiveMinimum;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMaximum(): ?int
    {
        return $this->maximum;
    }

    /**
     * {@inheritDoc}
     */
    public function setMaximum(int $maximum): self
    {
        $this->maximum = $maximum;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMinimum(): ?int
    {
        return $this->minimum;
    }

    /**
     * {@inheritDoc}
     */
    public function setMinimum(int $minimum): self
    {
        $this->minimum = $minimum;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMultipleOf(): ?int
    {
        return $this->multipleOf;
    }

    /**
     * {@inheritDoc}
     */
    public function setMultipleOf(int $multipleOf): self
    {
        $this->multipleOf = $multipleOf;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $array = parent::toArray();

        if ($this->exclusiveMaximum !== null) {
            $array['exclusiveMaximum'] = $this->exclusiveMaximum;
        }

        if ($this->exclusiveMinimum !== null) {
            $array['exclusiveMinimum'] = $this->exclusiveMinimum;
        }

        if ($this->maximum !== null) {
            $array['maximum'] = $this->maximum;
        }

        if ($this->minimum !== null) {
            $array['minimum'] = $this->minimum;
        }

        if ($this->multipleOf !== null) {
            $array['multipleOf'] = $this->multipleOf;
        }

        return $array;
    }

    /**
     * {@inheritDoc}
     */
    public static function fromArray(array $definition): self
    {
        $schema = new self($definition['name'] ?? '');

        // Set common properties.
        if (isset($definition['title'])) {
            $schema->setTitle($definition['title']);
        }

        if (isset($definition['description'])) {
            $schema->setDescription($definition['description']);
        }

        if (isset($definition['default'])) {
            $schema->setDefault($definition['default']);
        }

        if (isset($definition['deprecated'])) {
            $schema->setDeprecated($definition['deprecated']);
        }

        if (isset($definition['examples'])) {
            $schema->setExamples($definition['examples']);
        }

        if (isset($definition['readOnly'])) {
            $schema->setReadOnly($definition['readOnly']);
        }

        if (isset($definition['writeOnly'])) {
            $schema->setWriteOnly($definition['writeOnly']);
        }

        if (isset($definition['enum'])) {
            $schema->setEnum($definition['enum']);
        }

        if (isset($definition['const'])) {
            $schema->setConst($definition['const']);
        }

        if (isset($definition['allOf'])) {
            $schema->setAllOf($definition['allOf']);
        }

        if (isset($definition['anyOf'])) {
            $schema->setAnyOf($definition['anyOf']);
        }

        if (isset($definition['oneOf'])) {
            $schema->setOneOf($definition['oneOf']);
        }

        // Set integer-specific properties.
        if (isset($definition['exclusiveMaximum'])) {
            $schema->setExclusiveMaximum((int)$definition['exclusiveMaximum']);
        }

        if (isset($definition['exclusiveMinimum'])) {
            $schema->setExclusiveMinimum((int)$definition['exclusiveMinimum']);
        }

        if (isset($definition['maximum'])) {
            $schema->setMaximum((int)$definition['maximum']);
        }

        if (isset($definition['minimum'])) {
            $schema->setMinimum((int)$definition['minimum']);
        }

        if (isset($definition['multipleOf'])) {
            $schema->setMultipleOf((int)$definition['multipleOf']);
        }

        return $schema;
    }
}
