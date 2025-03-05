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
use Derafu\Form\Contract\UiSchema\UiSchemaElementInterface;
use Derafu\Form\Contract\UiSchema\VerticalLayoutInterface;
use Derafu\Form\Factory\UiSchemaElementFactory;

/**
 * Represents a vertical layout in a UI Schema.
 *
 * VerticalLayout elements are used to arrange UI elements in a vertical column.
 */
final class VerticalLayout extends AbstractUiSchemaElement implements VerticalLayoutInterface
{
    /**
     * Elements of the vertical layout.
     *
     * @var array<UiSchemaElementInterface>
     */
    private array $elements;

    /**
     * Constructor.
     *
     * @param array<UiSchemaElementInterface> $elements
     */
    public function __construct(array $elements = [])
    {
        parent::__construct([]);

        $this->elements = $elements;
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return 'VerticalLayout';
    }

    /**
     * {@inheritDoc}
     */
    public function addElement(UiSchemaElementInterface $element): static
    {
        $this->elements[] = $element;

        return $this;
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
    public function toArray(): array
    {
        return [
            'type' => $this->getType(),
            'elements' => array_map(
                fn (UiSchemaElementInterface $element) => $element->toArray(),
                $this->elements
            ),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function fromArray(array $definition): static
    {
        $elements = [];
        foreach ($definition['elements'] as $element) {
            $elements[] = UiSchemaElementFactory::create($element);
        }

        return new static($elements);
    }
}
