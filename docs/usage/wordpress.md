# WordPress

The following directives are specific to WordPress core functionality.

## @query

`@query` initializes a standard `WP_Query` as `$query` and accepts the usual `WP_Query` [parameters](https://developer.wordpress.org/reference/classes/wp_query/#parameters) as an array.

```php
@query([
  'post_type' => 'post'
])
```

## @posts

`@posts` begins the post loop and by default, assumes that `WP_Query` is set to `$query` (which is the case when using `@query`). It is wrapped in a `have_posts()` conditional and thus will return `null` if no posts are found.

`@endposts` is available to end your loop and `have_posts()` conditional as well as resetting your loop with [`wp_reset_postdata()`](https://developer.wordpress.org/reference/functions/wp_reset_postdata/).

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

## @hasposts

`@hasposts` accepts the same exact arguments as `@posts`, but simply runs a conditional without the while loop. It can be closed using `@endhasposts`.

```php
@hasposts
  <ul>
    @posts
      <li>@title</li>
    @endposts
  </ul>
@endhasposts
```

## @noposts

`@noposts` again has the exact same arguments as `@posts`, but runs a `!` conditional without the while loop. It can be closed using `@endnoposts`.

```php
@noposts
  <div class="py-2 px-4 bg-red-500 text-white">
    🏜️
  </div>
@endnoposts
```

## @title

`@title` echo's the current posts title using [`get_the_title()`](https://developer.wordpress.org/reference/functions/get_the_title/).

```php
@title
```

To echo the title of a specific post, you can pass the post ID or a `WP_Post` instance as a parameter:

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

## @permalink

`@permalink` echo's the current posts URL using [`get_permalink()`](https://developer.wordpress.org/reference/functions/get_permalink/).

```php
@permalink
```

To echo the URL of a specific post, you can pass the post ID or a `WP_Post` instance as a parameter:

```php
@permalink(1)
@permalink(get_post(1))
```

## @thumbnail 

`@thumbnail` echo's the current posts featured image using [`get_the_post_thumbnail`](https://developer.wordpress.org/reference/functions/get_the_post_thumbnail/). By default, it passes `thumbnail` as the size.

```php
@thumbnail
```

To echo the featured image of a specific post, you can pass the post ID or a `WP_Post` instance as the first parameter:

```php
@thumbnail(1)
@thumbnail(get_post(1))
```

To echo the featured image with a specific size, you can either pass it as a parameter by it's self, or as the second parameter when a post ID or `WP_Post` instance is present:

```php
@thumbnail('full')
@thumbnail(1, 'full')
@thumbnail(get_post(1), 'full')
```

To echo the featured image URL (without img markup), you can pass `false` as the last parameter on any of the above options:

```php
<img src="@thumbnail(false)" alt="My Image" />
<img src="@thumbnail(1, false)" alt="Post 1" />
<img src="@thumbnail(get_post(1), false)" alt="Post 1" />
<img src="@thumbnail('full', false)" alt="Full Image" />
<img src="@thumbnail(1, 'full', false)" alt="Post 1's Full Image" />
<img src="@thumbnail(get_post(1), 'full', false)" alt="Post 1's Full Image" />
```

## @author

`@author` echo's the author of the current posts display name.

```php
@author
```

To echo the display name of a specific author, you can pass the author's ID as a parameter:

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

To echo the URL of a specific author, you can pass the author's ID as a parameter:

```php
<a href="@authorurl(2)">@author</a>
```

## @published

`@published` echo's the current posts published date. By default, it uses the date format set in `Settings > General`.

```php
@published
```

To change the [formatting of the date](https://wordpress.org/support/article/formatting-date-and-time/), you can pass it as the first parameter:

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

To change the [formatting of the date](https://wordpress.org/support/article/formatting-date-and-time/), you can pass it as the first parameter:

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

## @category

`@category` echo's the first category of the current post.

```php
@category
```

To echo the category as a link, pass `true` as a parameter:

```php
@category(true)
```

To echo the category of a specific post, pass the post ID as a parameter:

```php
@category(1)
```

To echo the category of a specific post as a link, pass the post ID as the first parameter, and `true` as the second parameter:

```php
@category(1, true)
```

## @categories

`@categories` echo's a comma seperated list of the current post's categories.

```php
@categories
```

To echo the categories of a specific post, pass the post ID as the first parameter:

```php
@categories(1)
```

Similar to `@category`, if you would like to return the categories as links, pass `true` as the first parameter when by it's self, or as the second parameter when a post ID is present:

```php
@categories(true)
@categories(1, true)
```

## @term

`@term` echo's the taxonomy term of the current post. If multiple terms are present, it will echo the first term returned in the array.

```php
@term('genre')
```

Similar to `@category`, if you would like to return the terms of a specific post or as links, you can follow the same syntax, except keeping the taxonomy name as the first parameter:

```php
@term('genre', 1)
@term('genre', 1, false)
@term('genre', false)
```

## @terms

`@terms` echo's a comma seperated list of the taxonomy terms of the current post.

```php
@terms('genre')
```

It accepts the same parameters as `@term`:

```php
@terms('genre', 1)
@terms('genre', 1, false)
@terms('genre', false)
```

## @image 

`@image` echo's an image using [`wp_get_attachment_image()`](https://developer.wordpress.org/reference/functions/wp_get_attachment_image/).

Since I find this mostly useful with ACF fields (being that it automatically handles responsive image sizes), if ACF is present and a field name in the form of a `string` is passed as the first parameter, `@image` will attempt to use the built in [`Util::field()`](https://github.com/Log1x/sage-directives/blob/master/src/Utilities.php#L48-L74) utility to deep-dive `get_field()` and `get_sub_field()` to retrieve your image field, and if it returns as an array instead of `id`, automatically check for the existance of `$image['id']` and pass that value to `wp_get_attachment_image()`.

By default, `@image` uses the `thumbnail` image size and the default media library attachment `alt` tag.

```php
@image(1)
```

Optionally, pass it an image size and an alt tag:

```php
@image(1, 'full', 'My alt tag')
```

If you require an image without a set `width`, `height`, or `srcset`, our friends at WordPress core [don't agree](https://core.trac.wordpress.org/ticket/14110) with you and their word is law.

But since we do what we want, you can pass `raw` as an image size to return the attachment URL and build the image markup yourself.

```php
<img src="@image(1, 'raw')" alt="Take that, WordPress." />
```

Outside of a `raw` image, if you need access to the `<img>` tag attributes directly, use an array as the third parameter instead:

```php
@image(1, 'thumbnail', ['alt' => 'My alt tag', 'class' => 'block w-32 h-32'])
```

Accessing an ACF field, sub field, or even option field is just as easy:

```php
@image('my_image_field')
@image('my_image_field', 'full', 'My alt tag')
@image('my_image_field', 'thumbnail', ['alt' => 'My alt tag', 'class' => 'block w-32 h-32'])

<img src="@image('my_image_field', 'raw')" alt="My alt tag" />
```

## @shortcode

`@shortcode` echo's the specified shortcode using [`do_shortcode()`](https://developer.wordpress.org/reference/functions/do_shortcode/).

```php
@shortcode('[my-shortcode]')
```

## @role

`@role` is a simple conditional that allows you to display specific content only to users who are logged in and have a specific role. With [`wp_get_current_user()->roles`](https://developer.wordpress.org/reference/functions/wp_get_current_user/) returning an array of roles in all lowercase, the passed role is automatically lowercased using `strtolower`. It can be closed using `@endrole`.

```php
@role('author')
  This content is only displayed to Authors.
@endrole
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

`@wpautop` runs a passed string through [`wpautop()`](https://developer.wordpress.org/reference/functions/wpautop/) and echo's the output.

```php
@wpautop($content)
```

## @wpautokp

`@wpautokp` does the same as `@wpautop` but also sanitizes the string with [`wp_kses_post()`](https://developer.wordpress.org/reference/functions/wp_kses_post/).

```php
@wpautokp($content)
```

## @action

`@action` allows you to trigger a WordPress action through the use of [`do_action`](https://developer.wordpress.org/reference/functions/do_action/).

```php
@action('get_footer')
```

## @filter

`@filter` echoes the result of what its been passed through the use of [`apply_filters`](https://developer.wordpress.org/reference/functions/apply_filters/).

```php
@filter('the_content', 'some string')
```

## @wphead

This is just a directive version of [`wp_head`](https://developer.wordpress.org/reference/functions/wp_head/).

## @wpfoot

This is just a directive version of [`wp_footer`](https://developer.wordpress.org/reference/functions/wp_footer/).

## @bodyclass

`@bodyclass` behaves just as [`body_class`](https://developer.wordpress.org/reference/functions/body_class/) does. You can pass strings to it to have them added to the body class. Has the same limitations on passing arrays as any other directive.

```php
@bodyclass('add-me')
```

## @wpbodyopen

This is a directive version of [`wp_body_open`](https://developer.wordpress.org/reference/functions/wp_body_open/) except that it will work on WordPress versions below 5.2.
