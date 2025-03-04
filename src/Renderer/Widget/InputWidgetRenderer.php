<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Renderer\Widget;

use Derafu\Form\Contract\FormFieldInterface;
use Derafu\Form\Contract\Renderer\FormRendererInterface;
use Derafu\Form\Contract\Renderer\WidgetRendererInterface;
use Derafu\Form\Contract\Schema\StringSchemaInterface;
use InvalidArgumentException;

/**
 * Renderer for input widgets.
 */
class InputWidgetRenderer implements WidgetRendererInterface
{
    /**
     * Constructor.
     *
     * @param string $type The type of input to render.
     */
    public function __construct(private readonly string $type = 'text')
    {
    }

    /**
     * {@inheritDoc}
     */
    public function render(FormFieldInterface $field, array $options = []): string
    {
        // Get the main form renderer from options.
        $formRenderer = $options['renderer'] ?? null;
        if (!$formRenderer instanceof FormRendererInterface) {
            throw new InvalidArgumentException(
                'The "renderer" option in InputWidgetRenderer must be an instance of FormRendererInterface.'
            );
        }

        $property = $field->getProperty();
        $control = $field->getControl();

        // Determine if there are errors.
        $hasErrors = $options['has_errors'] ?? false;

        // Prepare CSS classes.
        $widgetClass = 'form-control';
        if ($hasErrors) {
            $widgetClass .= ' is-invalid';
        }
        if (isset($options['widget_class'])) {
            $widgetClass .= ' ' . $options['widget_class'];
        }

        // Get field name.
        $name = $property->getName();

        // Get field value.
        $value = $options['value'] ?? null;

        // Build HTML attributes for the input.
        $attrs = [
            'type' => $options['attr']['type'] ?? $options['type'] ?? $this->type,
            'id' => $name . '_field',
            'name' => $name,
            'class' => $widgetClass,
        ];

        // Set value if provided.
        if ($value !== null) {
            if (is_array($value) || is_object($value)) {
                $value = json_encode($value);
            }
            $attrs['value'] = $value;
        }

        // Add validation attributes from property.
        if ($field->isRequired()) {
            $attrs['required'] = 'required';
        }

        if ($property instanceof StringSchemaInterface) {
            if ($property->getMinLength() !== null) {
                $attrs['minlength'] = (string)$property->getMinLength();
            }

            if ($property->getMaxLength() !== null) {
                $attrs['maxlength'] = (string)$property->getMaxLength();
            }

            if ($property->getPattern() !== null) {
                $attrs['pattern'] = $property->getPattern();
            }
        }

        // Add placeholder from control options if available.
        $controlOptions = $control->getOptions();
        if (!empty($controlOptions['placeholder'])) {
            $attrs['placeholder'] = $controlOptions['placeholder'];
        }

        // Apply custom attributes from options.
        if (isset($options['attr']) && is_array($options['attr'])) {
            $attrs = array_merge($attrs, $options['attr']);
        }

        // Prepare context for template.
        $context = [
            'field' => $field,
            'options' => $options,
            'attrs' => $attrs,
            'has_errors' => $hasErrors,
        ];

        // Render the template.
        return $formRenderer->getRenderer()->render('form/widget/input', $context);
    }
}
