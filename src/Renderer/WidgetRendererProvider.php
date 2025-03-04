<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Renderer;

use Derafu\Form\Contract\Renderer\WidgetRendererProviderInterface;
use Derafu\Form\Renderer\Widget\InputWidgetRenderer;
use Derafu\Form\Renderer\Widget\TextareaWidgetRenderer;

/**
 * Widget renderer provider.
 */
class WidgetRendererProvider implements WidgetRendererProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getRenderers(): array
    {
        return [
            // Input types.
            'text' => new InputWidgetRenderer(),
            'button' => new InputWidgetRenderer('button'),
            'checkbox' => new InputWidgetRenderer('checkbox'),
            'color' => new InputWidgetRenderer('color'),
            'date' => new InputWidgetRenderer('date'),
            'datetime' => new InputWidgetRenderer('datetime-local'),
            'email' => new InputWidgetRenderer('email'),
            'file' => new InputWidgetRenderer('file'),
            'hidden' => new InputWidgetRenderer('hidden'),
            'image' => new InputWidgetRenderer('image'),
            'month' => new InputWidgetRenderer('month'),
            'number' => new InputWidgetRenderer('number'),
            'password' => new InputWidgetRenderer('password'),
            'radio' => new InputWidgetRenderer('radio'),
            'range' => new InputWidgetRenderer('range'),
            'reset' => new InputWidgetRenderer('reset'),
            'search' => new InputWidgetRenderer('search'),
            'submit' => new InputWidgetRenderer('submit'),
            'tel' => new InputWidgetRenderer('tel'),
            'time' => new InputWidgetRenderer('time'),
            'url' => new InputWidgetRenderer('url'),
            'week' => new InputWidgetRenderer('week'),

            'textarea' => new TextareaWidgetRenderer(),

            //'select' => new SelectWidgetRenderer(),
        ];
    }
}
