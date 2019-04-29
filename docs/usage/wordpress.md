# WordPress

The following directives are specific to WordPress core functionality.

## @query

`@query` initializes a standard `WP_Query` as `$query` and accepts the usual `WP_Query` [parameters](https://codex.wordpress.org/Class_Reference/WP_Query#Parameters) as an array.

```py
@query([
  'post_type' => 'post'
])
```

## @posts

`@posts` begins the post loop and by default, assumes that `WP_Query` is set to `$query` (which is the case when using `@query`). It is wrapped in a `have_posts()` conditional and thus will return `null` if no posts are found.

`@endposts` is available to end your loop and `have_posts()` conditional as well as resetting your loop with [`wp_reset_postdata()`](https://codex.wordpress.org/Function_Reference/wp_reset_postdata).

```py
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

```py
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

```py
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

```py
@title
```

To echo the title of a specific post, you can pass the post ID or a `WP_Post` instance as a parameter:

```py
@title(1)
@title(get_post(1))
```

## @content

`@content` echo's the current posts content using [`the_content()`](https://developer.wordpress.org/reference/functions/the_content/).

```py
@content
```

## @excerpt

`@excerpt` echo's the current posts excerpt using [`the_excerpt()`](https://developer.wordpress.org/reference/functions/the_excerpt/).

```py 
@excerpt
```

## @permalink

`@permalink` echo's the current posts URL using [`get_permalink()`](https://developer.wordpress.org/reference/functions/get_permalink/).

```py
@permalink
```

To echo the URL of a specific post, you can pass the post ID or a `WP_Post` instance as a parameter:

```py
@permalink(1)
@permalink(get_post(1))
```

## @thumbnail 

`@thumbnail` echo's the current posts featured image using [`get_the_post_thumbnail`](https://developer.wordpress.org/reference/functions/get_the_post_thumbnail/). By default, it passes `thumbnail` as the size.

```py
@thumbnail
```

To echo the featured image of a specific post, you can pass the post ID or a `WP_Post` instance as the first parameter:

```py
@thumbnail(1)
@thumbnail(get_post(1))
```

To echo the featured image with a specific size, you can either pass it as a parameter by it's self, or as the second parameter when a post ID or `WP_Post` instance is present:

```py
@thumbnail('full')
@thumbnail(1, 'full')
@thumbnail(get_post(1), 'full')
```

To echo the featured image URL (without img markup), you can pass `false` as the last parameter on any of the above options:

```py
<img src="@thumbnail(false)" alt="My Image" />
<img src="@thumbnail(1, false)" alt="Post 1" />
<img src="@thumbnail(get_post(1), false)" alt="Post 1" />
<img src="@thumbnail('full', false)" alt="Full Image" />
<img src="@thumbnail(1, 'full', false)" alt="Post 1's Full Image" />
<img src="@thumbnail(get_post(1), 'full', false)" alt="Post 1's Full Image" />
```

## @author

`@author` echo's the author of the current posts display name.

```py
@author
```

To echo the display name of a specific author, you can pass the author's ID as a parameter:

```py
@author(1)
```

## @authorurl

`@authorurl` echo's the author of the current posts archive URL.

```py
<span class="author" itemprop="author" itemscope itemtype="http://schema.org/Person">
  <a href="@authorurl" itemprop="url">
    <span class="fn" itemprop="name" rel="author">@author</span>
  </a>
</span>
```

To echo the URL of a specific author, you can pass the author's ID as a parameter:

```py
<a href="@authorurl(2)">@author</a>
```

## @published

`@published` echo's the current posts published date. By default, it uses the date format set in `Settings > General`.

```py
@published
```

To change the [formatting of the date](https://codex.wordpress.org/Formatting_Date_and_Time), you can pass it as the first parameter:

```py
<time class="entry-time">
  <span>@published('F j, Y')</span>
  <span itemprop="datePublished" content="@published('c')"></span>
</time>
```

To echo the published date of a specific post, you can pass a post ID or an instance of `WP_Post` as the first parameter:

```py
@published(1)
@published(get_post(1))
```

To format the published date of a specific post, you can pass the format as the first parameter, and the post ID or instance of `WP_Post` as the second parameter:

```py
@published('F j, Y', 1)
@published('F j, Y', get_post(1))
```

## @modified

`@modified` is similar to `@published`, but instead echo's the current posts last modified date. By default, it uses the date format set in `Settings > General`.

```py
@modified
```

To change the [formatting of the date](https://codex.wordpress.org/Formatting_Date_and_Time), you can pass it as the first parameter:

```py
<time class="entry-time">
  <span>@published('F j, Y')</span>
  <span itemprop="datePublished" content="@published('c')"></span>
  <span itemprop="dateModified" class="updated" content="@modified('c')"></span>
</time>
```

To echo the modified date of a specific post, you can pass a post ID or an instance of `WP_Post` as the first parameter:

```py
@modified(1)
@modified(get_post(1))
```

To format the modified date of a specific post, you can pass the format as the first parameter, and the post ID or instance of `WP_Post` as the second parameter:

```py
@modified('F j, Y', 1)
@modified('F j, Y', get_post(1))
```

## @category

`@category` echo's the first category of the current post.

```py
@category
```

To echo the category as a link, pass `true` as a parameter:

```py
@category(true)
```

To echo the category of a specific post, pass the post ID as a parameter:

```py
@category(1)
```

To echo the category of a specific post as a link, pass the post ID as the first parameter, and `true` as the second parameter:

```py
@category(1, true)
```

## @categories

`@categories` echo's a comma seperated list of the current post's categories.

```py
@categories
```

To echo the categories of a specific post, pass the post ID as the first parameter:

```py
@categories(1)
```

Similar to `@category`, if you would like to return the categories as links, pass `true` as the first parameter when by it's self, or as the second parameter when a post ID is present:

```py
@categories(true)
@categories(1, true)
```

## @term

`@term` echo's the taxonomy term of the current post. If multiple terms are present, it will echo the first term returned in the array.

```py
@term('genre')
```

Similar to `@category`, if you would like to return the terms of a specific post or as links, you can follow the same syntax, except keeping the taxonomy name as the first parameter:

```py
@term('genre', 1)
@term('genre', 1, false)
@term('genre', false)
```

## @terms

`@terms` echo's a comma seperated list of the taxonomy terms of the current post.

```py
@terms('genre')
```

It accepts the same parameters as `@term`:

```py
@terms('genre', 1)
@terms('genre', 1, false)
@terms('genre', false)
```

## @shortcode

`@shortcode` echo's the specified shortcode using [`do_shortcode()`](https://developer.wordpress.org/reference/functions/do_shortcode/).

```py
@shortcode('[my-shortcode]')
```

## @role

`@role` is a simple conditional that allows you to display specific content only to users who are logged in and have a specific role. With [`wp_get_current_user()->roles`](https://codex.wordpress.org/Function_Reference/wp_get_current_user) returning an array of roles in all lowercase, the passed role is automatically lowercased using `strtolower`. It can be closed using `@endrole`.

```py
@role('author')
  This content is only displayed to Authors.
@endrole
```

## @user

`@user` is a simple `is_user_logged_in()` conditional to display specific content only when a user is logged in. It can be closed using `@enduser`.

```py
@user
  You are logged in!
@enduser
```

## @guest

`@guest` is a simple `! is_user_logged_in()` conditional to display specific content only when a user is not logged in. It can be closed using `@endguest`.

```py
@guest
  You must be <a href="/wp-login.php">logged in</a> to view this content.
@endguest
```

## @wpautop

`@wpautop` runs a passed string through [`wpautop()`](https://codex.wordpress.org/Function_Reference/wpautop) and echo's the output.

```py
@wpautop($content)
```

## @wpautokp

`@wpautokp` does the same as `@wpautop` but also sanitizes the string with [`wp_kses_post()`](https://codex.wordpress.org/Function_Reference/wp_kses_post).

```py
@wpautokp($content)
```
