<?php

namespace Log1x\SageDirectives;

use Illuminate\Support\Str;

class Util
{
    /**
     * Parse expression passed to directive.
     *
     * @param  string  $expression
     * @param  int  $limit
     * @param  string  $delimiter
     * @return \Illuminate\Support\Collection
     */
    public static function parse($expression, $limit = PHP_INT_MAX, $delimiter = '__comma__')
    {
        $expression = preg_replace_callback('/\'(.*?)\'|"(.*?)"/', function ($matches) use ($delimiter) {
            return str_replace(',', $delimiter, $matches[0]);
        }, $expression);

        return collect(explode(',', $expression, $limit))
            ->map(function ($item) use ($delimiter) {
                $item = str_replace($delimiter, ',', $item);
                $item = trim($item);

                if (is_numeric($item)) {
                    return (int) $item;
                }

                return $item;
            });
    }

    /**
     * Determine if the string is a valid identifier.
     *
     * @param  string  $expression
     * @return bool
     */
    public static function isIdentifier($expression = null)
    {
        return ! empty($expression) && (is_numeric($expression) || Str::startsWith($expression, '$') || Str::startsWith($expression, 'get_'));
    }

    /**
     * Strip single quotes from expression.
     *
     * @param  string  $expression
     * @return string
     */
    public static function strip($expression = null)
    {
        return ! empty($expression) ? str_replace(["'", '"'], '', $expression) : $expression;
    }

    /**
     * Wraps the passed string in single quotes if they are not already present.
     *
     * @param  string  $value
     * @return string
     */
    public static function wrap($value)
    {
        $value = Str::start($value, "'");
        $value = Str::finish($value, "'");

        return $value;
    }

    /**
     * Unwraps the passed string from the passed delimiter.
     *
     * @param  string  $value
     * @param  string  $delimiter
     * @return string
     */
    public static function unwrap($value, $delimiter = "'")
    {
        if (Str::startsWith($value, $delimiter)) {
            $value = Str::replaceFirst($delimiter, '', $value);
        }

        if (Str::endsWith($value, $delimiter)) {
            $value = Str::replaceLast($delimiter, '', $value);
        }

        return $value;
    }

    /**
     * Combine and clean a malformed array formed from a parsed expression.
     *
     * @param  array  $expression
     * @return string
     */
    public static function clean($expression)
    {
        return Util::unwrap(
            Util::toString($expression, true)
        );
    }

    /**
     * Dives for an ACF field or sub field and returns the value if it exists.
     *
     * @param  string  $field
     * @param  int  $id
     * @return mixed
     */
    public static function field($field, $id = null)
    {
        if (! function_exists('acf')) {
            return;
        }

        if (! empty(get_field($field, $id))) {
            return get_field($field, $id);
        }

        if (! empty(get_sub_field($field))) {
            return get_sub_field($field);
        }

        if (! empty(get_field($field, 'option'))) {
            return get_field($field, 'option');
        }

        return false;
    }

    /**
     * Convert expression to a string.
     *
     * @param  mixed  $expression
     * @param  bool  $single
     * @return string
     */
    public static function toString($expression, $single = false)
    {
        if (! is_array($expression)) {
            return self::wrap($expression);
        }

        $keys = '';

        foreach ($expression as $key => $value) {
            $keys .= $single ?
                self::wrap($value).',' :
                self::wrap($key).' => '.self::wrap($value).', ';
        }

        $keys = trim(Str::replaceLast(',', '', $keys));

        if (! $single) {
            $keys = Str::start($keys, '[');
            $keys = Str::finish($keys, ']');
        }

        return $keys;
    }

    /**
     * Determine if the expression looks like an array.
     *
     * @param  mixed  $expression
     * @return bool
     */
    public static function isArray($expression)
    {
        $expression = self::strip($expression);

        return Str::startsWith($expression, '[') && Str::endsWith($expression, ']');
    }
}
