<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\ExamplesForm;

use Derafu\Http\Contract\RequestInterface;
use Derafu\Renderer\Contract\RendererInterface;
use Derafu\Routing\Exception\RouteNotFoundException;

/**
 * Controller for handling form examples.
 *
 * This controller provides methods to list and display form examples, serving
 * as a showcase for the form building capabilities.
 */
final class ExamplesController
{
    /**
     * Constructor.
     *
     * @param RendererInterface $renderer The template renderer.
     */
    public function __construct(private readonly RendererInterface $renderer)
    {
    }

    /**
     * List all available examples.
     *
     * @param RequestInterface $request The HTTP request.
     * @return string Rendered HTML of the examples index page.
     */
    public function index(RequestInterface $request): string
    {
        return $this->renderer->render('pages/examples', [
            'request' => $request,
            'examples' => Example::all(),
        ]);
    }

    /**
     * Show a specific example by its ID.
     *
     * @param RequestInterface $request The HTTP request.
     * @param string $id The example identifier.
     * @return string Rendered HTML of the specific example.
     * @throws RouteNotFoundException If the example ID doesn't exist.
     */
    public function show(RequestInterface $request, string $id): string
    {
        return $this->renderer->render('pages/example', [
            'request' => $request,
            'example' => Example::find($id),
        ]);
    }
}
