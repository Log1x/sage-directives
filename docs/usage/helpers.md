# Helpers

The following directives are generalized helpers in an attempt to avoid using `@php` and `@endphp` where it isn't absolutely necessary.

## @istrue

`@istrue` is a simple conditional that displays the specified output if the parameter passed exists and returns true. It can be closed using `@endistrue`.

```py
@istrue($variable)
  Hello World
@endistrue
```

Alternatively, you can pass the output as a second parameter:

```py
@istrue($variable, 'Hello World')
```

## @isfalse

`@isfalse` is a simple conditional that displays the specified output if the parameter passed exists but returns false. It can be closed using `@endisfalse`.

```py
@isfalse($variable)
  Goodbye World
@endistrue
```

Alternatively, you can pass the output as a second parameter:

```py
@isfalse($variable, 'Goodbye World')
```

## @isnull

`@isnull` is a simple conditional that displays the specified output if the parameter passed is null. It can be closed using `@endisnull`.

```py
@isnull($variable)
  There is nothing here.
@endisnull
```

Alternatively, you can pass the output as a second parameter:

```py
@isnull($variable, 'There is nothing here.')
```

## @isnotnull

`@isnotnull` is a simple conditional that displays the specified output if the parameter passed exists and is not null. It can be closed using `@endisnotnull`.

```py
@isnotnull($variable)
  There is something here.
@endisnull
```

Alternatively, you can pass the output as a second parameter:

```py
@isnotnull($variable, 'There is something here.')
```

## @instanceof

`@instanceof` checks if the first parameter is an instance of the second parameter. It can be closed using `@endinstanceof`.

```py
@instanceof($post, 'WP_Post')
  The post ID is {{ $post->ID }}.
@endinstanceof
```

## @typeof

`@typeof` checks if the first parameter is of a specified type. It can be closed using `@endtypeof`.

```py
@typeof(14, 'integer')
  This is a whole number.
@endtypeof
```

## @global

`@global` globals the specified variable.

```py
@global($post)
```

## @set

`@set` sets the specifed variable to a specified value.

```py
@set($hello, 'world')
```

## @unset

`@unset` unsets the specified variable.

```py
@unset($hello)
```

## @extract

`@extract` extracts the passed array into variables. This can be useful for making views customizable when passing parameters to `@include` but also having default values set within' the view.

```py
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

```py
@implode(', ' ['dog', 'cat', 'mouse', 'snake'])
// dog, cat, mouse, snake
```

## @repeat

`@repeat` repeats its contents a specified number of times. It can be closed using `@endrepeat`.

```py
<ul>
  @repeat(5)
    <li>{{ $loop->iteration }}</li>
  @endrepeat
</ul>
```

Similar to Laravel's native looping, a `$loop` variable is available inside of `@repeat`:

```py
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

```py
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

```py
@script
  console.log('Hello World')
@endscript
  
@script('/path/to/script.js')
```

## @js

`@js` allows you to declare inline Javascript variables similar to [wp_add_inline_script()](https://developer.wordpress.org/reference/functions/wp_add_inline_script/). The passed value can be in the form of a string or an array.

```py
@js('hello', 'world')
  
// <script>
//   window.hello = 'world';
// </script>
```

## @inline

`@inline` loads the contents of a CSS, JS, or HTML file inline into your view and wraps the content with the proper HTML tag depending on the file extension. By default, the path is set to your current theme directory.

```py
@inline('dist/styles/critical.css')
```

## @fa

`@fa` and its related directives serve as helpers for quickly outputting Font Awesome icons. `@fa` is for Font Awesome 4, while `@fas`, `@far`, `@fal`, and `@fab` are for their corrosponding Font Awesome 5 variation.

```py
@fa('arrow-up', 'optional-css-classes')
@fas('arrow-down', 'optional-css-classes')
@far('arrow-left', 'optional-css-classes')
@fal('arrow-right', 'optional-css-classes')
@fab('twitter', 'optional-css-classes')
```
