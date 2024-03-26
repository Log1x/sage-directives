<?php

namespace Log1x\SageDirectives;

use Illuminate\Support\ServiceProvider;

class SageDirectivesServiceProvider extends ServiceProvider
{
    /**
     * The directive providers.
     */
    protected array $directives = [
        Directives\Acf::class,
        Directives\Helpers::class,
        Directives\WordPress::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        collect($this->directives)
            ->filter(fn ($directive) => is_subclass_of($directive, Directives\Directives::class))
            ->each(fn ($directive) => $directive::make()->register());
    }
}
