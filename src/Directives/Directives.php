<?php

namespace Log1x\SageDirectives\Directives;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Log1x\SageDirectives\Contracts\Directives as DirectivesContract;

abstract class Directives implements DirectivesContract
{
    /**
     * The register state.
     */
    protected bool $registered = false;

    /**
     * Make an instance of the Directives.
     */
    public static function make(): self
    {
        return new static();
    }

    /**
     * Register the Directives.
     */
    public function register(): void
    {
        if ($this->registered) {
            return;
        }

        foreach ($this->directives() as $function => $directive) {
            Blade::directive($function, $directive);
        }

        $this->registered = true;
    }

    /**
     * Parse the expression into a collection.
     */
    public function parse(string $expression, int $limit = PHP_INT_MAX, string $delimiter = '__comma__'): Collection
    {
        $expression = preg_replace_callback(
            '/\'(.*?)\'|"(.*?)"/',
            fn ($matches) => str_replace(',', $delimiter, $matches[0]),
            $expression
        );

        return Collection::make(explode(',', $expression, $limit))
            ->map(function ($item) use ($delimiter) {
                $item = Str::of($item)
                    ->replace($delimiter, ',')
                    ->trim()
                    ->toString();

                return ! is_numeric($item) ? $item : (int) $item;
            });
    }

    /**
     * Determine if the expression should be parsed.
     */
    public function shouldParse(?string $expression = ''): bool
    {
        return Str::contains($expression, ',');
    }

    /**
     * Determine if the expression is a potential token.
     */
    public function isToken(?string $expression = ''): bool
    {
        $expression = $this->strip($expression);

        return ! empty($expression) && (is_numeric($expression) || Str::startsWith($expression, ['$', 'get_']));
    }

    /**
     * Strip single quotes from the expression.
     */
    public function strip(?string $expression = '', array $characters = ["'", '"']): string
    {
        return str_replace($characters, '', $expression ?? '');
    }

    /**
     * Wraps the passed string in single quotes if they are not already present.
     *
     * @param  string  $value
     * @return string
     */
    public function wrap($value)
    {
        $value = Str::start($value, "'");
        $value = Str::finish($value, "'");

        return $value;
    }

    /**
     * Unwraps the passed string from the passed delimiter.
     */
    public function unwrap(?string $value = '', string $delimiter = "'"): string
    {
        if (Str::startsWith($value, $delimiter)) {
            $value = Str::replaceFirst($delimiter, '', $value);
        }

        if (Str::endsWith($value, $delimiter)) {
            $value = Str::replaceLast($delimiter, '', $value);
        }

        return $value;
    }

    /**
     * Convert an array expression to a string.
     */
    public function toString(string|array|null $expression = '', bool $single = false): string
    {
        if (! is_array($expression)) {
            return $this->wrap($expression);
        }

        $keys = '';

        foreach ($expression as $key => $value) {
            $keys .= $single ?
                $this->wrap($value).',' :
                $this->wrap($key).' => '.$this->wrap($value).', ';
        }

        $keys = trim(Str::replaceLast(',', '', $keys));

        if (! $single) {
            $keys = Str::start($keys, '[');
            $keys = Str::finish($keys, ']');
        }

        return $keys;
    }

    /**
     * Determine if the expression looks like an array.
     */
    public function isArray(?string $expression = ''): bool
    {
        $expression = $this->unwrap($expression);

        return Str::startsWith($expression, '[') && Str::endsWith($expression, ']');
    }
}
