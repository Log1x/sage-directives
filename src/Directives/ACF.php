<?php

namespace Log1x\SageDirectives;

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | ACF Directives
    |--------------------------------------------------------------------------
    |
    | Directives specific to Advance Custom Fields.
    |
    */

    /*
    |---------------------------------------------------------------------
    | @fields / @endfields
    |---------------------------------------------------------------------
    */

    'fields' => function ($expression) {
        if (Str::contains($expression, ',')) {
            $expression = Util::parse($expression);

            return "<?php if (have_rows({$expression->get(0)}, {$expression->get(1)})) : ?>" .
                   "<?php while (have_rows({$expression->get(0)}, {$expression->get(1)})) : the_row(); ?>";
        }

        return "<?php if (have_rows({$expression})) : ?>" .
               "<?php while (have_rows({$expression})) : the_row(); ?>";
    },

    'endfields' => function () {
        return "<?php endwhile; endif; ?>";
    },

    /*
    |---------------------------------------------------------------------
    | @field / @hasfield / @isfield / @endfield
    |---------------------------------------------------------------------
    */

    'field' => function ($expression) {
        if (Str::contains($expression, ',')) {
            $expression = Util::parse($expression);

            if (! empty($expression->get(2)) && ! is_string($expression->get(2))) {
                return "<?= get_field({$expression->get(0)}, {$expression->get(2)})[{$expression->get(1)}]; ?>";
            }

            if (! is_string($expression->get(1))) {
                return "<?= get_field({$expression->get(0)}, {$expression->get(1)}); ?>";
            }

            return "<?= get_field({$expression->get(0)})[{$expression->get(1)}]; ?>";
        }

        return "<?= get_field({$expression}); ?>";
    },

    'hasfield' => function ($expression) {
        if (Str::contains($expression, ',')) {
            $expression = Util::parse($expression);

            if (! empty($expression->get(2)) && ! is_string($expression->get(2))) {
                return "<?php if (get_field({$expression->get(0)}, {$expression->get(2)})[{$expression->get(1)}]) : ?>";
            }

            if (! is_string($expression->get(1))) {
                return "<?php if (get_field({$expression->get(0)}, {$expression->get(1)})) : ?>";
            }

            return "<?php if (get_field({$expression->get(0)})[{$expression->get(1)}]) : ?>";
        }

        return "<?php if (get_field({$expression})) : ?>";
    },

    'isfield' => function ($expression) {
        if (Str::contains($expression, ',')) {
            $expression = Util::parse($expression);

            if (! empty($expression->get(3)) && ! is_string($expression->get(2))) {
                return "<?php if (get_field({$expression->get(0)}, {$expression->get(3)})[{$expression->get(1)}] === {$expression->get(2)}) : ?>"; // phpcs:ignore
            }

            if (! empty($expression->get(2)) && ! is_string($expression->get(2))) {
                return "<?php if (get_field({$expression->get(0)}, {$expression->get(2)}) === {$expression->get(1)}) : ?>"; // phpcs:ignore
            }

            if (! empty($expression->get(2)) && is_string($expression->get(2))) {
                return "<?php if (get_field({$expression->get(0)})[{$expression->get(2)}] === {$expression->get(1)}) : ?>"; // phpcs:ignore
            }

            return "<?php if (get_field({$expression->get(0)}) === {$expression->get(1)}) : ?>";
        }
    },

    'endfield' => function () {
        return "<?php endif; ?>";
    },

    /*
    |---------------------------------------------------------------------
    | @sub / @hassub / @issub / @endsub
    |---------------------------------------------------------------------
    */

    'sub' => function ($expression) {
        if (Str::contains($expression, ',')) {
            $expression = Util::parse($expression);

            if (! empty($expression->get(2))) {
                return "<?= get_sub_field({$expression->get(0)})[{$expression->get(1)}][{$expression->get(2)}]; ?>";
            }

            return "<?= get_sub_field({$expression->get(0)})[{$expression->get(1)}]; ?>";
        }

        return "<?= get_sub_field({$expression}); ?>";
    },

    'hassub' => function ($expression) {
        if (Str::contains($expression, ',')) {
            $expression = Util::parse($expression);

            if (! empty($expression->get(2))) {
                return "<?php if (get_sub_field({$expression->get(0)})[{$expression->get(1)}][{$expression->get(2)}]) : ?>"; // phpcs:ignore
            }

            return "<?php if (get_sub_field({$expression->get(0)})[{$expression->get(1)}]) : ?>";
        }

        return "<?php if (get_sub_field({$expression})) : ?>";
    },

    'issub' => function ($expression) {
        if (Str::contains($expression, ',')) {
            $expression = Util::parse($expression);

            if (! empty($expression->get(2))) {
                return "<?php if (get_sub_field({$expression->get(0)})[{$expression->get(1)}] === {$expression->get(2)}) : ?>"; // phpcs:ignore
            }

            return "<?php if (get_sub_field({$expression->get(0)}) === {$expression->get(1)}) : ?>";
        }
    },

    'endsub' => function () {
        return "<?php endif; ?>";
    },

    /*
    |---------------------------------------------------------------------
    | @layouts / @endlayouts
    |---------------------------------------------------------------------
    */

    'layouts' => function ($expression) {
        if (Str::contains($expression, ',')) {
            $expression = Util::parse($expression);

            return "<?php if (have_rows({$expression->get(0)}, {$expression->get(1)})) : ?>" .
                   "<?php while (have_rows({$expression->get(0)}, {$expression->get(1)})) : the_row(); ?>";
        }

        return "<?php if (have_rows({$expression})) : ?>" .
               "<?php while (have_rows({$expression})) : the_row(); ?>";
    },

    'endlayouts' => function () {
        return "<?php endwhile; endif; ?>";
    },

    /*
    |---------------------------------------------------------------------
    | @layout / @endlayout
    |---------------------------------------------------------------------
    */

    'layout' => function ($expression) {
        return "<?php if (get_row_layout() === {$expression}) : ?>";
    },

    'endlayout' => function () {
        return "<?php endif; ?>";
    },

    /*
    |---------------------------------------------------------------------
    | @group / @endgroup
    |---------------------------------------------------------------------
    */

    'group' => function ($expression) {
        if (Str::contains($expression, ',')) {
            $expression = Util::parse($expression);

            return "<?php if (have_rows({$expression->get(0)}, {$expression->get(1)})) : ?>" .
                   "<?php while (have_rows({$expression->get(0)}, {$expression->get(1)})) : the_row(); ?>";
        }

        return "<?php if (have_rows({$expression})) : ?>" .
               "<?php while (have_rows({$expression})) : the_row(); ?>";
    },

    'endgroup' => function () {
        return "<?php endwhile; endif; ?>";
    },

    /*
    |---------------------------------------------------------------------
    | @options / @endoptions
    |---------------------------------------------------------------------
    */

    'options' => function ($expression) {
        return "<?php if (have_rows({$expression}, 'option')) : ?>" .
               "<?php while (have_rows({$expression}, 'option')) : the_row(); ?>";
    },

    'endoptions' => function () {
        return "<?php endwhile; endif; ?>";
    },

    /*
    |---------------------------------------------------------------------
    | @option / @hasoption / @isoption / @endoption
    |---------------------------------------------------------------------
    */

    'option' => function ($expression) {
        if (Str::contains($expression, ',')) {
            $expression = Util::parse($expression);

            return "<?= get_field({$expression->get(0)}, 'option')[{$expression->get(1)}]; ?>";
        }

        return "<?= get_field({$expression}, 'option'); ?>";
    },

    'hasoption' => function ($expression) {
        if (Str::contains($expression, ',')) {
            $expression = Util::parse($expression);

            return "<?php if (get_field({$expression->get(0)}, 'option')[{$expression->get(1)}]) : ?>";
        }

        return "<?php if (get_field({$expression}, 'option')) : ?>";
    },

    'isoption' => function ($expression) {
        if (Str::contains($expression, ',')) {
            $expression = Util::parse($expression);

            if (! empty($expression->get(2))) {
                return "<?php if (get_field({$expression->get(0)}, 'option')[{$expression->get(1)}] === {$expression->get(2)}) : ?>"; // phpcs:ignore
            }

            return "<?php if (get_field({$expression->get(0)}, 'option') === {$expression->get(1)}) : ?>";
        }
    },

    'endoption' => function () {
        return "<?php endif; ?>";
    },

];
