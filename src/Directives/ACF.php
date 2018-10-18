<?php

namespace App\Directives;
use App\Util;

return [

    /*
    |--------------------------------------------------------------------------
    | ACF Directives
    |--------------------------------------------------------------------------
    |
    | Directives specific to Advance Custom Fields.
    |
    */

    /** Create @fields() Blade directive */
    'fields' => function ($expression) {
        return "<?php if (have_rows({$expression})) : while (have_rows({$expression})) : the_row(); ?>";
    },

    /** Create @endFields Blade directive */
    'endFields' => function () {
        return "<?php endwhile; endif; ?>";
    },

    /** Create @field() Blade directive */
    'field' => function ($expression) {
        if (count(Util::Args($expression)) > 1) {
            [$key, $value] = Util::Args($expression);
            return "<?= get_field({$key})[{$value}]; ?>";
        }

        return "<?= get_field({$expression}); ?>";
    },

    /** Create @getField() Blade directive */
    'getField' => function ($expression) {
        return "<?php return get_field({$expression}); ?>";
    },

    /** Create @hasField() Blade directive */
    'hasField' => function ($expression) {
        if (count(Util::Args($expression)) > 1) {
            [$key, $value] = Util::Args($expression);
            return "<?php if (get_field({$key})[{$value}]) : ?>";
        }

        return "<?php if (get_field({$expression})) : ?>";
    },

    /** Create @isField() Blade directive */
    'isField' => function ($expression) {
        [$name, $value] = Util::Args($expression);
        return "<?php if (get_field({$name}) == {$value}): ?>";
    },

    /** Create @endField Blade directive */
    'endField' => function () {
        return "<?php endif; ?>";
    },

    /** Create @sub() Blade directive */
    'sub' => function ($expression) {
        if (count(Util::Args($expression)) > 1) {
            [$key, $value] = Util::Args($expression);
            return "<?= get_sub_field({$key})[{$value}]; ?>";
        }

        return "<?= get_sub_field({$expression}); ?>";
    },

    /** Create @getSub() Blade directive */
    'getSub' => function ($expression) {
        return "<?php return get_sub_field({$expression}); ?>";
    },

    /** Create @hasSub() Blade directive */
    'hasSub' => function ($expression) {
        if (count(Util::Args($expression)) > 1) {
            [$key, $value] = Util::Args($expression);
            return "<?php if (get_sub_field({$key})[{$value}]) : ?>";
        }

        return "<?php if (get_sub_field({$expression})) : ?>";
    },

    /** Create @isSub() Blade directive */
    'isSub' => function ($expression) {
        [$name, $value] = Util::Args($expression);
        return "<?php if (get_sub_field({$name}) == {$value}) : ?>";
    },

    /** Create @endSub Blade directive */
    'endSub' => function () {
        return "<?php endif; ?>";
    },

    /** Create @layouts() Blade directive */
    'layouts' => function ($expression) {
        return "<?php while (have_rows({$expression})) : the_row(); ?>";
    },

    /** Create @endLayouts Blade directive */
    'endLayouts' => function () {
        return "<?php endwhile; ?>";
    },

    /** Create @layout() Blade directive */
    'layout' => function ($expression) {
        return "<?php if (get_row_layout() == {$expression}) : ?>";
    },

    /** Create @endLayout Blade directive */
    'endLayout' => function () {
        return "<?php endif; ?>";
    },

    /** Create @group() Blade directive */
    'group' => function ($expression) {
        return "<?php if (have_rows({$expression})) : while (have_rows({$expression})) : the_row(); ?>";
    },

    /** Create @endGroup Blade directive */
    'endGroup' => function () {
        return "<?php endwhile; endif; ?>";
    },

    /** Create @option () Blade directive */
    'option' => function ($expression) {
        return "<?= get_field({$expression}, 'option'); ?>";
    },

    /** Create @hasOption () Blade directive */
    'hasOption' => function ($expression) {
        return "<?php if (get_field({$expression}, 'option')) : ?>";
    },

    /** Create @endOption Blade directive */
    'endOption' => function () {
        return "<?php endif; ?>";
    },

    /** Create @options() Blade directive */
    'options' => function ($expression) {
        return "<?php if (have_rows({$expression}, 'options')) : while (have_rows({$expression}, 'options')) : the_row(); ?>";
    },

    /** Create @endOptions Blade directive */
    'endOptions' => function () {
        return "<?php endwhile; endif; ?>";
    },

    /** Create @endFields Blade directive */
    'endFields' => function () {
        return "<?php endwhile; endif; ?>";
    },
];
