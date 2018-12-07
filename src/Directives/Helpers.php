<?php

namespace App\Directives;

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
    'asset' => function ($expression) {
        return "<?= App\\asset_path({$expression}); ?>";
    },

    /** Create @condition() Blade directive */
    'condition' => function ($expression) {
        if (str_contains($expression, ',')) {
            $expression = Util::parse($expression);
            return "<?php if ({$expression->get(0)}) : ?>".
                   "<?= {$expression->get(1)} ?>".
                   "<?php endif; ?>";
        }
    },

    /** Create @global() Blade directive */
    'global' => function ($expression) {
        return "<?php global {$expression}; ?>";
    },

    /** Create @set() Blade directive */
    'set' => function ($expression) {
        if (str_contains($expression, ',')) {
            $expression = Util::parse($expression);
            return "<?php {$expression->get(0)} = {$expression->get(1)}; ?>";
        }
    },

    /** Create @unset() Blade directive */
    'unset' => function ($expression) {
        return "<?php unset({$expression}); ?>";
    },

    /** Create @extract() Blade directive */
    'extract' => function ($expression) {
        return "<?php extract({$expression}); ?>";
    },

    /** Create @explode() Blade directive */
    'explode' => function ($expression) {
        if (str_contains($expression, ',')) {
            $expression = Util::parse($expression);
            return "<?= explode({$expression->get(0)}, {$expression->get(1)}); ?>";
        }
    },

    /** Create @implode() Blade directive */
    'implode' => function ($expression) {
        if (str_contains($expression, ',')) {
            $expression = Util::parse($expression);
            return "<?= implode({$expression->get(0)}, {$expression->get(1)}); ?>";
        }
    },
];
