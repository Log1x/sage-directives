<?php

namespace Log1x\SageDirectives\Directives;

use Illuminate\Support\Str;

class Helpers extends Directives
{
    /**
     * The Helper directives.
     */
    public function directives(): array
    {
        return [
            /*
            |---------------------------------------------------------------------
            | @istrue / @isfalse
            |---------------------------------------------------------------------
            */

            'istrue' => function ($expression) {
                if ($this->shouldParse($expression)) {
                    $expression = $this->parse($expression);

                    return "<?php if (isset({$expression->get(0)}) && (bool) {$expression->get(0)} === true) : ?>".
                        "<?php echo {$expression->get(1)}; ?>".
                        '<?php endif; ?>';
                }

                return "<?php if (isset({$expression}) && (bool) {$expression} === true) : ?>";
            },

            'endistrue' => function () {
                return '<?php endif; ?>';
            },

            'isfalse' => function ($expression) {
                if ($this->shouldParse($expression)) {
                    $expression = $this->parse($expression);

                    return "<?php if (isset({$expression->get(0)}) && (bool) {$expression->get(0)} === false) : ?>".
                        "<?php echo {$expression->get(1)}; ?>".
                        '<?php endif; ?>';
                }

                return "<?php if (isset({$expression}) && (bool) {$expression} === false) : ?>";
            },

            'endisfalse' => function () {
                return '<?php endif; ?>';
            },

            /*
            |---------------------------------------------------------------------
            | @isnull / @endisnull / @isnotnull / @endisnotnull
            |---------------------------------------------------------------------
            */

            'isnull' => function ($expression) {
                if ($this->shouldParse($expression)) {
                    $expression = $this->parse($expression);

                    return "<?php if (is_null({$expression->get(0)})) : ?>".
                        "<?php echo {$expression->get(1)}; ?>".
                        '<?php endif; ?>';
                }

                return "<?php if (is_null({$expression})) : ?>";
            },

            'endisnull' => function () {
                return '<?php endif; ?>';
            },

            'isnotnull' => function ($expression) {
                if ($this->shouldParse($expression)) {
                    $expression = $this->parse($expression);

                    return "<?php if (! is_null({$expression->get(0)})) : ?>".
                        "<?php echo {$expression->get(1)}; ?>".
                        '<?php endif; ?>';
                }

                return "<?php if (! is_null({$expression})) : ?>";
            },

            'endisnotnull' => function () {
                return '<?php endif; ?>';
            },

            /*
            |---------------------------------------------------------------------
            | @notempty / @endnotempty
            |---------------------------------------------------------------------
            */

            'notempty' => function ($expression) {
                if ($this->shouldParse($expression)) {
                    $expression = $this->parse($expression);

                    return "<?php if (! empty({$expression->get(0)})) : ?>".
                        "<?php echo {$expression->get(1)}; ?>".
                        '<?php endif; ?>';
                }

                return "<?php if (! empty({$expression})) : ?>";
            },

            'endnotempty' => function () {
                return '<?php endif; ?>';
            },

            /*
            |---------------------------------------------------------------------
            | @instanceof / @endinstanceof
            |---------------------------------------------------------------------
            */

            'instanceof' => function ($expression) {
                if ($this->shouldParse($expression)) {
                    $expression = $this->parse($expression);

                    return "<?php if (is_a({$expression->get(0)}, {$expression->get(1)})) : ?>";
                }
            },

            'endinstanceof' => function () {
                return '<?php endif; ?>';
            },

            /*
            |---------------------------------------------------------------------
            | @typeof / @endtypeof
            |---------------------------------------------------------------------
            */

            'typeof' => function ($expression) {
                if ($this->shouldParse($expression)) {
                    $expression = $this->parse($expression);

                    return "<?php if (gettype({$expression->get(0)}) === {$expression->get(1)}) : ?>";
                }
            },

            'endtypeof' => function () {
                return '<?php endif; ?>';
            },

            /*
            |---------------------------------------------------------------------
            | @global
            |---------------------------------------------------------------------
            */

            'global' => function ($expression) {
                return "<?php global {$expression}; ?>";
            },

            /*
            |---------------------------------------------------------------------
            | @set / @unset
            |---------------------------------------------------------------------
            */

            'set' => function ($expression) {
                if ($this->shouldParse($expression)) {
                    $expression = $this->parse($expression, 2);

                    return "<?php {$expression->get(0)} = {$expression->get(1)}; ?>";
                }
            },

            'unset' => function ($expression) {
                return "<?php unset({$expression}); ?>";
            },

            /*
            |---------------------------------------------------------------------
            | @extract / @implode
            |---------------------------------------------------------------------
            */

            'extract' => function ($expression) {
                return "<?php extract({$expression}); ?>";
            },

            'implode' => function ($expression) {
                if ($this->shouldParse($expression)) {
                    $expression = $this->parse($expression);

                    return "<?php echo implode({$expression->get(0)}, {$expression->get(1)}); ?>";
                }
            },

            /*
            |---------------------------------------------------------------------
            | @repeat / @endrepeat
            |---------------------------------------------------------------------
            */

            'repeat' => function ($expression) {
                $initLoop = "\$__currentLoopData = range(1, {$expression}); \$__env->addLoop(\$__currentLoopData);";
                $iterateLoop = '$__env->incrementLoopIndices(); $loop = $__env->getLastLoop();';

                return "<?php {$initLoop} foreach(\$__currentLoopData as \$__i) : {$iterateLoop} ?>";
            },

            'endrepeat' => function () {
                return '<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>';
            },

            /*
            |---------------------------------------------------------------------
            | @stylesheet / @endstylesheet
            |---------------------------------------------------------------------
            */

            'stylesheet' => function ($expression) {
                if (! empty($expression)) {
                    return '<link rel="stylesheet" href="'.$this->strip($expression).'">';
                }

                return '<style>';
            },

            'endstylesheet' => function () {
                return '</style>';
            },

            /*
            |---------------------------------------------------------------------
            | @script / @endscript
            |---------------------------------------------------------------------
            */

            'script' => function ($expression) {
                if (! empty($expression)) {
                    return '<script src="'.$this->strip($expression).'"></script>';
                }

                return '<script>';
            },

            'endscript' => function () {
                return '</script>';
            },

            /*
            |---------------------------------------------------------------------
            | @js
            |---------------------------------------------------------------------
            */

            'js' => function ($expression) {
                $expression = $this->parse($expression);
                $variable = $this->strip($expression->get(0));

                return "<script>\n".
                    "window.{$variable} = <?php echo is_array({$expression->get(1)}) ? json_encode({$expression->get(1)}) : '\'' . {$expression->get(1)} . '\''; ?>;\n". // phpcs:ignore
                    '</script>';
            },

            /*
            |---------------------------------------------------------------------
            | @inline
            |---------------------------------------------------------------------
            */

            'inline' => function ($expression) {
                $path = $this->strip($expression);

                $output = "<?php include get_theme_file_path({$expression}) ?>";

                if (Str::endsWith($path, '.css')) {
                    return "<style>{$output}</style>";
                }

                if (Str::endsWith($path, '.js')) {
                    return "<script>{$output}</script>";
                }

                return $output;
            },

        ];
    }
}
