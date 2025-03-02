<?php

declare(strict_types=1);

/**
 * Derafu: Form - Form Builder with Easy Definition and Layouts.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\TestsForm;

use Derafu\ExamplesForm\Example;
use Derafu\Form\Contract\ValueObject\FormInterface;
use Derafu\Form\FormFactory;
use Derafu\Form\ValueObject\Data;
use Derafu\Form\ValueObject\Form;
use Derafu\Form\ValueObject\Schema;
use Derafu\Form\ValueObject\UiSchema;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(FormFactory::class)]
#[CoversClass(Form::class)]
#[CoversClass(Schema::class)]
#[CoversClass(UiSchema::class)]
#[CoversClass(Data::class)]
class ExamplesTest extends TestCase
{
    #[DataProvider('provideExamples')]
    public function testCreateExamples(Example $example): void
    {
        $form = $example->getForm();
        $this->assertInstanceOf(FormInterface::class, $form);
    }

    public static function provideExamples(): array
    {
        $examples = [];

        foreach (Example::all() as $example) {
            $examples[$example->getId()] = [$example];
        }

        return $examples;
    }
}
