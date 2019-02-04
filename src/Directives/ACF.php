<?php

namespace App\Directives;

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
        if (str_contains($expression, ',')) {
            $expression = Util::parse($expression);

            return "<?php if (have_rows({$expression->get(0)}, {$expression->get(1)})) : ?>".
                   "<?php while (have_rows({$expression->get(0)}, {$expression->get(1)})) : the_row(); ?>";
        }

        return "<?php if (have_rows({$expression})) : ?>".
               "<?php while (have_rows({$expression})) : the_row(); ?>";
    },

    /** Create @endfields Blade directive */
    'endfields' => function () {
        return "<?php endwhile; endif; ?>";
    },

    /** Create @field() Blade directive */
    'field' => function ($expression) {
        if (str_contains($expression, ',')) {
            $expression = Util::parse($expression);

            if (!empty($expression->get(2)) && !is_string($expression->get(2))) {
                return "<?= get_field({$expression->get(0)}, {$expression->get(2)})[{$expression->get(1)}]; ?>";
            }

            if (!is_string($expression->get(1))) {
                return "<?= get_field({$expression->get(0)}, {$expression->get(1)}); ?>";
            }

            return "<?= get_field({$expression->get(0)})[{$expression->get(1)}]; ?>";
        }

        return "<?= get_field({$expression}); ?>";
    },

    /** Create @hasfield() Blade directive */
    'hasfield' => function ($expression) {
        if (str_contains($expression, ',')) {
            $expression = Util::parse($expression);

            if (!empty($expression->get(2)) && !is_string($expression->get(2))) {
                return "<?php if (get_field({$expression->get(0)}, {$expression->get(2)})[{$expression->get(1)}]) : ?>";
            }

            if (!is_string($expression->get(1))) {
                return "<?php if (get_field({$expression->get(0)}, {$expression->get(1)})) : ?>";
            }

            return "<?php if (get_field({$expression->get(0)})[{$expression->get(1)}]) : ?>";
        }

        return "<?php if (get_field({$expression})) : ?>";
    },

    /** Create @isfield() Blade directive */
    'isfield' => function ($expression) {
        if (str_contains($expression, ',')) {
            $expression = Util::parse($expression);

            if (!empty($expression->get(2)) && !is_string($expression->get(2))) {
                return "<?php if (get_field({$expression->get(0)}, {$expression->get(2)}) == {$expression->get(1)}) : ?>";
            }

            return "<?php if (get_field({$expression->get(0)}) == {$expression->get(1)}) : ?>";
        }
    },

    /** Create @endfield Blade directive */
    'endfield' => function () {
        return "<?php endif; ?>";
    },

    /** Create @sub() Blade directive */
    'sub' => function ($expression) {
        if (str_contains($expression, ',')) {
            $expression = Util::parse($expression);

            return "<?= get_sub_field({$expression->get(0)})[{$expression->get(1)}]; ?>";
        }

        return "<?= get_sub_field({$expression}); ?>";
    },

    /** Create @hassub() Blade directive */
    'hassub' => function ($expression) {
        if (str_contains($expression, ',')) {
            $expression = Util::parse($expression);

            return "<?php if (get_sub_field({$expression->get(0)})[{$expression->get(1)}]) : ?>";
        }

        return "<?php if (get_sub_field({$expression})) : ?>";
    },

    /** Create @issub() Blade directive */
    'issub' => function ($expression) {
        if (str_contains($expression, ',')) {
            $expression = Util::parse($expression);

            return "<?php if (get_sub_field({$expression->get(0)}) == {$expression->get(1)}) : ?>";
        }
    },

    /** Create @endsub Blade directive */
    'endsub' => function () {
        return "<?php endif; ?>";
    },

    /** Create @layouts() Blade directive */
    'layouts' => function ($expression) {
        if (str_contains($expression, ',')) {
            $expression = Util::parse($expression);

            return "<?php if (have_rows({$expression->get(0)}, {$expression->get(1)})) : ?>".
                   "<?php while (have_rows({$expression->get(0)}, {$expression->get(1)})) : the_row(); ?>";
        }

        return "<?php if (have_rows({$expression})) : ?>".
               "<?php while (have_rows({$expression})) : the_row(); ?>";
    },

    /** Create @endlayouts Blade directive */
    'endlayouts' => function () {
        return "<?php endwhile; endif; ?>";
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
        if (str_contains($expression, ',')) {
            $expression = Util::parse($expression);

            return "<?php if (have_rows({$expression->get(0)}, {$expression->get(1)})) : ?>".
                   "<?php while (have_rows({$expression->get(0)}, {$expression->get(1)})) : the_row(); ?>";
        }

        return "<?php if (have_rows({$expression})) : ?>".
               "<?php while (have_rows({$expression})) : the_row(); ?>";
    },

    /** Create @endgroup Blade directive */
    'endgroup' => function () {
        return "<?php endwhile; endif; ?>";
    },

    /** Create @option() Blade directive */
    'option' => function ($expression) {
        if (str_contains($expression, ',')) {
            $expression = Util::parse($expression);

            return "<?= get_field({$expression->get(0)}, 'option')[{$expression->get(1)}]; ?>";
        }

        return "<?= get_field({$expression}, 'option'); ?>";
    },

    /** Create @hasoption() Blade directive */
    'hasoption' => function ($expression) {
        return "<?php if (get_field({$expression}, 'option')) : ?>";
    },

    /** Create @isoption() Blade directive */
    'isoption' => function ($expression) {
        if (str_contains($expression, ',')) {
            $expression = Util::parse($expression);

            return "<?php if (get_field({$expression->get(0)}, 'option') == {$expression->get(1)}) : ?>";
        }
    },

    /** Create @endoption Blade directive */
    'endoption' => function () {
        return "<?php endif; ?>";
    },

    /** Create @options() Blade directive */
    'options' => function ($expression) {
        return "<?php if (have_rows({$expression}, 'option')) : ?>".
               "<?php while (have_rows({$expression}, 'option')) : the_row(); ?>";
    },

    /** Create @endoptions Blade directive */
    'endoptions' => function () {
        return "<?php endwhile; endif; ?>";
    },
];
