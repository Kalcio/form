<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Contract\Schema;

/**
 * Represents a number property within a Schema.
 */
interface NumberSchemaInterface extends PropertySchemaInterface
{
    /**
     * Gets the exclusive maximum that the property value can reach.
     *
     * @return integer|float|null
     * @link https://www.learnjsonschema.com/2020-12/validation/exclusivemaximum/
     */
    public function getExclusiveMaximum(): int|float|null;

    /**
     * Sets the exclusive maximum that the property value can reach.
     *
     * @param int|float $exclusiveMaximum
     * @return self The current instance.
     */
    public function setExclusiveMaximum(int|float $exclusiveMaximum): self;

    /**
     * Gets the exclusive minimum that the property value can reach.
     *
     * @return integer|float|null
     * @link https://www.learnjsonschema.com/2020-12/validation/exclusiveminimum/
     */
    public function getExclusiveMinimum(): int|float|null;

    /**
     * Sets the exclusive minimum that the property value can reach.
     *
     * @param int|float $exclusiveMinimum
     * @return self The current instance.
     */
    public function setExclusiveMinimum(int|float $exclusiveMinimum): self;

    /**
     * Gets the maximum that the property value can reach.
     *
     * @return integer|float|null
     * @link https://www.learnjsonschema.com/2020-12/validation/maximum/
     */
    public function getMaximum(): int|float|null;

    /**
     * Sets the maximum that the property value can reach.
     *
     * @param int|float $maximum
     * @return self The current instance.
     */
    public function setMaximum(int|float $maximum): self;

    /**
     * Gets the minimum that the property value can reach.
     *
     * @return integer|float|null
     * @link https://www.learnjsonschema.com/2020-12/validation/minimum/
     */
    public function getMinimum(): int|float|null;

    /**
     * Sets the minimum that the property value can reach.
     *
     * @param int|float $minimum
     * @return self The current instance.
     */
    public function setMinimum(int|float $minimum): self;

    /**
     * Gets the divisor of the value property that results in an integer value.
     *
     * @return integer|float|null
     * @link https://www.learnjsonschema.com/2020-12/validation/multipleof/
     */
    public function getMultipleOf(): int|float|null;

    /**
     * Sets the divisor of the value property that results in an integer value.
     *
     * @param int|float $multipleOf
     * @return self The current instance.
     */
    public function setMultipleOf(int|float $multipleOf): self;
}
