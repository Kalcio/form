<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Renderer\Element;

use Derafu\Form\Contract\FormInterface;
use Derafu\Form\Contract\Renderer\ElementRendererInterface;
use Derafu\Form\Contract\Renderer\FormRendererInterface;
use Derafu\Form\Contract\UiSchema\UiSchemaElementInterface;
use Derafu\Form\Contract\UiSchema\VerticalLayoutInterface;
use InvalidArgumentException;

/**
 * Renderer for vertical layout elements.
 */
class VerticalLayoutRenderer implements ElementRendererInterface
{
    /**
     * {@inheritDoc}
     */
    public function render(
        UiSchemaElementInterface $element,
        FormInterface $form,
        array $options = []
    ): string {
        // Check if the element is a VerticalLayoutInterface.
        if (!$element instanceof VerticalLayoutInterface) {
            throw new InvalidArgumentException(sprintf(
                'Element must be an instance of %s in VerticalLayoutRenderer, %s given.',
                VerticalLayoutInterface::class,
                get_class($element)
            ));
        }

        // Get the main form renderer from options.
        $formRenderer = $options['renderer'] ?? null;
        if (!$formRenderer instanceof FormRendererInterface) {
            throw new InvalidArgumentException(
                'The "renderer" option in VerticalLayoutRenderer must be an instance of FormRendererInterface.'
            );
        }

        // Prepare context for template.
        $context = [
            'element' => $element,
            'form' => $form,
            'options' => $options,
            // Render child elements.
            'elements_html' => $formRenderer->renderElements(
                $element->getElements(),
                $form,
                $options
            ),
        ];

        // Render the template.
        return $formRenderer->getRenderer()->render(
            'form/element/vertical_layout',
            $context
        );
    }
}
