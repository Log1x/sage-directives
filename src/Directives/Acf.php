<?php

namespace Log1x\SageDirectives\Directives;

class Acf extends Directives
{
    /**
     * The Advanced Custom Fields directives.
     */
    public function directives(): array
    {
        return [
            /*
            |---------------------------------------------------------------------
            | @fields / @endfields / @hasfields / @endhasfields
            |---------------------------------------------------------------------
            */

            'fields' => function ($expression) {
                if ($this->shouldParse($expression)) {
                    $expression = $this->parse($expression);

                    return "<?php if (have_rows({$expression->get(0)}, {$expression->get(1)})) : ?>".
                        "<?php while (have_rows({$expression->get(0)}, {$expression->get(1)})) : the_row(); ?>";
                }

                return "<?php if (have_rows({$expression})) : ?>".
                    "<?php while (have_rows({$expression})) : the_row(); ?>";
            },

            'endfields' => function () {
                return '<?php endwhile; endif; ?>';
            },

            'hasfields' => function ($expression) {
                if ($this->shouldParse($expression)) {
                    $expression = $this->parse($expression);

                    return "<?php if (have_rows({$expression->get(0)}, {$expression->get(1)})) : ?>";
                }

                return "<?php if (have_rows({$expression})) : ?>";
            },

            'endhasfields' => function () {
                return '<?php endif; ?>';
            },

            /*
            |---------------------------------------------------------------------
            | @field / @hasfield / @isfield / @endfield
            |---------------------------------------------------------------------
            */

            'field' => function ($expression) {
                if ($this->shouldParse($expression)) {
                    $expression = $this->parse($expression);

                    if ($this->isToken($expression->get(2))) {
                        if (empty($expression->get(3))) {
                            $expression->put(3, 'true');
                        }

                        return "<?php echo get_field({$expression->get(0)}, {$expression->get(2)}, {$expression->get(3)})[{$expression->get(1)}]; ?>";
                    }

                    if ($this->isToken($expression->get(1))) {
                        if (empty($expression->get(2))) {
                            $expression->put(2, 'true');
                        }

                        return "<?php echo get_field({$expression->get(0)}, {$expression->get(1)}, {$expression->get(2)}); ?>";
                    }

                    if (empty($expression->get(2))) {
                        $expression->put(2, 'true');
                    }

                    return "<?php echo get_field({$expression->get(0)}, null, {$expression->get(2)})[{$expression->get(1)}]; ?>";
                }

                return "<?php echo get_field({$expression}); ?>";
            },

            'hasfield' => function ($expression) {
                if ($this->shouldParse($expression)) {
                    $expression = $this->parse($expression);

                    if ($this->isToken($expression->get(2))) {
                        return "<?php if (get_field({$expression->get(0)}, {$expression->get(2)})[{$expression->get(1)}]) : ?>";
                    }

                    if ($this->isToken($expression->get(1))) {
                        return "<?php if (get_field({$expression->get(0)}, {$expression->get(1)})) : ?>";
                    }

                    return "<?php if (get_field({$expression->get(0)})[{$expression->get(1)}]) : ?>";
                }

                return "<?php if (get_field({$expression})) : ?>";
            },

            'isfield' => function ($expression) {
                if ($this->shouldParse($expression)) {
                    $expression = $this->parse($expression);

                    if ($this->isToken($expression->get(3))) {
                        return "<?php if (get_field({$expression->get(0)}, {$expression->get(3)})[{$expression->get(1)}] === {$expression->get(2)}) : ?>"; // phpcs:ignore
                    }

                    if ($this->isToken($expression->get(2))) {
                        return "<?php if (get_field({$expression->get(0)}, {$expression->get(2)}) === {$expression->get(1)}) : ?>"; // phpcs:ignore
                    }

                    if (! empty($expression->get(2)) && ! $this->isToken($expression->get(2))) {
                        return "<?php if (get_field({$expression->get(0)})[{$expression->get(1)}] === {$expression->get(2)}) : ?>"; // phpcs:ignore
                    }

                    return "<?php if (get_field({$expression->get(0)}) === {$expression->get(1)}) : ?>";
                }
            },

            'endfield' => function () {
                return '<?php endif; ?>';
            },

            /*
            |---------------------------------------------------------------------
            | @sub / @hassub / @issub / @endsub
            |---------------------------------------------------------------------
            */

            'sub' => function ($expression) {
                if ($this->shouldParse($expression)) {
                    $expression = $this->parse($expression);

                    if (! empty($expression->get(2))) {
                        return "<?php echo get_sub_field({$expression->get(0)})[{$expression->get(1)}][{$expression->get(2)}]; ?>";
                    }

                    return "<?php echo get_sub_field({$expression->get(0)})[{$expression->get(1)}]; ?>";
                }

                return "<?php echo get_sub_field({$expression}); ?>";
            },

            'hassub' => function ($expression) {
                if ($this->shouldParse($expression)) {
                    $expression = $this->parse($expression);

                    if (! empty($expression->get(2))) {
                        return "<?php if (get_sub_field({$expression->get(0)})[{$expression->get(1)}][{$expression->get(2)}]) : ?>"; // phpcs:ignore
                    }

                    return "<?php if (get_sub_field({$expression->get(0)})[{$expression->get(1)}]) : ?>";
                }

                return "<?php if (get_sub_field({$expression})) : ?>";
            },

            'issub' => function ($expression) {
                if ($this->shouldParse($expression)) {
                    $expression = $this->parse($expression);

                    if (! empty($expression->get(2))) {
                        return "<?php if (get_sub_field({$expression->get(0)})[{$expression->get(1)}] === {$expression->get(2)}) : ?>"; // phpcs:ignore
                    }

                    return "<?php if (get_sub_field({$expression->get(0)}) === {$expression->get(1)}) : ?>";
                }
            },

            'endsub' => function () {
                return '<?php endif; ?>';
            },

            /*
            |---------------------------------------------------------------------
            | @layouts / @endlayouts
            |---------------------------------------------------------------------
            */

            'layouts' => function ($expression) {
                if ($this->shouldParse($expression)) {
                    $expression = $this->parse($expression);

                    return "<?php if (have_rows({$expression->get(0)}, {$expression->get(1)})) : ?>".
                        "<?php while (have_rows({$expression->get(0)}, {$expression->get(1)})) : the_row(); ?>";
                }

                return "<?php if (have_rows({$expression})) : ?>".
                    "<?php while (have_rows({$expression})) : the_row(); ?>";
            },

            'endlayouts' => function () {
                return '<?php endwhile; endif; ?>';
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
                return '<?php endif; ?>';
            },

            /*
            |---------------------------------------------------------------------
            | @group / @endgroup
            |---------------------------------------------------------------------
            */

            'group' => function ($expression) {
                if ($this->shouldParse($expression)) {
                    $expression = $this->parse($expression);

                    return "<?php if (have_rows({$expression->get(0)}, {$expression->get(1)})) : ?>".
                        "<?php while (have_rows({$expression->get(0)}, {$expression->get(1)})) : the_row(); ?>";
                }

                return "<?php if (have_rows({$expression})) : ?>".
                    "<?php while (have_rows({$expression})) : the_row(); ?>";
            },

            'endgroup' => function () {
                return '<?php endwhile; endif; ?>';
            },

            /*
            |---------------------------------------------------------------------
            | @options / @endoptions / @hasoptions / @endhasoptions
            |---------------------------------------------------------------------
            */

            'options' => function ($expression) {
                return "<?php if (have_rows({$expression}, 'option')) : ?>".
                    "<?php while (have_rows({$expression}, 'option')) : the_row(); ?>";
            },

            'endoptions' => function () {
                return '<?php endwhile; endif; ?>';
            },

            'hasoptions' => function ($expression) {
                return "<?php if (have_rows({$expression}, 'option')) : ?>";
            },

            'endhasoptions' => function () {
                return '<?php endif; ?>';
            },

            /*
            |---------------------------------------------------------------------
            | @option / @hasoption / @isoption / @endoption
            |---------------------------------------------------------------------
            */

            'option' => function ($expression) {
                if ($this->shouldParse($expression)) {
                    $expression = $this->parse($expression);

                    return "<?php echo get_field({$expression->get(0)}, 'option')[{$expression->get(1)}]; ?>";
                }

                return "<?php echo get_field({$expression}, 'option'); ?>";
            },

            'hasoption' => function ($expression) {
                if ($this->shouldParse($expression)) {
                    $expression = $this->parse($expression);

                    return "<?php if (get_field({$expression->get(0)}, 'option')[{$expression->get(1)}]) : ?>";
                }

                return "<?php if (get_field({$expression}, 'option')) : ?>";
            },

            'isoption' => function ($expression) {
                if ($this->shouldParse($expression)) {
                    $expression = $this->parse($expression);

                    if (! empty($expression->get(2))) {
                        return "<?php if (get_field({$expression->get(0)}, 'option')[{$expression->get(1)}] === {$expression->get(2)}) : ?>"; // phpcs:ignore
                    }

                    return "<?php if (get_field({$expression->get(0)}, 'option') === {$expression->get(1)}) : ?>";
                }
            },

            'endoption' => function () {
                return '<?php endif; ?>';
            },
        ];
    }
}
