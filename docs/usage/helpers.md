# Helpers

The following directives are generalized helpers in an attempt to avoid using `@php` and `@endphp` where it isn't absolutely necessary.

## @istrue

`@istrue` is a simple conditional that displays the specified output if the parameter passed exists and returns true. It can be closed using `@endistrue`.

```php
@istrue($variable)
  Hello World
@endistrue
```

Alternatively, you can pass the output as a second parameter:

```php
@istrue($variable, 'Hello World')
```

## @isfalse

`@isfalse` is a simple conditional that displays the specified output if the parameter passed exists but returns false. It can be closed using `@endisfalse`.

```php
@isfalse($variable)
  Goodbye World
@endisfalse
```

Alternatively, you can pass the output as a second parameter:

```php
@isfalse($variable, 'Goodbye World')
```

## @isnull

`@isnull` is a simple conditional that displays the specified output if the parameter passed is null. It can be closed using `@endisnull`.

```php
@isnull($variable)
  There is nothing here.
@endisnull
```

Alternatively, you can pass the output as a second parameter:

```php
@isnull($variable, 'There is nothing here.')
```

## @isnotnull

`@isnotnull` is a simple conditional that displays the specified output if the parameter passed exists and is not null. It can be closed using `@endisnotnull`.

```php
@isnotnull($variable)
  There is something here.
@endisnotnull
```

Alternatively, you can pass the output as a second parameter:

```php
@isnotnull($variable, 'There is something here.')
```

## @notempty

`@notempty` is a simple conditional that displays the specified output if the parameter passed exists and is not empty. It can be closed using `@endnotempty`.

```php
@notempty($variable)
  There is something here.
@endnotempty
```

Alternatively, you can pass the output as a second parameter:

```php
@notempty($variable, 'There is something here.')
```

## @instanceof

`@instanceof` checks if the first parameter is an instance of the second parameter. It can be closed using `@endinstanceof`.

```php
@instanceof($post, 'WP_Post')
  The post ID is {{ $post->ID }}.
@endinstanceof
```

## @typeof

`@typeof` checks if the first parameter is of a specified type. It can be closed using `@endtypeof`.

```php
@typeof(14, 'integer')
  This is a whole number.
@endtypeof
```

## @global

`@global` globals the specified variable.

```php
@global($post)
```

## @set

`@set` sets the specifed variable to a specified value.

```php
@set($hello, 'world')
```

## @unset

`@unset` unsets the specified variable.

```php
@unset($hello)
```

## @extract

`@extract` extracts the passed array into variables. This can be useful for making views customizable when passing parameters to `@include` but also having default values set within' the view.

```php
// single.blade.php
@include('components.entry-meta', [
  'author' => false,
  'date'   => true,
])

// entry-meta.blade.php
@extract([
  'author' => $author ?? true,
  'date'   => $date   ?? true
])

@if ($author)
  [...]
@endif

@if ($date)
  [...]
@endif
```

## @implode

`@implode` echo's a string containing a representation of each element within' the array passed to it with a glue string between each element.

```php
@implode(', ' ['dog', 'cat', 'mouse', 'snake'])
// dog, cat, mouse, snake
```

## @repeat

`@repeat` repeats its contents a specified number of times. It can be closed using `@endrepeat`.

```php
<ul>
  @repeat(5)
    <li>{{ $loop->iteration }}</li>
  @endrepeat
</ul>
```

Similar to Laravel's native looping, a `$loop` variable is available inside of `@repeat`:

```php
@repeat(5)
  @if ($loop->first)
    This is the first iteration.
  @endif

  @if ($loop->last)
    This is the last iteration.
  @endif

  This is iteration {{ $loop->iteration }} out of $loop->count.
  There are {{ $loop->remaining }} iterations left.
  The current iteration index is {{ $loop->index }}
@endrepeat
```

## @style

`@style` allows you to quickly inline a block of CSS or define a path to a stylesheet. When being used for inline CSS, it can be closed using `@endstyle`.

```php
@style
  .button {
    background-color: LightGreen;
    color: white;
    padding: 0.75rem 1rem;
  }
@endstyle

@style('/path/to/style.css')
```

## @script

`@script` allows you to quickly inline a block of Javascript or define a path to a script. When being used for inline JS, it can be closed using `@endscript`.

```php
@script
  console.log('Hello World')
@endscript

@script('/path/to/script.js')
```

## @js

`@js` allows you to declare inline Javascript variables similar to [wp_add_inline_script()](https://developer.wordpress.org/reference/functions/wp_add_inline_script/). The passed value can be in the form of a string or an array.

```php
@js('hello', 'world')

// <script>
//   window.hello = 'world';
// </script>
```

## @inline

`@inline` loads the contents of a CSS, JS, or HTML file inline into your view and wraps the content with the proper HTML tag depending on the file extension. By default, the path is set to your current theme directory.

```php
@inline('dist/styles/critical.css')
```
