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
     * Wraps the passed string in single quotes if they are not already present.
     *
     * @param  string $value
     * @return string
     */
    public static function wrap($value)
    {
        $value = Str::start($value, "'");
        $value = Str::finish($value, "'");

        return $value;
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
     * Convert expression to a string.
     *
     * @param  mixed  $expression
     * @param  string $keys
     * @return string
     */
    public static function toString($expression, $keys = '')
    {
        if (! is_array($expression)) {
            return self::wrap($expression);
        }

        foreach ($expression as $key => $value) {
            $keys .= self::wrap($key) . ' => ' . self::wrap($value) .  ',';
        }

        $keys = trim(Str::replaceLast(',', '', $keys));

        return "[{$keys}]";
    }

    /**
     * A sad attempt to check if an expression passed is actually an array.
     * Unfortunately, ANY expression passed to Blade is a string until it is
     * returned and parsed through the compiler. Even attempting to manually
     * convert the string to an array will then cause a string to array exception
     * during compiled time– so regardless, it must then be converted back to a
     * string.
     *
     * @see Utilities::toString()
     *
     * The only other way to approach this would be a clever `preg_match_all()`
     * or `eval()` which isn't happening. I've poached every other Blade directives
     * library and none have a viable solution.
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
