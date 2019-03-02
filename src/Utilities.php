<?php

namespace Log1x\SageDirectives;

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
            ->map(function($item) {
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
        return str_replace("'", '', $expression);
    }
}
