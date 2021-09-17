# ACF

The following directives are for use with Advanced Custom Fields. If ACF is not installed and activated, they will not be initialized.

## @field

`@field` echo's the specified field using `get_field()`.

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

## @hasfield

`@hasfield` is a simple conditional checking if the specified field exists and is not empty. It can be closed using `@endfield`.

```php
@hasfield('list')
  <ul>
    @fields('list')
      <li>@sub('item')</li>
    @endfields
  </ul>
@endfield
```

To check the existance of a field for a specific post, you can pass a post ID as a second parameter:

```php
@hasfield('list', 5)
  <ul>
    @fields('list', 5)
      <li>@sub('item')</li>
    @endfield
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

To check the existance of a field that is an array for a specific post, you can pass the array key as a second parameter and the post ID as a third parameter:

```php
@hasfield('image', 'url', 1)
  <figure class="image">
    <img src="@field('image', 'url', 1)" alt="@field('image', 'alt', 1)" />
  </figure>
@endfield
```

## @isfield

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

## @fields

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

## @hasfields

`@hasfields` is a simple conditional checking if a parent field (such as Repeater or Flexible Content) has any rows of data to loop over. It can be closed using `@endhasfields`

```php
@hasfields('list')
    <ul>
        @fields('list')
            <li>@sub('item')</li>
        @endfields
    </ul>
@endhasfields
```

To check the existance of a parent field (such as Repeater or Flexible Content) for a specific post, you can pass a post ID as a second parameter:

```php
@hasfields('lists', 5)
    <ul>
        @fields('list', 5)
            <li>@sub('item')</li>
        @endfields
    </ul>
@endhasfields
```

## @sub

`@sub` echo's the specified sub field using [`get_sub_field()`](https://www.advancedcustomfields.com/resources/get_sub_field/). It is to be used inside of repeatable fields such as `@fields`, `@layout`, `@group`, and `@options`.

```php
<ul>
  @fields('list')
    <li>@sub('item')</li>
  @endfields
</ul>
```

If the sub field is an array, you can pass the key(s) as additional parameters:

```php
<ul class="slider">
  @fields('slides')
    <li class="slide">
      <img src="@sub('image', 'url')" alt="@sub('image', 'alt')" />
    </li>
  @endfields
</ul>
```

```php
<ul class="slider">
  @fields('slides')
    <li class="slide">
      <img src="@sub('image', 'sizes', 'thumbnail')" alt="@sub('image', 'alt')" />
    </li>
  @endfields
</ul>
```

More usage of `@sub` can be found alongside the examples of the repeatable fields listed above.

## @hassub

`@hassub` is a simple conditional checking if the specified field exists and is not empty. It can be closed using `@endsub`.

```php
@hassub('icon')
  <i class="fas fa-@sub('icon')"></i>
@endsub
```

If the sub field you are checking against is an array, you can pass the key(s) as additional parameters:

```php
@hassub('image', 'url')
  <img src="@sub('image', 'url')" alt="@sub('image', 'alt')" />
@endsub
```

```php
@hassub('image', 'sizes', 'thumbnail')
  <img src="@sub('image', 'sizes', 'thumbnail')" alt="@sub('image', 'alt')" />
@endsub
```

## @issub

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

## @layouts

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

## @layout

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

## @group

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

## @option

`@option` echo's the specified theme options field using `get_field($field, 'option')`.

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

## @hasoption

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

## @isoption

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

## @options

`@options` acts as a helper for handling repeater and group fields within' your theme options. Under the hood, it is essentially the exact same as `@fields` and `@group` except it automatically has the theme options ID `'option'` passed to it. It can be closed using `@endoptions`.

```php
@hasoptions('social')
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
@endhasoptions
```

## @hasoptions

`@hasoptions` is a simple conditional checking if a parent field from your theme options (such as Repeater or Flexible Content) has any rows of data to loop over. It can be closed using `@endhasoptions`

```php
@hasoptions('socials')
    <ul>
        @options('socials')
            <li>@sub('social')</li>
        @endoptions
    </ul>
@endhasoptions
```


## @block

`@block` renders custom [ACF Blocks](https://www.advancedcustomfields.com/resources/blocks/). See [ACF Composer](https://github.com/Log1x/acf-composer) for a sage10 friendly way of creating ACF Blocks.

Assuming you have generated the [ACF Composer Example block](https://github.com/Log1x/acf-composer#generating-a-block).

```php
@block('example')
```

We assume the block prefix is `acf`, ie. `acf/example` - you can change it if you've set it to something else

```php
@block('yourprefix/example')
```

Passing data

```php
// Example array of acf field data
$data = ['items' => [['item' => 'Item one'], ['item' => 'Item two'], ['item' => 'Item three']]];

@block('example', $data)
```

