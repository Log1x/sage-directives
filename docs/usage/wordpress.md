# WordPress

The following directives are specific to WordPress core functionality.

## @query

`@query` initializes a standard `WP_Query` as `$query` and accepts the usual `WP_Query` [parameters](https://codex.wordpress.org/Class_Reference/WP_Query#Parameters) as an array.

```php
@query([
  'post_type' => 'post'
])
```

## @posts

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

When passing an array of ID's / objects, the posts will be sorted by the order of the array.

If `@query` is not used and an argument is not passed to `@posts`, it will use the main loop from the `$wp_query` global.

## @title

`@title` echo's the current posts title using [`get_the_title()`](https://developer.wordpress.org/reference/functions/get_the_title/).

```php
@title
```

To echo the title of a specific post, you can pass the post ID or a `WP_Post` instance as a second parameter:

```php
@title(1)
@title(get_post(1))
```

## @content

`@content` echo's the current posts content using [`the_content()`](https://developer.wordpress.org/reference/functions/the_content/).

```php
@content
```

## @excerpt

`@excerpt` echo's the current posts excerpt using [`the_excerpt()`](https://developer.wordpress.org/reference/functions/the_excerpt/).

```php 
@excerpt
```

## @author

`@author` echo's the author of the current posts display name.

```php
@author
```

To echo the display name of a specific author, you can pass the author's ID as a second parameter:

```php
@author(1)
```

## @authorurl

`@authorurl` echo's the author of the current posts archive URL.

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

## @published

`@published` echo's the current posts published date. By default, it uses the date format set in `Settings > General`.

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

## @modified

`@modified` is similar to `@published`, but instead echo's the current posts last modified date. By default, it uses the date format set in `Settings > General`.

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

## @shortcode

`@shortcode` echo's the specified shortcode using [`do_shortcode()`](https://developer.wordpress.org/reference/functions/do_shortcode/).

```php
@shortcode('[my-shortcode]')
```

## @user

`@user` is a simple `is_user_logged_in()` conditional to display specific content only when a user is logged in. It can be closed using `@enduser`.

```php
@user
  You are logged in!
@enduser
```

## @guest

`@guest` is a simple `! is_user_logged_in()` conditional to display specific content only when a user is not logged in. It can be closed using `@endguest`.

```php
@guest
  You must be <a href="/wp-login.php">logged in</a> to view this content.
@endguest
```

## @wpautop

`@wpautop` runs a passed string through [`wpautop()`](https://codex.wordpress.org/Function_Reference/wpautop) and echo's the output.

```php
@wpautop($content)
```

## @wpautokp

`@wpautokp` does the same as `@wpautop` but also sanitizes the string with [`wp_kses_post()`](https://codex.wordpress.org/Function_Reference/wp_kses_post).

```php
@wpautokp($content)
```
