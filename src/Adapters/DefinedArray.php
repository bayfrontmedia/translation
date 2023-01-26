<?php

namespace Bayfront\Translation\Adapters;

use Bayfront\Translation\AdapterInterface;

class DefinedArray implements AdapterInterface
{

    protected array $translations;

    public function __construct(array $translations)
    {
        $this->translations = $translations;
    }

    public function read(string $locale, string $id): array
    {

        if (isset($this->translations[$locale][$id])) {
            return $this->translations[$locale][$id];
        }

        return [];

    }

}