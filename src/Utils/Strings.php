<?php

namespace App\Utils;

use App\Exception\InvalidArgumentException;
use Nette\Utils\Strings as CoreStrings;

class Strings extends CoreStrings
{
    public static function isNullOrWhiteSpace(?string $subject): bool
    {
        return $subject === null || $subject === '' || trim($subject) === '';
    }

    public static function isEmptyOrWhiteSpace(string $subject): bool
    {
        return $subject === '' || trim($subject) === '';
    }

    /**
     * @param array<string> $list
     */
    public static function join(array $list, string $glue, ?string $and = null): string
    {
        if (empty($list)) {
            throw new InvalidArgumentException('List is empty.');
        }
        if ($and === null) {
            return implode($glue, $list);
        }
        $last = array_pop($list);
        if (!empty($list)) {
            return implode($glue, $list) . $and . $last;
        }
        return $last;
    }

    public static function varToString(mixed $var): string
    {
        if (is_object($var)) {
            return sprintf('an object of type %s', $var::class);
        }
        if (is_array($var)) {
            $a = [];
            foreach ($var as $k => $v) {
                $a[] = sprintf('%s => ...', $k);
            }

            return sprintf('an array ([%s])', mb_substr(implode(', ', $a), 0, 255));
        }
        if (is_resource($var)) {
            return sprintf('a resource (%s)', get_resource_type($var));
        }
        if (is_callable($var)) {
            return 'a callable';
        }
        if (null === $var) {
            return 'null';
        }
        if (false === $var) {
            return 'a boolean value (false)';
        }
        if (true === $var) {
            return 'a boolean value (true)';
        }
        if (is_string($var) && static::isNullOrWhiteSpace($var)) {
            return 'an empty string';
        }
        if (is_string($var)) {
            return sprintf('a string ("%s%s")', mb_substr($var, 0, 255), mb_strlen($var) > 255 ? '...' : '');
        }
        if (is_numeric($var)) {
            return sprintf('a number (%s)', $var);
        }
        return (string)$var;
    }

    public static function obfuscateEmail(string $email): string
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email address.');
        }
        $parts = explode('@', $email);
        [$name, $domain] = $parts;
        if (!str_contains($domain, '.')) {
            throw new InvalidArgumentException('Invalid email address.');
        }
        [$domain, $topLevelDomain] = explode('.', $domain);
        $obfuscate = static function (string $part): string {
            $chars = mb_str_split($part);
            $charsCount = count($chars);
            $dotCharsCount = count(array_filter($chars, static fn (string $char) => $char === '.'));
            $nonDotCharsCount = $charsCount - $dotCharsCount;
            if ($nonDotCharsCount <= 4) {
                return str_repeat('*', $charsCount);
            }
            $output = '';
            for ($i = 0; $i < $charsCount; $i++) {
                if ($i === 0 || $i === $charsCount - 1 || $chars[$i] === '.') {
                    $output .= $chars[$i];
                    continue;
                }
                $output .= '*';
            }
            return $output;
        };
        return $obfuscate($name) . '@' . $obfuscate($domain) . '.' . $topLevelDomain;
    }
}
