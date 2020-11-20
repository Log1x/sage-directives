<?php

namespace Log1x\SageDirectives;

class Directives
{
    /**
     * Directives
     *
     * @var array
     */
    protected $directives = [
        'ACF',
        'Helpers',
        'WordPress'
    ];

    /**
     * Namespace
     *
     * @var string
     */
    protected $namespace = 'App';

    /**
     * Create a new Directives instance.
     *
     * @return void
     */
    public function __construct()
    {
        add_action('after_setup_theme', function () {
            $this->namespace = apply_filters('log1x/sage-directives/namespace', $this->namespace);

            if (! $this->blade()) {
                return;
            }

            collect($this->directives())
                ->each(function ($directive, $function) {
                    $this->blade()->directive($function, $directive);
                });
        }, 20);
    }

    /**
     * Returns the specified directives as an array.
     *
     * @param  string $name
     * @return array
     */
    protected function get($name)
    {
        if (file_exists($directives = __DIR__ . '/Directives/' . $name . '.php')) {
            return require_once($directives);
        }
    }

    /**
     * Returns a collection of directives.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function directives()
    {
        return collect($this->directives)
            ->flatMap(function ($directive) {
                if ($directive === 'ACF' && ! function_exists('acf')) {
                    return;
                }

                return $this->get($directive);
            });
    }

    /**
     * Returns the Blade compiler.
     *
     * @return \Illuminate\Support\Facades\Blade
     */
    protected function blade()
    {
        $sage = "\\{$this->namespace}\\sage";

        if (function_exists($sage)) {
            return $sage('blade')->compiler();
        }

        if (function_exists('Roots\app')) {
            return \Roots\app('blade.compiler');
        }
    }
}

// phpcs:disable
if (function_exists('add_action')) {
    new Directives();
}
