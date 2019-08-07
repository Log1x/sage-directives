<?php

namespace Log1x\SageDirectives;

use Illuminate\Support\Str;

class Util
{
    /**
     * Parse expression passed to directive.
     *
     * @param  string $expression
     * @return \Illuminate\Support\Collection
     */
    public static function parse($expression)
    {
        return collect(explode(',', $expression))
            ->map(function ($item) {
                return trim($item);
            });
    }

    /**
     * Strip single quotes from expression.
     *
     * @param  string $expression
     * @return string
     */
    public static function strip($expression)
    {
        return str_replace(["'", "\""], '', $expression);
    }

    /**
     * Dives for an ACF field or sub field and returns the value if it exists.
     *
     * @param  string  $field
     * @param  integer $id
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
     * A sad attempt to check if an expression passed is actually an array.
     * The only other way to approach this would be a clever `preg_replace()`
     * or `eval()` which isn't happening.
     *
     * @param  mixed $expression
     * @return boolean
     */
    public static function isArray($expression)
    {
        $expression = self::strip($expression);

        return Str::startsWith($expression, '[') && Str::endsWith($expression, ']');
    }
}
