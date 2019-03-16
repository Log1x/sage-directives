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
    | @title / @content / @excerpt / @permalink
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

    'permalink' => function ($expression) {
        if (! empty($expression)) {
            return "<?php get_permalink({$expression}); ?>";
        }

        return "<?php get_permalink(); ?>";
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
    | @category / @term
    |---------------------------------------------------------------------
    */

    'category' => function ($expression) {
        $expression = Util::parse($expression);

        if ($expression->get(1) === 'true') {
            return "<?php if (collect(get_the_category({$expression->get(0)}))->isNotEmpty()) : ?>".
                    "<a href=\"<?= get_category_link(collect(get_the_category({$expression->get(0)}))->pop()->cat_ID); ?>\">".
                    "<?= collect(get_the_category({$expression->get(0)}))->pop()->name; ?>".
                    "</a>".
                    "<?php endif; ?>";
        }

        if (! empty($expression->get(0))) {
            if ($expression->get(0) === 'true') {
                return "<?php if (collect(get_the_category())->isNotEmpty()) : ?>".
                       "<a href=\"<?= get_category_link(collect(get_the_category())->pop()->cat_ID); ?>\">".
                       "<?= collect(get_the_category())->pop()->name; ?>".
                       "</a>".
                       "<?php endif; ?>";
            }

            return "<?php if (collect(get_the_category({$expression->get(0)}))->isNotEmpty()) : ?>".
                   "<?= collect(get_the_category({$expression->get(0)}))->pop()->name; ?>".
                   "<?php endif; ?>";
        }

        return "<?php if (collect(get_the_category())->isNotEmpty()) : ?>".
               "<?= collect(get_the_category())->pop()->name; ?>".
               "<?php endif; ?>";
    },

    'term' => function ($expression) {
        $expression = Util::parse($expression);

        if (! empty($expression->get(2))) {
            return "<?php if (collect(get_the_terms({$expression->get(0)}, {$expression->get(1)}))->isNotEmpty()) : ?>".
                   "<a href=\"<?= get_term_link(collect(get_the_terms({$expression->get(0)}, {$expression->get(1)}))->pop()->term_ID); ?>\">".
                   "<?= collect(get_the_terms({$expression->get(0)}, {$expression->get(1)}))->pop()->name(); ?>".
                   "</a>".
                   "<?php endif; ?>";
        }

        if (! empty($expression->get(1))) {
            if ($expression->get(1) === 'true') {
                return "<?php if (collect(get_the_terms(get_the_ID(), {$expression->get(0)}))->isNotEmpty()) : ?>".
                       "<a href=\"<?= get_term_link(collect(get_the_terms(get_the_ID(), {$expression->get(0)}))->pop()->term_ID); ?>\">".
                       "<?= collect(get_the_terms(get_the_ID(), {$expression->get(0)}))->pop()->name(); ?>".
                       "</a>".
                       "<?php endif; ?>";
            }

            return "<?php if (collect(get_the_terms({$expression->get(0)}, {$expression->get(1)}))->isNotEmpty()) : ?>".
                   "<?= collect(get_the_terms({$expression->get(0)}, {$expression->get(1)}))->pop()->name(); ?>".
                   "<?php endif; ?>";
        }

        if (! empty($expression->get(0))) {
            return "<?php if (collect(get_the_terms(get_the_ID(), {$expression->get(0)}))->isNotEmpty()) : ?>".
                   "<?= collect(get_the_terms(get_the_ID(), {$expression->get(0)}))->pop()->name; ?>".
                   "<?php endif; ?>";
        }
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
