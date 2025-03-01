<?php

declare(strict_types=1);

/**
 * Derafu: Form - Form Builder with Easy Definition and Layouts.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\ExamplesForm;

use Derafu\Form\Contract\FormInterface;
use Derafu\Form\FormFactory;
use Derafu\Routing\Exception\RouteNotFoundException;

/**
 * Example model representing a form example.
 *
 * This class handles loading and processing form example definitions stored as
 * JSON files, providing methods to access their metadata, content, and create
 * form instances from them.
 */
final class Example
{
    /**
     * Directory containing the example JSON files.
     */
    private const EXAMPLES_DIR = __DIR__ . '/../../tests/fixtures/examples';

    /**
     * Cached example data.
     *
     * @var array
     */
    private array $data;

    /**
     * Constructor.
     *
     * @param string $file Path to the example JSON file.
     */
    public function __construct(private readonly string $file)
    {
    }

    /**
     * Get the example's unique identifier.
     *
     * @return string The example ID.
     */
    public function getId(): string
    {
        return substr(basename($this->file), 0, -5);
    }

    /**
     * Get the example's numerical order.
     *
     * Examples are numbered with a three-digit prefix (e.g., 001, 002).
     *
     * @return int The example number.
     */
    public function getNumber(): int
    {
        return (int) substr($this->getId(), 0, 3);
    }

    /**
     * Get the example's code (slug).
     *
     * This is the part of the ID after the number prefix.
     *
     * @return string The example code.
     */
    public function getCode(): string
    {
        return substr($this->getId(), 4);
    }

    /**
     * Get the example's display name.
     *
     * Falls back to the code if no name is defined in metadata.
     *
     * @return string The example name.
     */
    public function getName(): string
    {
        return $this->getMeta()['name'] ?? $this->getCode();
    }

    /**
     * Get the example's description.
     *
     * @return string|null The description or `null` if not defined.
     */
    public function getDescription(): ?string
    {
        return $this->getMeta()['description'] ?? null;
    }

    /**
     * Get the example's tags.
     *
     * @return array List of tags.
     */
    public function getTags(): array
    {
        return $this->getMeta()['tags'] ?? [];
    }

    /**
     * Get all metadata for the example.
     *
     * @return array Metadata as an associative array.
     */
    public function getMeta(): array
    {
        return $this->getData()['meta'] ?? [];
    }

    /**
     * Create a form instance from the example definition.
     *
     * @return FormInterface The instantiated form.
     */
    public function getForm(): FormInterface
    {
        return FormFactory::create($this->getData()['form']);
    }

    /**
     * Get the raw data from the example JSON file.
     *
     * Results are cached after the first call.
     *
     * @return array The raw example data.
     */
    public function getData(): array
    {
        if (!isset($this->data)) {
            $this->data = json_decode(file_get_contents($this->file), true);
        }

        return $this->data;
    }

    /**
     * Get the JSON Forms definition for this example.
     *
     * This transforms the internal form representation to the JSON Forms format
     * that can be rendered by JSON Forms libraries.
     *
     * @return array JSON Forms definition (schema, uischema, data).
     */
    public function getJsonFormsDefinition(): array
    {
        $data = $this->getData();

        if ($data['meta']['normalize'] ?? true) {
            // TODO: Change to a JSON Forms transformer.
            $jsonForms = $this->getForm()->toArray();
        } else {
            $jsonForms = [];
            foreach (['schema', 'uischema', 'data'] as $key) {
                if (!empty($data['form'][$key])) {
                    $jsonForms[$key] = $data['form'][$key];
                }
            }
        }

        return $jsonForms;
    }

    /**
     * Get the previous example in numerical order.
     *
     * @return Example|null The previous example or `null` if this is the first.
     */
    public function getPrevious(): ?Example
    {
        return self::findByNumber($this->getNumber() - 1);
    }

    /**
     * Get the next example in numerical order.
     *
     * @return Example|null The next example or `null` if this is the last.
     */
    public function getNext(): ?Example
    {
        return self::findByNumber($this->getNumber() + 1);
    }

    /**
     * Find an example by its code.
     *
     * @param string $id The example code to find.
     * @return self The found example.
     * @throws RouteNotFoundException If no example with the given code exists.
     */
    public static function find(string $id): self
    {
        $files = glob(sprintf(
            '%s/[0-9][0-9][0-9]_%s.json',
            self::EXAMPLES_DIR,
            $id
        ));

        if (empty($files)) {
            throw new RouteNotFoundException('/examples/' . $id);
        }

        return new self($files[0]);
    }

    /**
     * Find an example by its numerical order.
     *
     * @param int $number The example number to find.
     * @return self|null The found example or null if none exists.
     */
    public static function findByNumber(int $number): ?self
    {
        $files = glob(sprintf(
            '%s/%03d_*.json',
            self::EXAMPLES_DIR,
            $number
        ));

        if (empty($files)) {
            return null;
        }

        return new self($files[0]);
    }

    /**
     * Get all available examples.
     *
     * @return Example[] Array of all examples.
     */
    public static function all(): array
    {
        $files = glob(self::EXAMPLES_DIR . '/[0-9][0-9][0-9]_*.json');

        $examples = [];
        foreach ($files as $file) {
            $examples[] = new self($file);
        }

        return $examples;
    }
}
