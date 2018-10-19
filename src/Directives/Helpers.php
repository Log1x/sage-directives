<?php

namespace App\Directives;
use App\Util;

return [

    /*
    |--------------------------------------------------------------------------
    | Helper Directives
    |--------------------------------------------------------------------------
    |
    | Simple helper directives for various functions used in views.
    |
    */

    /** Create @asset() Blade directive */
    'asset' => function ($asset) {
        return "<?= App\\asset_path({$asset}); ?>";
    },

    /** Create @condition() Blade directive */
    'condition' => function ($expression) {
        [$condition, $value] = Util::Args($expression);
        return "<?php if ($condition) { echo $value; } ?>";
    },

    /** Create @global() Blade directive */
    'global' => function ($expression) {
        return "<?php global {$expression}; ?>";
    },

    /** Create @set() Blade directive */
    'set' => function ($expression) {
        [$name, $value] = Util::Args($expression);
        return "<?php $name = $value; ?>";
    },

    /** Create @unset() Blade directive */
    'unset' => function ($expression) {
        $expression = Util::Args($expression);
        return "<?php unset {$expression}; ?>";
    },

    /** Create @extract() Blade directive */
    'extract' => function ($expression) {
        return "<?php @extract({$expression}); ?>";
    },

    /** Create @explode() Blade directive */
    'explode' => function ($expression) {
        [$delimiter, $string] = Util::Args($expression);
        return "<?= explode({$delimiter}, {$string}); ?>";
    },

    /** Create @implode() Blade directive */
    'implode' => function ($expression) {
        [$delimiter, $array] = Util::Args($expression);
        return "<?= implode({$delimiter}, {$array}); ?>";
    },
];
