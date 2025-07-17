<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.dev>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Renderer\Element;

use Derafu\Form\Contract\FormInterface;
use Derafu\Form\Contract\Renderer\ElementRendererInterface;
use Derafu\Form\Contract\Renderer\FormRendererInterface;
use Derafu\Form\Contract\UiSchema\ControlInterface;
use Derafu\Form\Contract\UiSchema\UiSchemaElementInterface;
use InvalidArgumentException;

/**
 * Renderer for control elements.
 *
 * Controls are UI elements that render form fields (inputs, selects, etc).
 */
final class ControlRenderer implements ElementRendererInterface
{
    /**
     * {@inheritDoc}
     */
    public function render(
        UiSchemaElementInterface $element,
        FormInterface $form,
        array $options = []
    ): string {
        // Check if the element is a control.
        if (!$element instanceof ControlInterface) {
            throw new InvalidArgumentException(sprintf(
                'Element must be an instance of %s in ControlRenderer, %s given.',
                ControlInterface::class,
                get_class($element)
            ));
        }

        // Get the main form renderer from options.
        $formRenderer = $options['renderer'] ?? null;
        if (!$formRenderer instanceof FormRendererInterface) {
            throw new InvalidArgumentException(
                'The "renderer" option in ControlRenderer must be an instance of FormRendererInterface.'
            );
        }

        // Find the field from the form using the property name.
        $propertyName = $element->getPropertyName();
        $field = $form->getField($propertyName);

        if (!$field) {
            throw new InvalidArgumentException(sprintf(
                'Field with property name "%s" not found in form.',
                $propertyName
            ));
        }

        // Determine if we need to render a full field or just the widget.
        $renderMode = $options['render_mode'] ?? 'row';

        // Merge control options with passed options.
        $fieldOptions = array_merge($element->getOptions(), $options);

        // Render based on the mode.
        if ($renderMode === 'widget') {
            // Render just the widget.
            return $formRenderer->renderWidget($field, $fieldOptions);
        } else {
            // Render the complete field.
            return $formRenderer->renderRow($field, $fieldOptions);
        }
    }
}
