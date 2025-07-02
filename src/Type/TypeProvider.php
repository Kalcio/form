<?php

declare(strict_types=1);

/**
 * Derafu: Form - Declarative Forms, Seamless Rendering.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.dev>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Form\Type;

use Derafu\Form\Contract\Type\TypeProviderInterface;

/**
 * Provides types.
 *
 * The order of the types is important if the type resolver is used. Types
 * that are more specific should be listed first.
 *
 * This provider is just an example, you MUST create your own provider or inject
 * directly the types to the type registry. The reason for this is that the
 * types listes here is just for demonstration purposes. It's included types
 * that you probably don't want to use in your project.
 */
final class TypeProvider implements TypeProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getTypes(): array
    {
        return [
            // Guessable types.
            EmailType::class,
            BooleanType::class,
            IntegerType::class, // Integer is more specific than Float.
            FloatType::class,   // Float must be listed after Integer if type resolver is used.
            DatetimeType::class,
            DateType::class,
            TimeType::class,
            WeekType::class,
            MonthType::class,
            UuidType::class,
            Ipv4Type::class,
            Ipv6Type::class,
            UrlType::class,
            UriType::class,
            ColorType::class,

            // Other types (not guessables).
            ChoiceType::class,
            TextareaType::class,

            // Last type, because it's a generic type.
            TextType::class,
        ];
    }
}
