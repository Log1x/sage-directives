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

    /** Create @endfields Blade directive */
    'endfields' => function () {
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

    /** Create @getfield() Blade directive */
    'getfield' => function ($expression) {
        return "<?php return get_field({$expression}); ?>";
    },

    /** Create @hasfield() Blade directive */
    'hasfield' => function ($expression) {
        if (count(Util::Args($expression)) > 1) {
            [$key, $value] = Util::Args($expression);
            return "<?php if (get_field({$key})[{$value}]) : ?>";
        }

        return "<?php if (get_field({$expression})) : ?>";
    },

    /** Create @isfield() Blade directive */
    'isfield' => function ($expression) {
        [$name, $value] = Util::Args($expression);
        return "<?php if (get_field({$name}) == {$value}): ?>";
    },

    /** Create @endfield Blade directive */
    'endfield' => function () {
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

    /** Create @getsub() Blade directive */
    'getsub' => function ($expression) {
        return "<?php return get_sub_field({$expression}); ?>";
    },

    /** Create @hassub() Blade directive */
    'hassub' => function ($expression) {
        if (count(Util::Args($expression)) > 1) {
            [$key, $value] = Util::Args($expression);
            return "<?php if (get_sub_field({$key})[{$value}]) : ?>";
        }

        return "<?php if (get_sub_field({$expression})) : ?>";
    },

    /** Create @issub() Blade directive */
    'issub' => function ($expression) {
        [$name, $value] = Util::Args($expression);
        return "<?php if (get_sub_field({$name}) == {$value}) : ?>";
    },

    /** Create @endsub Blade directive */
    'endsub' => function () {
        return "<?php endif; ?>";
    },

    /** Create @layouts() Blade directive */
    'layouts' => function ($expression) {
        return "<?php while (have_rows({$expression})) : the_row(); ?>";
    },

    /** Create @endlayouts Blade directive */
    'endlayouts' => function () {
        return "<?php endwhile; ?>";
    },

    /** Create @layout() Blade directive */
    'layout' => function ($expression) {
        return "<?php if (get_row_layout() == {$expression}) : ?>";
    },

    /** Create @endlayout Blade directive */
    'endlayout' => function () {
        return "<?php endif; ?>";
    },

    /** Create @group() Blade directive */
    'group' => function ($expression) {
        return "<?php if (have_rows({$expression})) : while (have_rows({$expression})) : the_row(); ?>";
    },

    /** Create @endgroup Blade directive */
    'endgroup' => function () {
        return "<?php endwhile; endif; ?>";
    },

    /** Create @option() Blade directive */
    'option' => function ($expression) {
        return "<?= get_field({$expression}, 'option'); ?>";
    },

    /** Create @hasoption() Blade directive */
    'hasoption' => function ($expression) {
        return "<?php if (get_field({$expression}, 'option')) : ?>";
    },

    /** Create @endoption Blade directive */
    'endoption' => function () {
        return "<?php endif; ?>";
    },

    /** Create @options() Blade directive */
    'options' => function ($expression) {
        return "<?php if (have_rows({$expression}, 'options')) : while (have_rows({$expression}, 'options')) : the_row(); ?>";
    },

    /** Create @endoptions Blade directive */
    'endoptions' => function () {
        return "<?php endwhile; endif; ?>";
    },

    /** Create @endfields Blade directive */
    'endfields' => function () {
        return "<?php endwhile; endif; ?>";
    },
];
