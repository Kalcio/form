<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.dev>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Schema;

use Derafu\Form\Abstract\AbstractPropertySchema;
use Derafu\Form\Contract\Schema\NumberSchemaInterface;

/**
 * Implementation of NumberSchemaInterface.
 *
 * This class represents a number schema and provides number-specific validations.
 */
final class NumberSchema extends AbstractPropertySchema implements NumberSchemaInterface
{
    /**
     * The exclusive maximum value.
     *
     * @var int|float|null
     */
    protected int|float|null $exclusiveMaximum = null;

    /**
     * The exclusive minimum value.
     *
     * @var int|float|null
     */
    protected int|float|null $exclusiveMinimum = null;

    /**
     * The maximum value.
     *
     * @var int|float|null
     */
    protected int|float|null $maximum = null;

    /**
     * The minimum value.
     *
     * @var int|float|null
     */
    protected int|float|null $minimum = null;

    /**
     * The divisor for the value.
     *
     * @var int|float|null
     */
    protected int|float|null $multipleOf = null;

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return 'number';
    }

    /**
     * {@inheritDoc}
     */
    public function getExclusiveMaximum(): int|float|null
    {
        return $this->exclusiveMaximum;
    }

    /**
     * {@inheritDoc}
     */
    public function setExclusiveMaximum(int|float $exclusiveMaximum): static
    {
        $this->exclusiveMaximum = $exclusiveMaximum;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getExclusiveMinimum(): int|float|null
    {
        return $this->exclusiveMinimum;
    }

    /**
     * {@inheritDoc}
     */
    public function setExclusiveMinimum(int|float $exclusiveMinimum): static
    {
        $this->exclusiveMinimum = $exclusiveMinimum;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMaximum(): int|float|null
    {
        return $this->maximum;
    }

    /**
     * {@inheritDoc}
     */
    public function setMaximum(int|float $maximum): static
    {
        $this->maximum = $maximum;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMinimum(): int|float|null
    {
        return $this->minimum;
    }

    /**
     * {@inheritDoc}
     */
    public function setMinimum(int|float $minimum): static
    {
        $this->minimum = $minimum;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMultipleOf(): int|float|null
    {
        return $this->multipleOf;
    }

    /**
     * {@inheritDoc}
     */
    public function setMultipleOf(int|float $multipleOf): static
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
    public static function fromArray(array $definition): static
    {
        $schema = new static($definition['name'] ?? '');

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

        // Set number-specific properties.
        if (isset($definition['exclusiveMaximum'])) {
            $schema->setExclusiveMaximum($definition['exclusiveMaximum']);
        }

        if (isset($definition['exclusiveMinimum'])) {
            $schema->setExclusiveMinimum($definition['exclusiveMinimum']);
        }

        if (isset($definition['maximum'])) {
            $schema->setMaximum($definition['maximum']);
        }

        if (isset($definition['minimum'])) {
            $schema->setMinimum($definition['minimum']);
        }

        if (isset($definition['multipleOf'])) {
            $schema->setMultipleOf($definition['multipleOf']);
        }

        return $schema;
    }
}
