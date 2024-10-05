<?php

namespace App\Utils;

class Arrays
{
    public static function orderByStringKeys(array $array, bool $caseSensitive = true): array
    {
        uksort($array, static function (string $a, string $b) use ($caseSensitive) {
            return $caseSensitive ? strcmp($a, $b) : strcasecmp($a, $b);
        });
        return $array;
    }

    public static function prefixKeys(array $array, string $prefix): array
    {
        $prefixed = [];
        foreach ($array as $key => $value) {
            $prefixed[$prefix . $key] = $value;
        }
        return $prefixed;
    }

    public static function flatten(array $array, bool $preserveKeys = false): array
    {
        $result = [];
        $callback = $preserveKeys
            ? static function ($value, $key) use (&$result): void {
                $result[$key] = $value;
            }
            : static function ($value) use (&$result): void {
                $result[] = $value;
            };
        array_walk_recursive($array, $callback);
        return $result;
    }
}
