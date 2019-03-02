<?php

namespace Log1x\SageDirectives;

return [

    /*
    |--------------------------------------------------------------------------
    | WordPress Directives
    |--------------------------------------------------------------------------
    |
    | Directives for various WordPress use-cases.
    |
    */

    /*
    |---------------------------------------------------------------------
    | @query / @posts / @endposts
    |---------------------------------------------------------------------
    */

    'query' => function ($expression) {
        return "<?php global \$query; ?>".
               "<?php \$query = new WP_Query({$expression}); ?>";
    },

    'posts' => function ($expression) {
        if (! empty($expression)) {
            return "<?php \$posts = collect(); ?>".

                   "<?php if (is_a({$expression}, 'WP_Post') || is_numeric({$expression})) : ?>".
                   "<?php \$posts->put('p', is_a({$expression}, 'WP_Post') ? ({$expression})->ID : {$expression}); ?>".
                   "<?php endif; ?>".

                   "<?php if (is_array({$expression})) : ?>".
                   "<?php \$posts
                       ->put('ignore_sticky_posts', true)
                       ->put('posts_per_page', -1)
                       ->put('post__in', collect({$expression})
                           ->map(function (\$post) {
                               return is_a(\$post, 'WP_Post') ? \$post->ID : \$post;
                           })->all())
                       ->put('orderby', 'post__in');
                   ?>".
                   "<?php endif; ?>".

                   "<?php \$query = \$posts->isNotEmpty() ? new WP_Query(\$posts->all()) : {$expression}; ?>" .
                   "<?php if (\$query->have_posts()) : while (\$query->have_posts()) : \$query->the_post(); ?>";
        }

        return "<?php if (empty(\$query)) : ?>".
               "<?php global \$wp_query; ?>".
               "<?php \$query = \$wp_query; ?>".
               "<?php endif; ?>".

               "<?php if (\$query->have_posts()) : ?>".
               "<?php while (\$query->have_posts()) : \$query->the_post(); ?>";
    },

    'endposts' => function () {
        return "<?php endwhile; wp_reset_postdata(); endif; ?>";
    },

    /*
    |---------------------------------------------------------------------
    | @title / @content / @excerpt
    |---------------------------------------------------------------------
    */

    'title' => function ($expression) {
        if (! empty($expression)) {
            return "<?= get_the_title({$expression}); ?>";
        }

        return "<?= get_the_title(); ?>";
    },

    'content' => function () {
        return "<?php the_content(); ?>";
    },

    'excerpt' => function () {
        return "<?php the_excerpt(); ?>";
    },

    /*
    |---------------------------------------------------------------------
    | @author / @authorurl / @published / @modified
    |---------------------------------------------------------------------
    */

    'author' => function ($expression) {
        if (! empty($expression)) {
            return "<?= get_the_author_meta('user_nicename', {$expression}); ?>";
        }

        return "<?= get_the_author_meta('user_nicename'); ?>";
    },

    'authorurl' => function ($expression) {
        if (! empty($expression)) {
            return "<?= get_author_posts_url({$expression}, get_the_author_meta('user_nicename', {$expression})); ?>";
        }

        return "<?= get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename')); ?>";
    },

    'published' => function ($expression) {
        if (! empty($expression)) {
            return "<?php if (is_a({$expression}, 'WP_Post') || is_numeric({$expression})) : ?>".
                   "<?= get_the_date('', {$expression}); ?>".
                   "<?php endif; ?>".

                   "<?= get_the_date({$expression}); ?>";
        }

        return "<?= get_the_date(); ?>";
    },

    'modified' => function ($expression) {
        if (! empty($expression)) {
            return "<?php if (is_a({$expression}, 'WP_Post') || is_numeric({$expression})) : ?>".
                   "<?= get_the_modified_date('', {$expression}); ?>".
                   "<?php endif; ?>".

                   "<?= get_the_modified_date({$expression}); ?>";
        }

        return "<?= get_the_modified_date(); ?>";
    },

    /*
    |---------------------------------------------------------------------
    | @user / @enduser / @guest / @endguest
    |---------------------------------------------------------------------
    */

    'user' => function () {
        return "<?php if (is_user_logged_in()) : ?>";
    },

    'enduser' => function () {
        return "<?php endif; ?>";
    },

    'guest' => function () {
        return "<?php if (! is_user_logged_in()) : ?>";
    },

    'endguest' => function () {
        return "<?php endif; ?>";
    },

    /*
    |---------------------------------------------------------------------
    | @shortcode
    |---------------------------------------------------------------------
    */

    'shortcode' => function ($expression) {
        return "<?= do_shortcode({$expression}); ?>";
    },

    /*
    |---------------------------------------------------------------------
    | @wpautop / @wpautokp
    |---------------------------------------------------------------------
    */

    'wpautop' => function ($expression) {
        return "<?= wpautop({$expression}); ?>";
    },

    'wpautokp' => function ($expression) {
        return "<?= wpautop(wp_kses_post({$expression})); ?>";
    },

];
