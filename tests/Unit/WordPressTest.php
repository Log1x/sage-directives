<?php

describe('@query', function () {
    it('compiles correctly', function () {
        $directive = "@query(['post_type' => 'post'])";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php global \$query; ?><?php \$query = new WP_Query(['post_type' => 'post']); ?>");
    });
});

describe('@posts', function () {
    it('compiles correctly', function () {
        $directive = '@posts';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php if (empty($query)) : ?><?php global $wp_query; ?><?php $query = $wp_query; ?><?php endif; ?><?php if ($query->have_posts()) : ?><?php while ($query->have_posts()) : $query->the_post(); ?>');
    });

    it('compiles correctly with options array', function () {
        $directive = "@posts(['post_type' => 'post'])";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php \$posts = collect(); ?><?php if (is_a(['post_type' => 'post'], 'WP_Post') || is_numeric(['post_type' => 'post'])) : ?><?php \$posts->put('p', is_a(['post_type' => 'post'], 'WP_Post') ? (['post_type' => 'post'])->ID : ['post_type' => 'post']); ?><?php endif; ?><?php if (is_array(['post_type' => 'post'])) : ?><?php \$posts ->put('ignore_sticky_posts', true) ->put('posts_per_page', -1) ->put('post__in', collect(['post_type' => 'post']) ->map(function (\$post) { return is_a(\$post, 'WP_Post') ? \$post->ID : \$post; })->all()) ->put('orderby', 'post__in'); ?><?php endif; ?><?php \$query = \$posts->isNotEmpty() ? new WP_Query(\$posts->all()) : ['post_type' => 'post']; ?><?php if (\$query->have_posts()) : while (\$query->have_posts()) : \$query->the_post(); ?>");
    });
});

describe('@endposts', function () {
    it('compiles correctly', function () {
        $directive = '@endposts';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php endwhile; wp_reset_postdata(); endif; ?>');
    });
});

describe('@hasposts', function () {
    it('compiles correctly', function () {
        $directive = '@hasposts';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php if (empty($query)) : ?><?php global $wp_query; ?><?php $query = $wp_query; ?><?php endif; ?><?php if ($query->have_posts()) : ?>');
    });

    it('compiles correctly with options array', function () {
        $directive = "@hasposts(['post_type' => 'post'])";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php \$posts = collect(); ?><?php if (is_a(['post_type' => 'post'], 'WP_Post') || is_numeric(['post_type' => 'post'])) : ?><?php \$posts->put('p', is_a(['post_type' => 'post'], 'WP_Post') ? (['post_type' => 'post'])->ID : ['post_type' => 'post']); ?><?php endif; ?><?php if (is_array(['post_type' => 'post'])) : ?><?php \$posts ->put('ignore_sticky_posts', true) ->put('posts_per_page', -1) ->put('post__in', collect(['post_type' => 'post']) ->map(function (\$post) { return is_a(\$post, 'WP_Post') ? \$post->ID : \$post; })->all()) ->put('orderby', 'post__in'); ?><?php endif; ?><?php \$query = \$posts->isNotEmpty() ? new WP_Query(\$posts->all()) : ['post_type' => 'post']; ?><?php if (\$query->have_posts()) : ?>");
    });
});

describe('@endhasposts', function () {
    it('compiles correctly', function () {
        $directive = '@endhasposts';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php wp_reset_postdata(); endif; ?>');
    });
});

describe('@noposts', function () {
    it('compiles correctly', function () {
        $directive = '@noposts';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php if (empty($query)) : ?><?php global $wp_query; ?><?php $query = $wp_query; ?><?php endif; ?><?php if (! $query->have_posts()) : ?>');
    });

    it('compiles correctly with options array', function () {
        $directive = "@noposts(['post_type' => 'post'])";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php \$posts = collect(); ?><?php if (is_a(['post_type' => 'post'], 'WP_Post') || is_numeric(['post_type' => 'post'])) : ?><?php \$posts->put('p', is_a(['post_type' => 'post'], 'WP_Post') ? (['post_type' => 'post'])->ID : ['post_type' => 'post']); ?><?php endif; ?><?php if (is_array(['post_type' => 'post'])) : ?><?php \$posts ->put('ignore_sticky_posts', true) ->put('posts_per_page', -1) ->put('post__in', collect(['post_type' => 'post']) ->map(function (\$post) { return is_a(\$post, 'WP_Post') ? \$post->ID : \$post; })->all()) ->put('orderby', 'post__in'); ?><?php endif; ?><?php \$query = \$posts->isNotEmpty() ? new WP_Query(\$posts->all()) : ['post_type' => 'post']; ?><?php if (! \$query->have_posts()) : ?>");
    });
});

describe('@endnoposts', function () {
    it('compiles correctly', function () {
        $directive = '@endnoposts';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php wp_reset_postdata(); endif; ?>');
    });
});

describe('@postmeta', function () {
    it('compiles correctly', function () {
        $directive = "@postmeta('foo')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_post_meta(get_the_ID(), 'foo', false); ?>");
    });

    it('compiles correctly with object', function () {
        $directive = "@postmeta('foo', \$post->ID)";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_post_meta(\$post->ID, 'foo', false); ?>");
    });

    it('compiles correctly with post', function () {
        $directive = "@postmeta('foo', 1)";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_post_meta(1, 'foo', false); ?>");
    });

    it('compiles correctly with post and single', function () {
        $directive = "@postmeta('foo', 1, true)";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_post_meta(1, 'foo', true); ?>");
    });
});

describe('@title', function () {
    it('compiles correctly', function () {
        $directive = '@title';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php echo get_the_title(); ?>');
    });

    it('compiles correctly with post', function () {
        $directive = '@title(1)';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php echo get_the_title(1); ?>');
    });
});

describe('@content', function () {
    it('compiles correctly', function () {
        $directive = '@content';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php the_content(); ?>');
    });
});

describe('@excerpt', function () {
    it('compiles correctly', function () {
        $directive = '@excerpt';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php the_excerpt(); ?>');
    });
});

describe('@permalink', function () {
    it('compiles correctly', function () {
        $directive = '@permalink';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php echo get_permalink(); ?>');
    });

    it('compiles correctly with post', function () {
        $directive = '@permalink(1)';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php echo get_permalink(1); ?>');
    });
});

describe('@thumbnail', function () {
    it('compiles correctly', function () {
        $directive = '@thumbnail';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_the_post_thumbnail(get_the_ID(), 'thumbnail'); ?>");
    });

    it('compiles correctly with post', function () {
        $directive = '@thumbnail(1)';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_the_post_thumbnail(1, 'thumbnail'); ?>");
    });

    it('compiles correctly with size', function () {
        $directive = "@thumbnail('medium')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_the_post_thumbnail(get_the_ID(), 'medium'); ?>");
    });

    it('compiles correctly with post and size', function () {
        $directive = "@thumbnail(1, 'medium')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_the_post_thumbnail(1, is_numeric('medium') ? ['medium', 'medium'] : 'medium'); ?>");
    });

    it('compiles correctly with post and custom size', function () {
        $directive = '@thumbnail(1, 128)';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php echo get_the_post_thumbnail(1, is_numeric(128) ? [128, 128] : 128); ?>');
    });

    it('compiles correctly with using URL', function () {
        $directive = '@thumbnail(false)';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); ?>");
    });

    it('compiles correctly with ID and using URL', function () {
        $directive = '@thumbnail(1, false)';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_the_post_thumbnail_url(1, 'thumbnail'); ?>");
    });

    it('compiles correctly with size and using URL', function () {
        $directive = "@thumbnail('medium', false)";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>");
    });

    it('compiles correctly with post, size and using URL', function () {
        $directive = "@thumbnail(1, 'medium', false)";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_the_post_thumbnail_url(1, is_numeric('medium') ? ['medium', 'medium'] : 'medium'); ?>");
    });

    it('compiles correctly with post, custom size and using URL', function () {
        $directive = '@thumbnail(1, 128, false)';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php echo get_the_post_thumbnail_url(1, is_numeric(128) ? [128, 128] : 128); ?>');
    });
});

describe('@author', function () {
    it('compiles correctly', function () {
        $directive = '@author';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_the_author_meta('display_name'); ?>");
    });

    it('compiles correctly with post', function () {
        $directive = '@author(1)';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_the_author_meta('display_name', 1); ?>");
    });
});

describe('@authorurl', function () {
    it('compiles correctly', function () {
        $directive = '@authorurl';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename')); ?>");
    });

    it('compiles correctly with post', function () {
        $directive = '@authorurl(1)';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_author_posts_url(1, get_the_author_meta('user_nicename', 1)); ?>");
    });
});

describe('@published', function () {
    it('compiles correctly', function () {
        $directive = '@published';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php echo get_the_date(); ?>');
    });

    it('compiles correctly with post', function () {
        $directive = '@published(1)';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_the_date('', 1); ?>");
    });

    it('compiles correctly with format', function () {
        $directive = "@published('F j, Y')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_the_date('F j, Y'); ?>");
    });

    it('compiles correctly with post and format', function () {
        $directive = "@published('F j, Y', 1)";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_the_date('F j, Y', 1); ?>");
    });
});

describe('@modified', function () {
    it('compiles correctly', function () {
        $directive = '@modified';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php echo get_the_modified_date(); ?>');
    });

    it('compiles correctly with post', function () {
        $directive = '@modified(1)';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_the_modified_date('', 1); ?>");
    });

    it('compiles correctly with format', function () {
        $directive = "@modified('F j, Y')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_the_modified_date('F j, Y'); ?>");
    });

    it('compiles correctly with post and format', function () {
        $directive = "@modified('F j, Y', 1)";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_the_modified_date('F j, Y', 1); ?>");
    });
});

describe('@category', function () {
    it('compiles correctly', function () {
        $directive = '@category';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php if (collect(get_the_category())->isNotEmpty()) : ?><?php echo collect(get_the_category())->shift()->name; ?><?php endif; ?>');
    });

    it('compiles correctly with post', function () {
        $directive = '@category(1)';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php if (collect(get_the_category(1))->isNotEmpty()) : ?><?php echo collect(get_the_category(1))->shift()->name; ?><?php endif; ?>');
    });

    it('compiles correctly as link', function () {
        $directive = '@category(true)';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php if (collect(get_the_category())->isNotEmpty()) : ?><a href="<?php echo get_category_link(collect(get_the_category())->shift()->cat_ID); ?>"><?php echo collect(get_the_category())->shift()->name; ?></a><?php endif; ?>');
    });

    it('compiles correctly as link with post', function () {
        $directive = '@category(1, true)';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php if (collect(get_the_category(1))->isNotEmpty()) : ?><a href="<?php echo get_category_link(collect(get_the_category(1))->shift()->cat_ID); ?>"><?php echo collect(get_the_category(1))->shift()->name; ?></a><?php endif; ?>');
    });
});

describe('@categories', function () {
    it('compiles correctly', function () {
        $directive = '@categories';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo strip_tags(get_the_category_list(', ', '', get_the_ID())); ?>");
    });

    it('compiles correctly with post', function () {
        $directive = '@categories(1)';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo strip_tags(get_the_category_list(', ', '', 1)); ?>");
    });

    it('compiles correctly as links', function () {
        $directive = '@categories(true)';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_the_category_list(', ', '', get_the_ID()); ?>");
    });

    it('compiles correctly as links with post', function () {
        $directive = '@categories(1, true)';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_the_category_list(', ', '', 1); ?>");
    });
});

describe('@term', function () {
    it('compiles correctly', function () {
        $directive = "@term('genre')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php if (get_the_terms(get_the_ID(), 'genre')) : ?><?php echo collect(get_the_terms(get_the_ID(), 'genre'))->shift()->name; ?><?php endif; ?>");
    });

    it('compiles correctly with post', function () {
        $directive = "@term('genre', 1)";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php if (get_the_terms(1, 'genre')) : ?><?php echo collect(get_the_terms(1, 'genre'))->shift()->name; ?><?php endif; ?>");
    });

    it('compiles correctly as link', function () {
        $directive = "@term('genre', true)";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php if (get_the_terms(get_the_ID(), 'genre')) : ?><a href=\"<?php echo get_term_link(collect(get_the_terms(get_the_ID(), 'genre'))->shift()->term_id); ?>\"><?php echo collect(get_the_terms(get_the_ID(), 'genre'))->shift()->name; ?></a><?php endif; ?>");
    });

    it('compiles correctly as link with post', function () {
        $directive = "@term('genre', 1, true)";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php if (get_the_terms(1, 'genre')) : ?><a href=\"<?php echo get_term_link(collect(get_the_terms(1, 'genre'))->shift()->term_id); ?>\"><?php echo collect(get_the_terms(1, 'genre'))->shift()->name; ?></a><?php endif; ?>");
    });
});

describe('@terms', function () {
    it('compiles correctly', function () {
        $directive = "@terms('genre')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo strip_tags(get_the_term_list(get_the_ID(), 'genre', '', ', ')); ?>");
    });

    it('compiles correctly with post', function () {
        $directive = "@terms('genre', 1)";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo strip_tags(get_the_term_list(1, 'genre', '', ', ')); ?>");
    });

    it('compiles correctly as links', function () {
        $directive = "@terms('genre', true)";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_the_term_list(get_the_ID(), 'genre', '', ', '); ?>");
    });

    it('compiles correctly as links with post', function () {
        $directive = "@terms('genre', 1, true)";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo get_the_term_list(1, 'genre', '', ', '); ?>");
    });
});

describe('@image', function () {
    it('compiles correctly', function () {
        $directive = '@image(1)';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo wp_get_attachment_image(1, 'thumbnail', false); ?>");
    });

    it('compiles correctly with size', function () {
        $directive = "@image(1, 'medium')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo wp_get_attachment_image(1, 'medium', false); ?>");
    });

    it('compiles correctly with size and alt tag', function () {
        $directive = "@image(1, 'medium', 'Alt Text')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo wp_get_attachment_image(1,'medium',false,['alt' => 'Alt Text']); ?>");
    });

    it('compiles correctly with size and options array', function () {
        $directive = "@image(1, 'medium', ['alt' => 'Alt Text', 'class' => 'class'])";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo wp_get_attachment_image(1,'medium',false,['alt' => 'Alt Text','class' => 'class']); ?>");
    });

    it('compiles correctly as a raw URL', function () {
        $directive = "@image(1, 'raw')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php echo wp_get_attachment_url(1); ?>');
    });

    it('compiles correctly using a field name', function () {
        $directive = "@image('image')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo wp_get_attachment_image('image', 'thumbnail', false); ?>");
    });

    it('compiles correctly using a field name and size', function () {
        $directive = "@image('image', 'medium')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo wp_get_attachment_image('image', 'medium', false); ?>");
    });

    it('compiles correctly using a field name, size and alt tag', function () {
        $directive = "@image('image', 'medium', 'Alt Text')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo wp_get_attachment_image('image','medium',false,['alt' => 'Alt Text']); ?>");
    });

    it('compiles correctly using a field name, size and options array', function () {
        $directive = "@image('image', 'medium', ['alt' => 'Alt Text', 'class' => 'class'])";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo wp_get_attachment_image('image','medium',false,['alt' => 'Alt Text','class' => 'class']); ?>");
    });
});

describe('@role', function () {
    it('compiles correctly', function () {
        $directive = "@role('editor')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php if (is_user_logged_in() && in_array(strtolower('editor'), (array) wp_get_current_user()->roles)) : ?>");
    });

    it('compiles correctly with multiple roles', function () {
        $directive = "@role('editor', 'author', 'contributor')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php if (is_user_logged_in() && in_array(strtolower('editor'), (array) wp_get_current_user()->roles) && in_array(strtolower('author'), (array) wp_get_current_user()->roles) && in_array(strtolower('contributor'), (array) wp_get_current_user()->roles)) : ?>");
    });
});

describe('@endrole', function () {
    it('compiles correctly', function () {
        $directive = '@endrole';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php endif; ?>');
    });
});

describe('@user', function () {
    it('compiles correctly', function () {
        $directive = '@user';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php if (is_user_logged_in()) : ?>');
    });
});

describe('@enduser', function () {
    it('compiles correctly', function () {
        $directive = '@enduser';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php endif; ?>');
    });
});

describe('@guest', function () {
    it('compiles correctly', function () {
        $directive = '@guest';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php if (! is_user_logged_in()) : ?>');
    });
});

describe('@endguest', function () {
    it('compiles correctly', function () {
        $directive = '@endguest';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php endif; ?>');
    });
});

describe('@shortcode', function () {
    it('compiles correctly', function () {
        $directive = "@shortcode('[gallery]')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo do_shortcode('[gallery]'); ?>");
    });
});

describe('@wpautop', function () {
    it('compiles correctly', function () {
        $directive = "@wpautop('This is a paragraph')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo wpautop('This is a paragraph'); ?>");
    });
});

describe('@wpautokp', function () {
    it('compiles correctly', function () {
        $directive = "@wpautokp('This is a paragraph')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo wpautop(wp_kses_post('This is a paragraph')); ?>");
    });
});

describe('@action', function () {
    it('compiles correctly', function () {
        $directive = "@action('wp_head')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php do_action('wp_head'); ?>");
    });
});

describe('@filter', function () {
    it('compiles correctly', function () {
        $directive = "@filter('the_title')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo apply_filters('the_title'); ?>");
    });
});

describe('@wphead', function () {
    it('compiles correctly', function () {
        $directive = '@wphead';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php wp_head(); ?>');
    });
});

describe('@wpfooter', function () {
    it('compiles correctly', function () {
        $directive = '@wpfooter';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php wp_footer(); ?>');
    });
});

describe('@bodyclass', function () {
    it('compiles correctly', function () {
        $directive = '@bodyclass';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<?php body_class(); ?>');
    });
});

describe('@wpbodyopen', function () {
    it('compiles correctly', function () {
        $directive = '@wpbodyopen';

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php if (function_exists('wp_body_open')) { wp_body_open(); } else { do_action('wp_body_open'); } ?>");
    });
});

describe('@__', function () {
    it('compiles correctly', function () {
        $directive = "@__('Hello World', 'sage')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php _e('Hello World', 'sage'); ?>");
    });
});
