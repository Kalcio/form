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
 * Represents an array schema.
 */
interface ArraySchemaInterface extends PropertySchemaInterface
{
    /**
     * Gets all items defined in the schema.
     *
     * @return array An array of items.
     */
    public function getItems(): array;

    /**
     * Sets the items defined in the schema.
     *
     * @param array $items
     * @return self The current instance.
     */
    public function setItems(array $items): self;

    /**
     * Gets the maximum number of items on array properties.
     *
     * @return integer|null
     * @link https://www.learnjsonschema.com/2020-12/validation/maxitems/
     */
    public function getMaxItems(): ?int;

    /**
     * Sets the maximum number of items on array properties.
     *
     * @param int $maxItems
     * @return self The current instance.
     */
    public function setMaxItems(int $maxItems): self;

    /**
     * Gets the minimum number of items on array properties.
     *
     * @return integer|null
     * @link https://www.learnjsonschema.com/2020-12/validation/minitems/
     */
    public function getMinItems(): ?int;

    /**
     * Sets the minimum number of items on array properties.
     *
     * @param int $minItems
     * @return self The current instance.
     */
    public function setMinItems(int $minItems): self;

    /**
     * Gets the sub-schemas that at least one element in an array property must
     * match.
     *
     * @return array|null
     * @link https://www.learnjsonschema.com/2020-12/applicator/contains/
     */
    public function getContains(): ?array;

    /**
     * Sets the sub-schemas that at least one element in an array property must
     * match.
     *
     * @param array $contains
     * @return self The current instance.
     */
    public function setContains(array $contains): self;

    /**
     * Gets the maximum times that the value of `getContains()` can be.
     *
     * @return integer|null
     * @link https://www.learnjsonschema.com/2020-12/validation/maxcontains/
     */
    public function getMaxContains(): ?int;

    /**
     * Sets the maximum times that the value of `getContains()` can be.
     *
     * @param int $maxContains
     * @return self The current instance.
     */
    public function setMaxContains(int $maxContains): self;

    /**
     * Gets the minimum times that the value of `getContains()` can be.
     *
     * @return integer|null
     * @link https://www.learnjsonschema.com/2020-12/validation/mincontains/
     */
    public function getMinContains(): ?int;

    /**
     * Sets the minimum times that the value of `getContains()` can be.
     *
     * @param int $minContains
     * @return self The current instance.
     */
    public function setMinContains(int $minContains): self;

    /**
     * Indicates if the values of a property must be all equals.
     *
     * @return boolean|null
     * @link https://www.learnjsonschema.com/2020-12/validation/uniqueitems/
     */
    public function needUniqueItems(): ?bool;

    /**
     * Sets the unique items status of the property.
     *
     * @param bool $uniqueItems
     * @return self The current instance.
     */
    public function setUniqueItems(bool $uniqueItems = true): self;
}
