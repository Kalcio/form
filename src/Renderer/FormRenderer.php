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

use Derafu\Form\Contract\FormFieldInterface;
use Derafu\Form\Contract\FormInterface;
use Derafu\Form\Contract\Renderer\FormRendererInterface;

final class FormRenderer implements FormRendererInterface
{
    /**
     * {@inheritDoc}
     */
    public function render(FormInterface $form, array $options = []): string
    {
        $fields = $form->getFields();

        return '<form></form>'; // TODO: Implement render() method.
    }

    /**
     * {@inheritDoc}
     */
    public function renderStart(
        FormInterface $form,
        array $options = []
    ): string {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function renderEnd(
        FormInterface $form,
        array $options = []
    ): string {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function renderLabel(
        FormFieldInterface $field,
        ?string $label = null,
        array $options = []
    ): string {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function renderErrors(
        FormFieldInterface $field,
        array $options = []
    ): string {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function renderWidget(
        FormFieldInterface $field,
        array $options = []
    ): string {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function renderHelp(
        FormFieldInterface $field,
        array $options = []
    ): string {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function renderRow(
        FormFieldInterface $field,
        array $options = []
    ): string {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function renderRest(
        FormInterface $form,
        array $options = []
    ): string {
        return '';
    }
}
