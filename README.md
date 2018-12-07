# Sage Directives

Sage Directives is a simple Composer package adding a variety of useful Blade directives for use with Sage 9.

## Requirements

- [Sage](https://github.com/roots/sage) >= 9.0
- [PHP](https://secure.php.net/manual/en/install.php) >= 7.0
- [Composer](https://getcomposer.org/download/)

## Installation

Install via Composer:

```bash
composer require log1x/sage-directives
```

## Usage

Once Sage Directives is installed with Composer, it is automatically loaded and is ready for use. If a directive appears to not be rendering properly, please make sure you clear your Blade cache before further debugging or opening an issue.

| [WordPress](#wordpress)  | [ACF](#acf)            |                          | [Helpers](#helpers)      |
| :----------------------- | :--------------------- | :----------------------- | :----------------------- |
| [@query](#query)         | [@fields](#fields)     | [@layouts](#layouts)     | [@condition](#condition) |
| [@posts](#posts)         | [@field](#field)       | [@layout](#layout)       | [@global](#global)       |
| [@title](#title)         | [@hasfield](#hasfield) | [@group](#group)         | [@set](#set)             |
| [@content](#content)     | [@isfield](#isfield)   | [@option](#option)       | [@unset](#unset)         |
| [@excerpt](#excerpt)     | [@sub](#sub)           | [@hasoption](#hasoption) | [@extract](#extract)     |
| [@shortcode](#shortcode) | [@hassub](#hassub)     | [@isoption](#isoption)   | [@explode](#explode)     |
| [@user](#user)           | [@issub](#issub)       | [@options](#options)     | [@implode](#implode)     |
| [@guest](#guest)         |                        |                          |                          |

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

If an instance of `WP_Query` is passed to `@posts` as a variable, it will use that instead.

```php
@php
  $My_Query = new WP_Query([
    'post_type' => 'post'
  ]);
@endphp

@posts($My_Query)
  <h2 class="entry-title">@title</h2>
  <div class="entry-content">
    @content
  </div>
@endposts
```

If `$query` is not defined and a variable is not passed to `@posts`, it will use the main loop from the `$wp_query` global.

#### @title

`@title` simply echo's the current posts title using [`get_the_title()`](https://developer.wordpress.org/reference/functions/get_the_title/). It can also accept a specific post as a parameter.

```php
@title
@title(123)
```

#### @content

`@content` simply echo's the current posts content using [`the_content()`](https://developer.wordpress.org/reference/functions/the_content/).

```php
@content
```

#### @excerpt

`@excerpt` simply echo's the current posts excerpt using [`the_excerpt()`](https://developer.wordpress.org/reference/functions/the_excerpt/).

```php 
@excerpt
```

#### @shortcode

`@shortcode` simply echo's the provided shortcode using [`do_shortcode()`](https://developer.wordpress.org/reference/functions/do_shortcode/).

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

`@guest` is a simple `!is_user_logged_in()` conditional to display specific content only when a user is not logged in. It can be closed using `@endguest`.

```php
@guest
  You must be <a href="/wp-login.php">logged in</a> to view this content.
@endguest
```

### ACF

The following directives are for use with Advanced Custom Fields. If ACF is not installed and activated, they will not be initialized.

#### @field

`@field` does what you would expect it to do and echo's the specified field using `get_field()`. In an attempt to help keep things clean, it can also intelligently accept parameters to assist in pulling specific values if your field happens to be an array (e.g. an image field). This is done simply by checking if the second parameter passed is a string.

```php
@field('text')
@field('text', 123)         // Returns the text field from post ID 123.
@field('image', 'url')      // If image is an array, returns the url value.
@field('image', 'url', 123) // If image is an array, returns the url value for post ID 123.
```

#### @hasfield

`@hasfield` is a simple conditional checking if the specified field returns a value. Similar to `@field`, it accepts parameters to check array values as well as the post ID in the event you need your conditional to be specific. It can be closed using `@endfield`.

```php
@hasfield('list')
  <ul>
    @fields('list')
      <li>@sub('item')</li>
    @fields
  </ul>
@endfield

@hasfield('image', 'url')
  <figure class="image">
    <img src="@field('image', 'url')" alt="@field('image', 'alt')" />
  </figure>
@endfield
```

#### @isfield

`@isfield` is a simple conditional for checking if your field value equals a specified value. As a third parameter, it accepts a post ID. It can be closed using `@endfield`.

```php
@isfield('cta_type', 'phone')
  <i class="fa fa-phone"></i>
@endfield

@isfield('cta_type', 'phone', 123)
  <i class="fa fa-phone"></i>
@endfield
```

#### @fields

`@fields` acts as a helper for handling repeater fields. It is wrapped with a [`have_rows()`](https://www.advancedcustomfields.com/resources/have_rows/) conditional, and if it returns true, begins the while loop followed by `the_row()`. It can be closed using `@endfields`.

```php
<ul>
  @fields('list')
    <li>@sub('item')</li>
  @endfields
</ul>
```

Optionally, it can be passed a post ID:

```php
@fields('list', 123)
  [...]
@endfields
```

#### @sub

`@sub` does what you would expect it to do and echo's the specified sub field using [`get_sub_field()`](https://www.advancedcustomfields.com/resources/get_sub_field/). It is to be used inside of repeatable fields such as `@fields`, `@layout`, `@group`, and `@options`.

Similar to `@field`, it can also accept a second parameter allowing you to return a value if the sub field is an array. More examples of `@sub` can be found within' the repeatable field examples listed above.

```php
<ul>
  @fields('list')
    <li>@sub('item')</li>
  @endfields
</ul>

<ul class="slider">
  @fields('slides')
    <li class="slide">
      <img src="@sub('image', 'url')" alt="@sub('image', 'alt')" />
    </li>
  @endfields
</ul>
```

#### @hassub

`@hassub` is a simple conditional checking if the specified sub field returns a value.

Similar to `@hasfield`, it also accepts a second parameter to check an array value. It can be closed using `@endsub`.

```php
@hassub('icon')
  <i class="fa @sub('icon')"></i>
@endsub

@hassub('image', 'url')
  <img src="@sub('image', 'url')" alt="@sub('image', 'alt')" />
@endsub
```

#### @issub

`@issub` is a simple conditional for checking if your sub field equals a specified value. It can be closed using `@endsub`.

```php
@issub('icon', 'arrow')
  <i class="fa fa-arrow-up fa-rotate-90"></i>
@endsub
```

#### @layouts

`@layouts` acts as a helper for handling flexible content fields. Under the hood, it is essentially the exact same as `@fields`, but is provided to allow for a more clean, readable code-base in conjunction with `@layout` which calls `get_row_layout()`.

As with `@fields`, it accepts a post ID as a second parameter. It can be closed using `@endlayouts`.

```php
@layouts('components')
  [...]
@endlayouts

@layouts('components', 123)
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

`@group` acts as a helper for handling group fields. Under the hood, it is essentially the exact same as `@fields` and thus serves as a simple alias for code readability purposes. Which one you prefer is entirely up to you. It can be closed using `@endgroup`.

```php
@group('button')
  @hassub('url')
    <a href="@sub('url')" class="button button--@sub('color')">
      @sub('label')
    </a>
  @endsub
@endgroup
```

#### @option

`@option` echo's the specified theme options field using `get_field($field, 'option')`. As with the other field directives, it accepts a second parameter allowing you to retrieve a value if the option field returns an array.

```php
Find us on <a href="@option('facebook_url')" target="_blank">Facebook</a>

<div class="navbar-brand">
  <a class="navbar-item" href="{{ home_url() }}">
    <img src="@option('logo', 'url')" alt="{{ get_bloginfo('name', 'display') }}" />
  </a>
</div>
```

#### @hasoption

`@hasoption` is a simple conditional checking if the specified theme option field returns a value. It can be closed using `@endoption`.

```php
@hasoption('facebook_url')
  Find us on <a href="@option('facebook_url')" target="_blank">Facebook</a>
@endoption
```

#### @isoption

`@isoption` is a simple conditional for checking if your theme option field equals a specified value. It can be closed using `@endoption`.

```php
@isoption('logo_type', 'svg')
  [...]
@endoption
```

#### @options

`@options` acts as a helper for handling repeater and group fields within' your theme options. Under the hood, it is essentially the exact same as `@fields` except it automatically has the theme options ID `'option'` passed to it. It can be closed using `@endoptions`.

```php
@hasoption('social')
  <ul class="social">
    @options('social')
      <li>
        <a href="@sub('url')" target="_blank">
          @hassub('icon')
            <i class="icon fa-@sub('icon')"></i>
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

#### @condition

`@condition` is a simple `if` condition that checks the first parameter passed, and if it equals true, echo's the value passed in the second parameter.

```php
@if ($phone = App::phone())
  Call us at <a href="#">{{ App::phone() }}</a>
@endif

<a href="#" class="text @condition($phone, 'is-hidden-mobile')">
  Visit our website
</a>
```

#### @global

`@global` global's the specified variable.

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

`@extract` extracts the passed array into variables. I find this particularly useful when I want to make my views customizable when passing parameters to `@include` but also having default values set within' the view.

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

`@implode` echo's a string containing a representation of each element within' the array passed to it with a glue string between each element.

```php
@implode(', ' ['dog', 'cat', 'mouse', 'snake'])
// dog, cat, mouse, snake
```
