<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Roots\Acorn\Application;
use Illuminate\Support\Fluent;
use Illuminate\Support\Facades\Facade;

abstract class TestCase extends BaseTestCase
{
    /**
     * The Acorn application instance.
     *
     * @var \Roots\Acorn\Application
     */
    public static $app;

    /**
     * The providers.
     *
     * @var array
     */
    protected $providers = [
        \Illuminate\View\ViewServiceProvider::class,
        \Illuminate\Filesystem\FilesystemServiceProvider::class,
        \Log1x\SageDirectives\SageDirectivesServiceProvider::class,
    ];

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (self::$app) {
            return;
        }

        $app = $this->createApplication();

        $app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            \Roots\Acorn\Exceptions\Handler::class
        );

        Facade::clearResolvedInstances();

        Facade::setFacadeApplication($app);

        foreach ($this->providers as $provider) {
            $app->register($provider);
        }

        $app->boot();

        self::$app = $app;
    }

    /**
     * Create the application.
     *
     * @return \Roots\Acorn\Application
     */
    protected function createApplication(): Application
    {
        $app = new Application(__DIR__, [
            'bootstrap' => __DIR__ . '/.acorn/bootstrap',
        ]);

        $app['env'] = 'testing';

        $app->bind('config', function () {
            return new Fluent([
                'view.compiled' => __DIR__ . '/.acorn/cache',
                'view.paths' => [__DIR__ . '/.acorn/views'],
            ]);
        });

        return $app;
    }

    /**
     * Get the Blade compiler.
     *
     * @return \Illuminate\View\Compilers\BladeCompiler
     */
    protected function getCompiler()
    {
        return self::$app->make('view')
            ->getEngineResolver()->resolve('blade')->getCompiler();
    }

    /**
     * Compile the Blade directive.
     *
     * @param  string $directive
     * @return string
     */
    public function compile($directive)
    {
        $compiled = $this->getCompiler()->compileString($directive);

        return str_replace(["\n", '  '], '', $compiled);
    }
}
