<?php

/**
 * @package translation
 * @link https://github.com/bayfrontmedia/translation
 * @author John Robinson <john@bayfrontmedia.com>
 * @copyright 2020 Bayfront Media
 */

namespace Bayfront\Translation\Adapters;

use Bayfront\Translation\AdapterInterface;

class Local implements AdapterInterface
{

    protected $root;

    public function __construct(string $root = '')
    {
        $this->root = '/' . trim($root, '/'); // Trim slashes
    }

    public function read(string $locale, string $id): array
    {

        $read = require($this->root . '/' . $locale . '/' . $id . '.php');

        if (is_array($read)) {
            return $read;
        }

        return [];

    }

}