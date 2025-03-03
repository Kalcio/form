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

use Derafu\Form\Contract\FormFieldInterface;
use Derafu\Form\Contract\FormInterface;

/**
 * Interface for form renderers.
 *
 * This interface defines the methods required to render forms and their
 * components. Implementations of this interface should handle the actual
 * rendering of forms, delegating when appropriate to more specialized
 * renderers, like control renderers or layout renderers.
 */
interface FormRendererInterface
{
    /**
     * Renders the HTML of the entire form.
     *
     * @param FormInterface $form The form to render.
     * @param array $options Additional options for the rendering.
     * @return string The rendered form HTML.
     */
    public function render(FormInterface $form, array $options = []): string;

    /**
     * Renders the opening form tag with appropriate attributes.
     *
     * @param FormInterface $form The form to render the start tag for.
     * @param array $options Additional options for the rendering.
     * @return string The rendered opening form tag.
     */
    public function renderStart(
        FormInterface $form,
        array $options = []
    ): string;

    /**
     * Renders the closing form tag and optionally renders remaining fields.
     *
     * @param FormInterface $form The form to render the end tag for.
     * @param array $options Additional options for the rendering.
     * @return string The rendered closing form tag.
     */
    public function renderEnd(
        FormInterface $form,
        array $options = []
    ): string;

    /**
     * Renders the label for the given field.
     *
     * @param FormFieldInterface $field The form field to render the label for.
     * @param string|null $label Optional override for the label text.
     * @param array $options Additional options for the rendering.
     * @return string The rendered label HTML.
     */
    public function renderLabel(
        FormFieldInterface $field,
        ?string $label = null,
        array $options = []
    ): string;

    /**
     * Renders any errors for the given field.
     *
     * @param FormFieldInterface $field The form field to render errors for.
     * @param array $options Additional options for the rendering.
     * @return string The rendered errors HTML.
     */
    public function renderErrors(
        FormFieldInterface $field,
        array $options = []
    ): string;

    /**
     * Renders the widget (input field) for a form element.
     *
     * @param FormFieldInterface $field The form field to render the widget for.
     * @param array $options Additional options for the rendering.
     * @return string The rendered widget HTML.
     */
    public function renderWidget(
        FormFieldInterface $field,
        array $options = []
    ): string;

    /**
     * Renders the help text for a form element.
     *
     * @param FormFieldInterface $field The form field to render help text for.
     * @param array $options Additional options for the rendering.
     * @return string The rendered help text HTML.
     */
    public function renderHelp(
        FormFieldInterface $field,
        array $options = []
    ): string;

    /**
     * Renders a complete form row (label, widget, errors, and help text).
     *
     * @param FormFieldInterface $field The form field to render the row for.
     * @param array $options Additional options for the rendering.
     * @return string The rendered row HTML.
     */
    public function renderRow(
        FormFieldInterface $field,
        array $options = []
    ): string;

    /**
     * Renders all unrendered fields in the form.
     *
     * @param FormInterface $form The form to render remaining fields for.
     * @param array $options Additional options for the rendering.
     * @return string The rendered remaining fields HTML.
     */
    public function renderRest(
        FormInterface $form,
        array $options = []
    ): string;
}
