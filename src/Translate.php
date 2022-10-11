<?php

/**
 * @package translation
 * @link https://github.com/bayfrontmedia/translation
 * @author John Robinson <john@bayfrontmedia.com>
 * @copyright 2020 Bayfront Media
 */

namespace Bayfront\Translation;

use Bayfront\ArrayHelpers\Arr;

class Translate
{

    protected $storage;

    protected $locale;

    protected $return_original;

    public function __construct(AdapterInterface $storage, string $locale, bool $return_original = true)
    {

        $this->storage = $storage;

        $this->locale = $locale;

        $this->return_original = $return_original;

    }

    protected $translations = [];

    protected $added_translations = [];

    /**
     * Get locale.
     *
     * @return string
     */

    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * Set locale.
     *
     * @param string $locale
     *
     * @return self
     */

    public function setLocale(string $locale): self
    {

        $this->locale = $locale;

        return $this;

    }

    /**
     * Return array of all known translations.
     *
     * Translations are only "known" once their ID has been used,
     * or they have been added via the addTranslations method.
     *
     * @return array
     */

    public function getTranslations(): array
    {
        return array_replace_recursive($this->translations, $this->added_translations);
    }

    /**
     * Add translations to the known translations.
     *
     * @param array $translations
     *
     * @return self
     */

    public function addTranslations(array $translations): self
    {

        $this->added_translations = array_replace_recursive($this->added_translations, $translations);

        return $this;

    }

    /**
     * Return the translation for a given string.
     *
     * The string format is: id.key
     *
     * If a translation is not found and $default = NULL, the original string is returned.
     *
     * @param string $string
     * @param array $replacements
     * @param mixed $default (Default value to return if translation is not found)
     *
     * @return mixed
     *
     * @throws TranslationException
     */

    public function get(string $string, array $replacements = [], $default = NULL)
    {

        $exp = explode('.', $string, 2); // $exp[0] = ID / $exp[1] = array key in dot notation

        if (!isset($exp[1])) { // Invalid translation string
            return $default;
        }

        if (!isset($this->getTranslations()[$exp[0]])) { // If this ID has not yet been read

            $this->translations[$this->locale][$exp[0]] = $this->storage->read($this->locale, $exp[0]);

        }

        $translation = Arr::get($this->getTranslations()[$this->locale], $string, $default);

        if (NULL === $translation) { // If a translation does not exist and $default = NULL

            if (true === $this->return_original) {

                return $string; // Return the original string

            }

            throw new TranslationException('Translation not found for locale (' . $this->locale . '): ' . $string);

        }

        if (is_string($translation)) {

            foreach ($replacements as $k => $v) {

                $translation = str_replace('{{' . $k . '}}', $v, $translation);

            }

        }

        return $translation;

    }

    /**
     * Echos the translation for a given string.
     *
     * @param string $string
     * @param array $replacements
     * @param mixed $default
     *
     * @return void
     *
     * @throws TranslationException
     */

    public function say(string $string, array $replacements = [], $default = NULL): void
    {
        echo $this->get($string, $replacements, $default);
    }

    /**
     * Replace case-sensitive values in a string.
     *
     * @param string $string (Original string)
     * @param array $replacements (Array of values and replacements)
     *
     * @return string
     */

    public function replace(string $string, array $replacements = []): string
    {

        foreach ($replacements as $k => $v) {

            $string = str_replace($k, $v, $string);

        }

        return $string;

    }

    /**
     * Replace multiple case-sensitive values in a string with a single replacement.
     *
     * @param string $string (Original string)
     * @param array $values (Array of values to replace)
     * @param string $replacement
     *
     * @return string
     */

    public function replaceAll(string $string, array $values, string $replacement): string
    {
        return str_replace($values, $replacement, $string);
    }

}