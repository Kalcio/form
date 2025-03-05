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

use InvalidArgumentException;

/**
 * Interface for widget renderer registry.
 *
 * This interface defines the methods required to register and retrieve widget
 * renderers for different widget types.
 */
interface WidgetRendererRegistryInterface
{
    /**
     * Registers a renderer for a widget type.
     *
     * @param string $widgetType The widget type to register the renderer for.
     * @param WidgetRendererInterface $renderer The renderer to register.
     * @return static Fluent interface.
     */
    public function registerRenderer(
        string $widgetType,
        WidgetRendererInterface $renderer
    ): static;

    /**
     * Gets the renderer for a widget type.
     *
     * @param string $widgetType The widget type to get the renderer for.
     * @return WidgetRendererInterface The renderer for the widget type. If no
     * renderer is registered for the widget type it will use the first
     * registered as default (tipically the default renderer should be the
     * InputWidgetRenderer).
     * @throws InvalidArgumentException If no renderers are registered.
     */
    public function getRenderer(string $widgetType): WidgetRendererInterface;

    /**
     * Checks if a renderer is registered for a widget type.
     *
     * @param string $widgetType The widget type to check.
     * @return bool Whether a renderer is registered for the widget type.
     */
    public function hasRenderer(string $widgetType): bool;
}
