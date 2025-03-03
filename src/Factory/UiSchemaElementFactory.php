<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Factory;

use Derafu\Form\Contract\Factory\UiSchemaElementFactoryInterface;
use Derafu\Form\Contract\UiSchema\UiSchemaElementInterface;
use Derafu\Form\UiSchema\Categorization;
use Derafu\Form\UiSchema\Category;
use Derafu\Form\UiSchema\Control;
use Derafu\Form\UiSchema\Group;
use Derafu\Form\UiSchema\HorizontalLayout;
use Derafu\Form\UiSchema\Label;
use Derafu\Form\UiSchema\VerticalLayout;
use InvalidArgumentException;

/**
 * Factory class for creating UiSchemaElement instances.
 */
final class UiSchemaElementFactory implements UiSchemaElementFactoryInterface
{
    /**
     * Valid types of UI schema elements.
     *
     * @var string[]
     */
    private const VALID_TYPES = [
        'Categorization' => Categorization::class,
        'Category' => Category::class,
        'Control' => Control::class,
        'Group' => Group::class,
        'HorizontalLayout' => HorizontalLayout::class,
        'Label' => Label::class,
        'VerticalLayout' => VerticalLayout::class,
    ];

    /**
     * {@inheritDoc}
     */
    public static function create(array $definition): UiSchemaElementInterface
    {
        $type = $definition['type'] ?? 'Control';

        if (!isset(self::VALID_TYPES[$type])) {
            throw new InvalidArgumentException(sprintf(
                'Invalid UI schema element type: %s. Valid types are: %s.',
                $type,
                implode(', ', self::VALID_TYPES)
            ));
        }

        $class = self::VALID_TYPES[$type];

        return $class::fromArray($definition);
    }
}
