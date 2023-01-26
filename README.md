## Translation

A PHP translation library utilizing multiple language storage options.

- [License](#license)
- [Author](#author)
- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)

## License

This project is open source and available under the [MIT License](LICENSE).

## Author

<img src="https://cdn1.onbayfront.com/bfm/brand/bfm-logo.svg" alt="Bayfront Media" width="250" />

- [Bayfront Media homepage](https://www.bayfrontmedia.com?utm_source=github&amp;utm_medium=direct)
- [Bayfront Media GitHub](https://github.com/bayfrontmedia)

## Requirements

* PHP `^8.0`
* JSON PHP extension
* PDO PHP extension

## Installation

```
composer require bayfrontmedia/translation
```

## Usage

**NOTE:** It is up to you to populate the translation data, no matter where it is stored.
This library simply reads the existing data from its source.

### Storage adapter

A `Bayfront\Translation\ApadpterInterface` must be passed to the `Bayfront\Translation\Translate` constructor.
There are a variety of storage adapters available, each with their own required configuration.

**Defined array**

The defined array adapter allows you to use a predefined array containing all of your translations.

```
use Bayfront\Translation\Adapters\DefinedArray;

$adapter = new DefinedArray([
    'en' => [ // Locale
        'dashboard' => [ // ID
            'title' => 'Account dashboard',
            'greeting' => 'Welcome back, {{name}}'
        ]
    ]
]);
```

**Local**

The local adapter allows you to use local native PHP files containing all of your translation arrays.

```
use Bayfront\Translation\Adapters\Local;

$adapter = new Local('/root_path');
```

The file structure from the root path should be:

```
/root_path
    /locale
        /id.php
```

For example, if the locale is set as `en`, the method `say('dashboard.greeting')` will search for the file `/root_path/en/dashboard.php`, and the array key `greeting`.

Example `dashboard.php`:

```
<?php

return [
    'title' => 'Account dashboard',
    'greeting' => 'Welcome back, {{name}}'
];
```

**PDO**

The PDO adapter allows you to use a `\PDO` instance for language retrieval from a database, and may throw a `Bayfront\Translation\AdapterException` exception in its constructor.

To create a compatible table, execute the following statement:

```
CREATE TABLE IF NOT EXISTS table_name (
    `locale` varchar(80) NOT NULL, 
    `id` varchar(255) NOT NULL, 
    `contents` longtext NOT NULL, 
    UNIQUE KEY unique_index(locale,id))
```

This table structure ensures only one row exists with a matching `locale` and `id`.
The `contents` column should contain a JSON encoded array.

The PDO adapter will create/use a table named "translations" unless otherwise specified in the constructor.

```
use Bayfront\Translation\Adapters\PDO;

try {

    $adapter = new PDO($dbh, 'table_name');

} catch (AdapterException $e) {
    die($e->getMessage());
}
```

### Start using Translation

Once your adapter has been created, it can be used with Translation. 
In addition, a string defining the locale should be passed to the constructor.

The last argument is `true` by default. 
When `true`, if a translation is not found and `$default = NULL`, the original string is returned. 
If `false`, a `Bayfront\Translation\TranslationException` is thrown.

```
use Bayfront\Translation\Translate;

$translate = new Translate($adapter, 'en', true);
```

### Public methods

- [getLocale](#getlocale)
- [setLocale](#setlocale)
- [getTranslations](#gettranslations)
- [addTranslations](#addtranslations)
- [get](#get)
- [say](#say)
- [replace](#replace)
- [replaceAll](#replaceall)

<hr />

### getLocale

**Description:**

Get locale.

**Parameters:**

- None

**Returns:**

- (string)

**Example:**

```
echo $translate->getLocale();
```

<hr />

### setLocale

**Description:**

Set locale.

**Parameters:**

- `$locale` (string)

**Returns:**

- (self)

**Example:**

```
$translate->setLocale('es');
```

<hr />

### getTranslations

**Description:**

Return array of all known translations.

Translations are only "known" once their ID has been used, or they have been added via the [addTranslations](#addtranslations) method.

**Parameters:**

- None

**Returns:**

- (array)

**Example:**

```
$translations = $translate->getTranslations();
```

<hr />

### addTranslations

**Description:**

Add translations to the known translations for the current request only.

**Parameters:**

- `$translations` (array)

**Returns:**

- (self)

**Example:**

```
$translate->addTranslations([
    'en' => [ // Locale
        'dashboard' => [ // ID
            'title' => 'New account dashboard'
        ]
    ],
    'es' => [
        'dashboard' => [
            'greeting' => 'Bienvenidos, {{name}}'
        ]
    ]
]);
```

<hr />

### get

**Description:**

Return the translation for a given string.

The string format is: `id.key`. 
Keys are in array dot notation, so they can be as deeply nested as needed.

Replacement variables should be surrounded by `{{ }}` in the original string.

If a translation is not found and `$default = NULL`, either the original string is returned, or a `TranslationException` is thrown, depending on the setting chosen in the constructor.

**Parameters:**

- `$string` (string)
- `$replacements = []` (array)
- `$default = NULL` (mixed): Default value to return if translation is not found

**Returns:**

- (mixed)

**Throws:**

- `Bayfront\Translation\TranslationException`

**Example:**

```
$title = $translate->get('dashboard.title');

// Example with replacements

$greeting = $translate->get('dashboard.greeting', [
    'name' => 'John'
]);
```

<hr />

### say

**Description:**

Echos the translation for a given string.

**Parameters:**

- `$string` (string)
- `$replacements = []` (array)
- `$default = NULL` (mixed): Default value to return if translation is not found

**Returns:**

- (void)

**Throws:**

- `Bayfront\Translation\TranslationException`

**Example:**

```
$translate->say('dashboard.title');
```

<hr />

### replace

**Description:**

Replace case-sensitive values in a string.

**Parameters:**

- `$string` (string): Original string
- `$replacements = []` (array): Array of values and replacements

**Returns:**

- (string)

**Example:**

```
$title = $translate->get('dashboard.title');

echo $translate->replace($title, [
    'dashboard' => 'homepage'
]);
```

<hr />

### replaceAll

**Description:**

Replace multiple case-sensitive values in a string with a single replacement.

**Parameters:**

- `$string` (string): Original string
- `$values` (array): Array of values to replace
- `$replacement` (string)

**Returns:**

- (string)

**Example:**

```
echo $translate->replaceAll($user_comment, [
    'array',
    'of',
    'bad',
    'words'
], '**CENSORED**');
```