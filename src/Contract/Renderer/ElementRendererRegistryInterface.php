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
 * Interface for element renderer registry.
 *
 * This interface defines the methods required to register and retrieve element
 * renderers for different UI element types.
 */
interface ElementRendererRegistryInterface
{
    /**
     * Registers a renderer for an element type.
     *
     * @param string $elementType The element type to register the renderer for.
     * @param ElementRendererInterface $renderer The renderer to register.
     * @return static Fluent interface.
     */
    public function registerRenderer(
        string $elementType,
        ElementRendererInterface $renderer
    ): static;

    /**
     * Gets the renderer for an element type.
     *
     * @param string $elementType The element type to get the renderer for.
     * @return ElementRendererInterface The renderer for the element type. If no
     * renderer is registered for the element type it will use the first
     * registered as default (tipically the default renderer should be the
     * ControlElementRenderer).
     * @throws InvalidArgumentException If no renderers are registered.
     */
    public function getRenderer(string $elementType): ElementRendererInterface;

    /**
     * Checks if a renderer is registered for an element type.
     *
     * @param string $elementType The element type to check.
     * @return bool Whether a renderer is registered for the element type.
     */
    public function hasRenderer(string $elementType): bool;
}
