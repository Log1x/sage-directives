# Sage Directives

[![Latest Stable Version](https://poser.pugx.org/log1x/sage-directives/v/stable)](https://packagist.org/packages/log1x/sage-directives) [![Total Downloads](https://poser.pugx.org/log1x/sage-directives/downloads)](https://packagist.org/packages/log1x/sage-directives)

Sage Directives is a simple Composer package adding a variety of useful Blade directives for use with Sage 9 including directives for WordPress, ACF, and various miscellaneous helpers.

## Requirements

- [Sage](https://github.com/roots/sage) >= 9.0
- [PHP](https://secure.php.net/manual/en/install.php) >= 7.1.3
- [Composer](https://getcomposer.org/download/)

## Installation

Install via Composer:

```bash
composer require log1x/sage-directives
```

## Usage

Once Sage Directives is installed with Composer, it is automatically loaded and is ready for use. If a directive appears to not be rendering properly, please make sure you clear your Blade cache before further debugging or opening an issue.

| [WordPress](#wordpress)  |                          | [ACF](#acf)            |                          | [Helpers](#helpers)        |                      |
|--------------------------|--------------------------|------------------------|--------------------------|----------------------------|----------------------|
| [@query](#query)         | [@user](#user)           | [@fields](#fields)     | [@group](#group)         | [@istrue](#istrue)         | [@extract](#extract) |
| [@posts](#posts)         | [@guest](#guest)         | [@field](#field)       | [@option](#option)       | [@isfalse](#isfalse)       | [@implode](#implode) |
| [@title](#title)         | [@shortcode](#shortcode) | [@hasfield](#hasfield) | [@hasoption](#hasoption) | [@isnull](#isnull)         | [@repeat](#repeat)   |
| [@content](#content)     | [@wpauto](#wpautop)      | [@isfield](#isfield)   | [@isoption](#isoption)   | [@isnotnull](#isnotnull)   | [@style](#style)     |
| [@excerpt](#excerpt)     | [@wpautokp](#wpautokp)   | [@sub](#sub)           | [@options](#options)     | [@instanceof](#instanceof) | [@script](#script)   |
| [@author](#author)       |                          | [@hassub](#hassub)     |                          | [@typeof](#typeof)         | [@js](#js)           |
| [@authorurl](#authorurl) |                          | [@issub](#issub)       |                          | [@global](#global)         | [@inline](#inline)   |
| [@published](#published) |                          | [@layouts](#layouts)   |                          | [@set](#set)               | [@fa](#fa)           |
| [@modified](#modified)   |                          | [@layout](#layout)     |                          | [@unset](#unset)           |                      |

### WordPress

The following directives are specific to WordPress core functionality.

#### @query

`@query` initializes a standard `WP_Query` as `$query` and accepts the usual `WP_Query` [parameters](https://codex.wordpress.org/Class_Reference/WP_Query#Parameters) as an array.

```php
@query([
  'post_type' => 'post'
])
```

#### @posts

`@posts` begins the post loop and by default, assumes that `WP_Query` is set to `$query` (which is the case when using `@query`). It is wrapped in a `have_posts()` conditional and thus will return `null` if no posts are found.

`@endposts` is available to end your loop and `have_posts()` conditional as well as resetting your loop with [`wp_reset_postdata()`](https://codex.wordpress.org/Function_Reference/wp_reset_postdata).

```php
@query([
  'post_type' => 'post'
])

@posts
  <h2 class="entry-title">@title</h2>
  <div class="entry-content">
    @content
  </div>
@endposts
```

If an instance of `WP_Query` is passed to `@posts`, it will use that instead:

```php
@php
  $query = new WP_Query([
    'post_type' => 'post'
  ]);
@endphp

@posts($query)
  <h2 class="entry-title">@title</h2>
  <div class="entry-content">
    @content
  </div>
@endposts
```

Additionally, you can pass a single post ID, post object, or an array containing a mixture of the two:

```php
@posts(12)
  <h2 class="entry-title">@title</h2>
  <div class="entry-content">
    @content
  </div>
@endposts
  
@posts(get_post(4))
  <h2 class="entry-title">@title</h2>
  <div class="entry-content">
    @content
  </div>
@endposts
  
@posts([6, get_post(9), 3])
  <h2 class="entry-title">@title</h2>
  <div class="entry-content">
    @content
  </div>
@endposts
```

When passing an array of IDs / objects, the posts will be sorted by the order of the array.

If `@query` is not used and an argument is not passed to `@posts`, it will use the main loop from the `$wp_query` global.

#### @title

`@title` echoes the current post's title using [`get_the_title()`](https://developer.wordpress.org/reference/functions/get_the_title/).

```php
@title
```

To echo the title of a specific post, you can pass the post ID or a `WP_Post` instance as a second parameter:

```php
@title(1)
@title(get_post(1))
```

#### @content

`@content` echoes the current post's content using [`the_content()`](https://developer.wordpress.org/reference/functions/the_content/).

```php
@content
```

#### @excerpt

`@excerpt` echoes the current post's excerpt using [`the_excerpt()`](https://developer.wordpress.org/reference/functions/the_excerpt/).

```php 
@excerpt
```

#### @author

`@author` echoes the author of the current post's display name.

```php
@author
```

To echo the display name of a specific author, you can pass the author's ID as a second parameter:

```php
@author(1)
```

#### @authorurl

`@authorurl` echoes the author of the current post's archive URL.

```php
<span class="author" itemprop="author" itemscope itemtype="http://schema.org/Person">
  <a href="@authorurl" itemprop="url">
    <span class="fn" itemprop="name" rel="author">@author</span>
  </a>
</span>
```

To echo the URL of a specific author, you can pass the author's ID as a second parameter:

```php
<a href="@authorurl">@author</a>
```

#### @published

`@published` echoes the current post's published date. By default, it uses the date format set in `Settings > General`.

```php
@published
```

To change the [formatting of the date](https://codex.wordpress.org/Formatting_Date_and_Time), you can pass it as the first parameter:

```php
<time class="entry-time">
  <span>@published('F j, Y')</span>
  <span itemprop="datePublished" content="@published('c')"></span>
</time>
```

To echo the published date of a specific post, you can pass a post ID or an instance of `WP_Post` as the first parameter:

```php
@published(1)
@published(get_post(1))
```

To format the published date of a specific post, you can pass the format as the first parameter, and the post ID or instance of `WP_Post` as the second parameter:

```php
@published('F j, Y', 1)
@published('F j, Y', get_post(1))
```

#### @modified

`@modified` is similar to `@published`, but instead echoes the current post's last modified date. By default, it uses the date format set in `Settings > General`.

```php
@modified
```

To change the [formatting of the date](https://codex.wordpress.org/Formatting_Date_and_Time), you can pass it as the first parameter:

```php
<time class="entry-time">
  <span>@published('F j, Y')</span>
  <span itemprop="datePublished" content="@published('c')"></span>
  <span itemprop="dateModified" class="updated" content="@modified('c')"></span>
</time>
```

To echo the modified date of a specific post, you can pass a post ID or an instance of `WP_Post` as the first parameter:

```php
@modified(1)
@modified(get_post(1))
```

To format the modified date of a specific post, you can pass the format as the first parameter, and the post ID or instance of `WP_Post` as the second parameter:

```php
@modified('F j, Y', 1)
@modified('F j, Y', get_post(1))
```

#### @shortcode

`@shortcode` echoes the specified shortcode using [`do_shortcode()`](https://developer.wordpress.org/reference/functions/do_shortcode/).

```php
@shortcode('[my-shortcode]')
```

#### @user

`@user` is a simple `is_user_logged_in()` conditional to display specific content only when a user is logged in. It can be closed using `@enduser`.

```php
@user
  You are logged in!
@enduser
```

#### @guest

`@guest` is a simple `! is_user_logged_in()` conditional to display specific content only when a user is not logged in. It can be closed using `@endguest`.

```php
@guest
  You must be <a href="/wp-login.php">logged in</a> to view this content.
@endguest
```

#### @wpautop

`@wpautop` runs a passed string through [`wpautop()`](https://codex.wordpress.org/Function_Reference/wpautop) and echoes the output.

```php
@wpautop($content)
```

#### @wpautokp

`@wpautokp` does the same as `@wpautop` but also sanitizes the string with [`wp_kses_post()`](https://codex.wordpress.org/Function_Reference/wp_kses_post).

```php
@wpautokp($content)
```

### ACF

The following directives are for use with Advanced Custom Fields. If ACF is not installed and activated, they will not be initialized.

#### @field

`@field` echoes the specified field using `get_field()`.

```php
@field('text')
```

To echo a field for a specific post, you can pass a post ID as a second parameter:

```php
@field('text', 1)
```

If the field you are echoing is an array, you can pass the array key as a second parameter:

```php
@field('image', 'url')
```

To echo a field for a specific post that is also an array, you can pass the post ID as a third parameter:

```php
@field('image', 'url', 1)
```

#### @hasfield

`@hasfield` is a simple conditional checking if the specified field exists and is not empty. It can be closed using `@endfield`.

```php
@hasfield('list')
  <ul>
    @fields('list')
      <li>@sub('item')</li>
    @fields
  </ul>
@endfield
```

To check the existence of a field for a specific post, you can pass a post ID as a second parameter:

```php
@hasfield('list', 5)
  <ul>
    @fields('list', 5)
      <li>@sub('item')</li>
    @fields
  </ul>
@endfield
```

If the field you are checking against is an array, you can pass the array key as a second parameter:

```php
@hasfield('image', 'url')
  <figure class="image">
    <img src="@field('image', 'url')" alt="@field('image', 'alt')" />
  </figure>
@endfield
```

To check the existence of a field that is an array for a specific post, you can pass the array key as a second parameter and the post ID as a third parameter:

```php
@hasfield('image', 'url', 1)
  <figure class="image">
    <img src="@field('image', 'url', 1)" alt="@field('image', 'alt', 1)" />
  </figure>
@endfield
```

#### @isfield

`@isfield` is a simple conditional for checking if your field value equals a specified value. It can be closed using `@endfield`.

```php
@isfield('cta_type', 'phone')
  <i class="fas fa-phone"></i>
@endfield
```

To check the field value of a specific post, you can pass a post ID as a third parameter:

```php
@isfield('cta_type', 'phone', 1)
  <i class="fas fa-phone"></i>
@endfield
```

If the field you are checking against is an array, you can pass the array key as a second parameter and the value you are checking against as a third parameter:

```php
@isfield('cta', 'type', 'phone')
  <i class="fas fa-phone"></i>
@endfield
```

To check the field value of a field that is an array for a specific post, you can pass the array key as a second parameter, the value you are checking against as a third parameter, and the post ID as a fourth parameter:

```php
@isfield('cta', 'type', 'phone', 1)
  <i class="fas fa-phone"></i>
@endfield
```

#### @fields

`@fields` acts as a helper for handling repeater fields. It is wrapped with a [`have_rows()`](https://www.advancedcustomfields.com/resources/have_rows/) conditional, and if it exists and is not empty, begins the while loop followed by `the_row()`. It can be closed using `@endfields`.

```php
<ul>
  @fields('list')
    <li>@sub('item')</li>
  @endfields
</ul>
```

To retrieve fields for a specific post, you can pass a post ID as a second parameter:

```php
<ul>
  @fields('list', 1)
    <li>@sub('item')</li>
  @endfields
</ul>
```

#### @sub

`@sub` echoes the specified sub field using [`get_sub_field()`](https://www.advancedcustomfields.com/resources/get_sub_field/). It is to be used inside of repeatable fields such as `@fields`, `@layout`, `@group`, and `@options`.

```php
<ul>
  @fields('list')
    <li>@sub('item')</li>
  @endfields
</ul>
```

If the sub field is an array, you can pass the key as a second parameter:

```php
<ul class="slider">
  @fields('slides')
    <li class="slide">
      <img src="@sub('image', 'url')" alt="@sub('image', 'alt')" />
    </li>
  @endfields
</ul>
```

More usage of `@sub` can be found alongside the examples of the repeatable fields listed above.

#### @hassub

`@hassub` is a simple conditional checking if the specified field exists and is not empty. It can be closed using `@endsub`.

```php
@hassub('icon')
  <i class="fas fa-@sub('icon')"></i>
@endsub
```

If the sub field you are checking against is an array, you can pass the array key as a second parameter:

```php
@hassub('image', 'url')
  <img src="@sub('image', 'url')" alt="@sub('image', 'alt')" />
@endsub
```

#### @issub

`@issub` is a simple conditional for checking if your sub field equals a specified value. It can be closed using `@endsub`.

```php
@issub('icon', 'arrow')
  <i class="fas fa-arrow-up fa-rotate-90"></i>
@endsub
```

If the sub field you are checking against is an array, you can pass the array key as a second parameter and the value you are checking against as a third parameter:

```php
@issub('icon', 'name', 'arrow')
  <i class="fas fa-@sub('icon', 'value') fa-rotate-90"></i>
@endsub
```

#### @layouts

`@layouts` acts as a helper for handling flexible content fields. Under the hood, it is essentially the exact same as `@fields`, but is provided to allow for a more clean, readable code-base in conjunction with `@layout` which calls `get_row_layout()`. It can be closed using `@endlayouts`.

```php
@layouts('components')
  [...]
@endlayouts
```

To retrieve layouts for a specific post, you can pass a post ID as a second parameter:

```php
@layouts('components', 1)
  [...]
@endlayouts
```

#### @layout

`@layout` serves as a conditional checking if `get_row_layout()` equals the specified value during the `have_rows()` while loop.

Using `@layouts`, this allows you to have a rather clean view when displaying your flexible content components. It can be closed using `@endlayout`.

```php
@layouts('components')
  @layout('card')
    <div class="card">
      @hassub('image')
        <div class="card-image">
          <img src="@sub('image', 'url')" alt="@sub('image', 'alt')" />
        </div>
      @endsub

      <div class="card-content">
        <h2>@sub('title')</h2>
        @sub('description')
      </div>
    </div>
  @endlayout

  @layout('button')
    @hassub('url')
      <a href="@sub('url')" class="button button--@sub('color') button--@sub('size')">
        @hassub('icon')
          <i class="fa fa-@sub('icon')"></i>
        @endsub
        <span>@sub('label')</span>
      </a>
    @endsub
  @endlayout

  @layout('content')
    @hassub('content')
      <div class="content">
        @sub('content')
      </div>
    @endsub
  @endlayout
@endlayouts
```

#### @group

`@group` acts as a helper for handling grouped fields. Under the hood, it is essentially the exact same as `@fields` and thus serves as a simple alias for code readability purposes. Which one you prefer is entirely up to you. It can be closed using `@endgroup`.

```php
@group('button')
  @hassub('url')
    <a href="@sub('url')" class="button button--@sub('color')">
      @sub('label')
    </a>
  @endsub
@endgroup
```

To retrieve a group for a specific post, you can pass a post ID as a second parameter:

```php
@group('button', 1)
  @hassub('url')
    <a href="@sub('url')" class="button button--@sub('color')">
      @sub('label')
    </a>
  @endsub
@endgroup
```

#### @option

`@option` echoes the specified theme options field using `get_field($field, 'option')`.

```php
Find us on <a href="@option('facebook_url')" target="_blank">Facebook</a>
```

If the option is an array, you can pass the key as a second parameter:

```php
<div class="navbar-brand">
  <a class="navbar-item" href="{{ home_url() }}">
    <img src="@option('logo', 'url')" alt="{{ get_bloginfo('name', 'display') }}" />
  </a>
</div>
```

#### @hasoption

`@hasoption` is a simple conditional checking if the specified theme option field exists and is not empty. It can be closed using `@endoption`.

```php
@hasoption('facebook_url')
  Find us on <a href="@option('facebook_url')" target="_blank">Facebook</a>
@endoption
```

If the option you are checking against is an array, you can pass the array key as a second parameter:

```php
@hasoption('facebook', 'url')
  <a href="@option('facebook', 'url')" target="_blank">
    @hasoption('facebook', 'icon')
      <span class="icon">
        <i class="fas @option('facebook', 'icon')"></i>
      </span>
    @else 
      <span class="label">Facebook</span>
    @endoption
  </a>
@endoption
```

#### @isoption

`@isoption` is a simple conditional for checking if your theme option field equals a specified value. It can be closed using `@endoption`.

```php
@isoption('logo_type', 'image')
  <img src="@option('logo')" alt="{{ get_bloginfo('name', 'display') }}" />
@endoption
```

If the option you are checking against is an array, you can pass the array key as a second parameter and the value you are checking against as a third parameter:

```php
@isoption('logo', 'type', 'image')
  <img src="@option('logo', 'url')" alt="{{ get_bloginfo('name', 'display') }}" />
@endoption
```

#### @options

`@options` acts as a helper for handling repeater and group fields within your theme options. Under the hood, it is essentially the exact same as `@fields` and `@group` except it automatically has the theme options ID `'option'` passed to it. It can be closed using `@endoptions`.

```php
@hasoption('social')
  <ul class="social">
    @options('social')
      <li>
        <a href="@sub('url')" target="_blank">
          @hassub('icon')
            <span class="icon">
              <i class="fas fa-@sub('icon')"></i>
            </span>
          @endsub
          <span>@sub('platform')</span>
        </a>
      </li>
    @endoptions
  </ul>
@endoption

```

### Helpers

The following directives are generalized helpers in an attempt to avoid using `@php` and `@endphp` where it isn't absolutely necessary.

#### @istrue

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

#### @isfalse

`@isfalse` is a simple conditional that displays the specified output if the parameter passed exists but returns false. It can be closed using `@endisfalse`.

```php
@isfalse($variable)
  Goodbye World
@endistrue
```

Alternatively, you can pass the output as a second parameter:

```php
@isfalse($variable, 'Goodbye World')
```

#### @isnull

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

#### @isnotnull

`@isnotnull` is a simple conditional that displays the specified output if the parameter passed exists and is not null. It can be closed using `@endisnotnull`.

```php
@isnotnull($variable)
  There is something here.
@endisnull
```

Alternatively, you can pass the output as a second parameter:

```php
@isnotnull($variable, 'There is something here.')
```

#### @instanceof

`@instanceof` checks if the first parameter is an instance of the second parameter. It can be closed using `@endinstanceof`.

```php
@instanceof($post, 'WP_Post')
  The post ID is {{ $post->ID }}.
@endinstanceof
```

#### @typeof

`@typeof` checks if the first parameter is of a specified type. It can be closed using `@endtypeof`.

```php
@typeof(14, 'integer')
  This is a whole number.
@endtypeof
```

#### @global

`@global` globals the specified variable.

```php
@global($post)
```

#### @set

`@set` sets the specifed variable to a specified value.

```php
@set($hello, 'world')
```

#### @unset

`@unset` unsets the specified variable.

```php
@unset($hello)
```

#### @extract

`@extract` extracts the passed array into variables. This can be useful for making views customizable when passing parameters to `@include` but also having default values set within the view.

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

#### @implode

`@implode` echoes a string containing a representation of each element within the array passed to it with a glue string between each element.

```php
@implode(', ' ['dog', 'cat', 'mouse', 'snake'])
// dog, cat, mouse, snake
```

#### @repeat

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

#### @style

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

#### @script

`@script` allows you to quickly inline a block of Javascript or define a path to a script. When being used for inline JS, it can be closed using `@endscript`.

```php
@script
  console.log('Hello World')
@endscript
  
@script('/path/to/script.js')
```

#### @js

`@js` allows you to declare inline Javascript variables similar to [wp_add_inline_script()](https://developer.wordpress.org/reference/functions/wp_add_inline_script/). The passed value can be in the form of a string or an array.

```php
@js('hello', 'world')
  
// <script>
//   window.hello = 'world';
// </script>
```

#### @inline

`@inline` loads the contents of a CSS, JS, or HTML file inline into your view and wraps the content with the proper HTML tag depending on the file extension. By default, the path is set to your current theme directory.

```php
@inline('dist/styles/critical.css')
```

#### @fa

`@fa` and its related directives serve as helpers for quickly outputting Font Awesome icons. `@fa` is for Font Awesome 4, while `@fas`, `@far`, `@fal`, and `@fab` are for their corrosponding Font Awesome 5 variation.

```php
@fa('arrow-up', 'optional-css-classes')
@fas('arrow-down', 'optional-css-classes')
@far('arrow-left', 'optional-css-classes')
@fal('arrow-right', 'optional-css-classes')
@fab('twitter', 'optional-css-classes')
```
