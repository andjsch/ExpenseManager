<?php

namespace App\Dictionaries;

abstract class Dictionary
{
    public static string $labelKey = 'value';

    abstract public static function items(): array;

    public static function label(array $item): string
    {
        return $item[static::$labelKey];
    }
}