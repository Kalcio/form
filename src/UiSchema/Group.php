<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\UiSchema;

use Derafu\Form\Abstract\AbstractUiSchemaElement;
use Derafu\Form\Contract\UiSchema\GroupInterface;
use Derafu\Form\Contract\UiSchema\UiSchemaElementInterface;
use Derafu\Form\Factory\UiSchemaElementFactory;

/**
 * Represents a group in a UI Schema.
 *
 * Groups are used to organize UI elements into a logical collection.
 */
final class Group extends AbstractUiSchemaElement implements GroupInterface
{
    /**
     * The label text.
     *
     * @var string
     */
    private string $label;

    /**
     * The elements of the group.
     *
     * @var array<UiSchemaElementInterface>
     */
    private array $elements;

    /**
     * Constructor.
     *
     * @param string $label The label text.
     * @param array<UiSchemaElementInterface> $elements The elements of the group.
     */
    public function __construct(string $label, array $elements = [])
    {
        parent::__construct([]);

        $this->label = $label;
        $this->elements = $elements;
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return 'Group';
    }

    /**
     * {@inheritDoc}
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * {@inheritDoc}
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * {@inheritDoc}
     */
    public function addElement(UiSchemaElementInterface $element): self
    {
        $this->elements[] = $element;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'type' => $this->getType(),
            'label' => $this->label,
            'elements' => array_map(
                fn (UiSchemaElementInterface $element) => $element->toArray(),
                $this->elements
            ),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function fromArray(array $definition): self
    {
        $elements = [];
        foreach ($definition['elements'] as $element) {
            $elements[] = UiSchemaElementFactory::create($element);
        }

        return new self($definition['label'], $elements);
    }
}
