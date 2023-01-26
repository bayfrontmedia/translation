<?php

namespace Bayfront\Translation\Adapters;

use Bayfront\Translation\AdapterInterface;

class Local implements AdapterInterface
{

    protected string $root;

    public function __construct(string $root = '')
    {
        $this->root = '/' . trim($root, '/'); // Trim slashes
    }

    public function read(string $locale, string $id): array
    {

        $file = $this->root . '/' . $locale . '/' . $id . '.php';

        if (file_exists($file)) {

            $read = require($file);

            if (is_array($read)) {
                return $read;
            }

        }

        return [];

    }

}