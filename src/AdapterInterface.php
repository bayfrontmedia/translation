<?php

namespace Bayfront\Translation;

interface AdapterInterface
{

    public function read(string $locale, string $id): array;

}