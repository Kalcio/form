<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Contract\Renderer;

/**
 * Interface for widget renderer provider.
 */
interface WidgetRendererProviderInterface
{
    /**
     * Gets the renderers.
     *
     * @return array<string,WidgetRendererInterface>
     */
    public function getRenderers(): array;
}
