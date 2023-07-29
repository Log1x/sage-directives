<?php

namespace Log1x\SageDirectives;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class SageDirectivesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->directives()
            ->each(function ($directive, $function) {
                Blade::directive($function, $directive);
            });
    }

    /**
     * Get the Blade directives.
     *
     * @return array
     */
    public function directives()
    {
        return collect(['Acf', 'Helpers', 'WordPress'])
            ->flatMap(function ($directive) {
                if (file_exists($directives = __DIR__.'/Directives/'.$directive.'.php')) {
                    return require_once $directives;
                }
            });
    }
}
