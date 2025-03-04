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
use Derafu\Form\Renderer\Widget\TextWidgetRenderer;

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
            'TextWidget' => new TextWidgetRenderer(),
        ];
    }
}
