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
        return "<?php global \$query; ?>" .
               "<?php \$query = new WP_Query({$expression}); ?>";
    },

    'posts' => function ($expression) {
        if (! empty($expression)) {
            return "<?php \$posts = collect(); ?>" .

                   "<?php if (is_a({$expression}, 'WP_Post') || is_numeric({$expression})) : ?>" .
                   "<?php \$posts->put('p', is_a({$expression}, 'WP_Post') ? ({$expression})->ID : {$expression}); ?>" .
                   "<?php endif; ?>" .

                   "<?php if (is_array({$expression})) : ?>" .
                   "<?php \$posts
                       ->put('ignore_sticky_posts', true)
                       ->put('posts_per_page', -1)
                       ->put('post__in', collect({$expression})
                           ->map(function (\$post) {
                               return is_a(\$post, 'WP_Post') ? \$post->ID : \$post;
                           })->all())
                       ->put('orderby', 'post__in');
                   ?>" .
                   "<?php endif; ?>" .

                   "<?php \$query = \$posts->isNotEmpty() ? new WP_Query(\$posts->all()) : {$expression}; ?>" .
                   "<?php if (\$query->have_posts()) : while (\$query->have_posts()) : \$query->the_post(); ?>";
        }

        return "<?php if (empty(\$query)) : ?>" .
               "<?php global \$wp_query; ?>" .
               "<?php \$query = \$wp_query; ?>" .
               "<?php endif; ?>" .

               "<?php if (\$query->have_posts()) : ?>" .
               "<?php while (\$query->have_posts()) : \$query->the_post(); ?>";
    },

    'endposts' => function () {
        return "<?php endwhile; wp_reset_postdata(); endif; ?>";
    },

    /*
    |---------------------------------------------------------------------
    | @hasposts / @endhasposts / @noposts / @endnoposts
    |---------------------------------------------------------------------
    */

    'hasposts' => function ($expression) {
        if (! empty($expression)) {
            return "<?php \$posts = collect(); ?>" .

                   "<?php if (is_a({$expression}, 'WP_Post') || is_numeric({$expression})) : ?>" .
                   "<?php \$posts->put('p', is_a({$expression}, 'WP_Post') ? ({$expression})->ID : {$expression}); ?>" .
                   "<?php endif; ?>" .

                   "<?php if (is_array({$expression})) : ?>" .
                   "<?php \$posts
                       ->put('ignore_sticky_posts', true)
                       ->put('posts_per_page', -1)
                       ->put('post__in', collect({$expression})
                           ->map(function (\$post) {
                               return is_a(\$post, 'WP_Post') ? \$post->ID : \$post;
                           })->all())
                       ->put('orderby', 'post__in');
                   ?>" .
                   "<?php endif; ?>" .

                   "<?php \$query = \$posts->isNotEmpty() ? new WP_Query(\$posts->all()) : {$expression}; ?>" .
                   "<?php if (\$query->have_posts()) : ?>";
        }

        return "<?php if (empty(\$query)) : ?>" .
               "<?php global \$wp_query; ?>" .
               "<?php \$query = \$wp_query; ?>" .
               "<?php endif; ?>" .

               "<?php if (\$query->have_posts()) : ?>";
    },

    'endhasposts' => function () {
        return "<?php wp_reset_postdata(); endif; ?>";
    },

    'noposts' => function ($expression) {
        if (! empty($expression)) {
            return "<?php \$posts = collect(); ?>" .

                   "<?php if (is_a({$expression}, 'WP_Post') || is_numeric({$expression})) : ?>" .
                   "<?php \$posts->put('p', is_a({$expression}, 'WP_Post') ? ({$expression})->ID : {$expression}); ?>" .
                   "<?php endif; ?>" .

                   "<?php if (is_array({$expression})) : ?>" .
                   "<?php \$posts
                       ->put('ignore_sticky_posts', true)
                       ->put('posts_per_page', -1)
                       ->put('post__in', collect({$expression})
                           ->map(function (\$post) {
                               return is_a(\$post, 'WP_Post') ? \$post->ID : \$post;
                           })->all())
                       ->put('orderby', 'post__in');
                   ?>" .
                   "<?php endif; ?>" .

                   "<?php \$query = \$posts->isNotEmpty() ? new WP_Query(\$posts->all()) : {$expression}; ?>" .
                   "<?php if (! \$query->have_posts()) :";
        }

        return "<?php if (empty(\$query)) : ?>" .
               "<?php global \$wp_query; ?>" .
               "<?php \$query = \$wp_query; ?>" .
               "<?php endif; ?>" .

               "<?php if (! \$query->have_posts()) : ?>";
    },

    'endnoposts' => function () {
        return "<?php wp_reset_postdata(); endif; ?>";
    },

    /*
    |---------------------------------------------------------------------
    | @title / @content / @excerpt / @permalink / @thumbnail
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
        return "<?= get_permalink({$expression}); ?>";
    },

    'thumbnail' => function ($expression) {
        if (! empty($expression)) {
            $expression = Util::parse($expression);

            if (! empty($expression->get(2))) {
                if ($expression->get(2) === 'false') {
                    return "<?= get_the_post_thumbnail_url({$expression->get(0)}, is_numeric({$expression->get(1)}) ? [{$expression->get(1)}, {$expression->get(1)}] : {$expression->get(1)}); ?>"; // phpcs:ignore
                }

                return "<?= get_the_post_thumbnail({$expression->get(0)}, is_numeric({$expression->get(1)}) ? [{$expression->get(1)}, {$expression->get(1)}] : {$expression->get(1)}); ?>"; // phpcs:ignore
            }

            if (! empty($expression->get(1))) {
                if ($expression->get(1) === 'false') {
                    return "<?= get_the_post_thumbnail_url(get_the_ID(), {$expression->get(0)}); ?>";
                }

                return "<?= get_the_post_thumbnail({$expression->get(0)}, is_numeric({$expression->get(1)}) ? [{$expression->get(1)}, {$expression->get(1)}] : {$expression->get(1)}); ?>"; // phpcs:ignore
            }

            if (! empty($expression->get(0))) {
                if ($expression->get(0) === 'false') {
                    return "<?= get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); ?>";
                }

                if (is_numeric($expression->get(0))) {
                    return "<?= get_the_post_thumbnail({$expression->get(0)}, 'thumbnail'); ?>";
                }

                return "<?= get_the_post_thumbnail(get_the_ID(), {$expression->get(0)}); ?>";
            }
        }

        return "<?= get_the_post_thumbnail(get_the_ID(), 'thumbnail'); ?>";
    },

    /*
    |---------------------------------------------------------------------
    | @author / @authorurl / @published / @modified
    |---------------------------------------------------------------------
    */

    'author' => function ($expression) {
        if (! empty($expression)) {
            return "<?= get_the_author_meta('display_name', {$expression}); ?>";
        }

        return "<?= get_the_author_meta('display_name'); ?>";
    },

    'authorurl' => function ($expression) {
        if (! empty($expression)) {
            return "<?= get_author_posts_url({$expression}, get_the_author_meta('user_nicename', {$expression})); ?>";
        }

        return "<?= get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename')); ?>";
    },

    'published' => function ($expression) {
        if (! empty($expression)) {
            return "<?php if (is_a({$expression}, 'WP_Post') || is_int({$expression})) : ?>" .
                   "<?= get_the_date('', {$expression}); ?>" .
                   "<?php else : ?>" .
                   "<?= get_the_date({$expression}); ?>" .
                   "<?php endif; ?>";
        }

        return "<?= get_the_date(); ?>";
    },

    'modified' => function ($expression) {
        if (! empty($expression)) {
            return "<?php if (is_a({$expression}, 'WP_Post') || is_numeric({$expression})) : ?>" .
                   "<?= get_the_modified_date('', {$expression}); ?>" .
                   "<?php else : ?>" .
                   "<?= get_the_modified_date({$expression}); ?>" .
                   "<?php endif; ?>";
        }

        return "<?= get_the_modified_date(); ?>";
    },

    /*
    |---------------------------------------------------------------------
    | @category / @categories / @term / @terms
    |---------------------------------------------------------------------
    */

    'category' => function ($expression) {
        $expression = Util::parse($expression);

        if ($expression->get(1) === 'true') {
            return "<?php if (collect(get_the_category({$expression->get(0)}))->isNotEmpty()) : ?>" .
                   "<a href=\"<?= get_category_link(collect(get_the_category({$expression->get(0)}))->shift()->cat_ID); ?>\">" . // phpcs:ignore
                   "<?= collect(get_the_category({$expression->get(0)}))->shift()->name; ?>" .
                   "</a>" .
                   "<?php endif; ?>";
        }

        if (! empty($expression->get(0))) {
            if ($expression->get(0) === 'true') {
                return "<?php if (collect(get_the_category())->isNotEmpty()) : ?>" .
                       "<a href=\"<?= get_category_link(collect(get_the_category())->shift()->cat_ID); ?>\">" .
                       "<?= collect(get_the_category())->shift()->name; ?>" .
                       "</a>" .
                       "<?php endif; ?>";
            }

            return "<?php if (collect(get_the_category({$expression->get(0)}))->isNotEmpty()) : ?>" .
                   "<?= collect(get_the_category({$expression->get(0)}))->shift()->name; ?>" .
                   "<?php endif; ?>";
        }

        return "<?php if (collect(get_the_category())->isNotEmpty()) : ?>" .
               "<?= collect(get_the_category())->shift()->name; ?>" .
               "<?php endif; ?>";
    },

    'categories' => function ($expression) {
        $expression = Util::parse($expression);

        if ($expression->get(1) === 'true') {
            return "<?= get_the_category_list(', ', '', {$expression->get(0)}); ?>";
        }

        if ($expression->get(0) === 'true') {
            return "<?= get_the_category_list(', ', '', get_the_ID()); ?>";
        }


        if (is_numeric($expression->get(0))) {
            return "<?= strip_tags(get_the_category_list(', ', '', {$expression->get(0)})); ?>";
        }

        return "<?= strip_tags(get_the_category_list(', ', '', get_the_ID())); ?>";
    },

    'term' => function ($expression) {
        $expression = Util::parse($expression);

        if (! empty($expression->get(2))) {
            return "<?php if (get_the_terms({$expression->get(1)}, {$expression->get(0)})) : ?>" . // phpcs:ignore
                   "<a href=\"<?= get_term_link(collect(get_the_terms({$expression->get(1)}, {$expression->get(0)}))->shift()->term_id); ?>\">" . // phpcs:ignore
                   "<?= collect(get_the_terms({$expression->get(1)}, {$expression->get(0)}))->shift()->name; ?>" .
                   "</a>" .
                   "<?php endif; ?>";
        }

        if (! empty($expression->get(1))) {
            if ($expression->get(1) === 'true') {
                return "<?php if (get_the_terms(get_the_ID(), {$expression->get(0)})) : ?>" .
                       "<a href=\"<?= get_term_link(collect(get_the_terms(get_the_ID(), {$expression->get(0)}))->shift()->term_id); ?>\">" . // phpcs:ignore
                       "<?= collect(get_the_terms(get_the_ID(), {$expression->get(0)}))->shift()->name; ?>" .
                       "</a>" .
                       "<?php endif; ?>";
            }

            return "<?php if (get_the_terms({$expression->get(1)}, {$expression->get(0)})) : ?>" . // phpcs:ignore
                   "<?= collect(get_the_terms({$expression->get(1)}, {$expression->get(0)}))->shift()->name; ?>" .
                   "<?php endif; ?>";
        }

        if (! empty($expression->get(0))) {
            return "<?php if (get_the_terms(get_the_ID(), {$expression->get(0)})) : ?>" .
                   "<?= collect(get_the_terms(get_the_ID(), {$expression->get(0)}))->shift()->name; ?>" .
                   "<?php endif; ?>";
        }
    },

    'terms' => function ($expression) {
        $expression = Util::parse($expression);

        if ($expression->get(2) === 'true') {
            return "<?= get_the_term_list({$expression->get(1)}, {$expression->get(0)}, '', ', '); ?>";
        }

        if (! empty($expression->get(1))) {
            if ($expression->get(1) === 'true') {
                return "<?= get_the_term_list(get_the_ID(), {$expression->get(0)}, '', ', '); ?>";
            }

            return "<?= strip_tags(get_the_term_list({$expression->get(1)}, {$expression->get(0)}, '', ', ')); ?>";
        }

        if (! empty($expression->get(0))) {
            return "<?= strip_tags(get_the_term_list(get_the_ID(), {$expression->get(0)}, '', ', ')); ?>";
        }
    },

    /*
    |---------------------------------------------------------------------
    | @image
    |---------------------------------------------------------------------
    */

    'image' => function ($expression) {
        $expression = Util::parse($expression);
        $image = Util::strip($expression->get(0));

        if (
            is_string($image) &&
            ! is_numeric($image) &&
            $image = Util::field($image)
        ) {
            $expression = $expression->put(0, is_array($image) && ! empty($image['id']) ? $image['id'] : $image);
        }

        if (Util::strip($expression->get(1)) == 'raw') {
            return "<?php echo wp_get_attachment_url({$expression->get(0)}); ?>";
        }

        if (! empty($expression->get(3))) {
            $expression = $expression->put(2, Util::clean($expression->slice(2)->all()));
        }

        if (! empty($expression->get(2)) && ! Util::isArray($expression->get(2))) {
            $expression = $expression->put(2, Util::toString(['alt' => $expression->get(2)]));
        }

        if ($expression->get(1)) {
            return "<?php echo wp_get_attachment_image(
                {$expression->get(0)},
                {$expression->get(1)},
                false,
                {$expression->get(2)}
            ); ?>";
        }

        return "<?php echo wp_get_attachment_image(
            {$expression->get(0)},
            'thumbnail',
            false,
            {$expression->get(2)}
        ); ?>";
    },

    /*
    |---------------------------------------------------------------------
    | @role / @endrole / @user / @enduser / @guest / @endguest
    |---------------------------------------------------------------------
    */

    'role' => function ($expression) {
        $expression = Util::parse($expression);

        return "<?php if (is_user_logged_in() && in_array(strtolower({$expression->get(0)}), (array) wp_get_current_user()->roles)) : ?>"; // phpcs:ignore
    },

    'endrole' => function () {
        return "<?php endif; ?>";
    },

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


    /*
    |---------------------------------------------------------------------
    | @action / @filter
    |---------------------------------------------------------------------
    */

    'action' => function ($expression) {
        return "<?php do_action({$expression}); ?>";
    },

    'filter' => function ($expression) {
        return "<?= apply_filters({$expression}); ?>";
    },

    /*
    |---------------------------------------------------------------------
    | @wphead / @wpfoot
    |---------------------------------------------------------------------
    */

    'wphead' => function () {
        return "<?php wp_head(); ?>";
    },

    'wpfoot' => function () {
        return "<?php wp_footer(); ?>";
    },

    /*
    |---------------------------------------------------------------------
    | @bodyclass / @wpbodyopen
    |---------------------------------------------------------------------
    */

    'bodyclass' => function ($expression) {
        return "<?php body_class({$expression}); ?>";
    },

    'wpbodyopen' => function () {
        return "<?php if (function_exists('wp_body_open')) { wp_body_open(); } else { do_action('wp_body_open'); } ?>";
    },

    /*
    |---------------------------------------------------------------------
    | @__
    |---------------------------------------------------------------------
    */

    '__' => function ($expression) {
        $expression = Util::parse($expression);

        return "<?php _e({$expression[0]}, {$expression[1]}); ?>";
    },
];
