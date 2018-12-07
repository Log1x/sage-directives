<?php

namespace App\Directives;

return [

    /*
    |--------------------------------------------------------------------------
    | WordPress Directives
    |--------------------------------------------------------------------------
    |
    | Directives for various WordPress use-cases.
    |
    */

    /** Create @query() Blade directive */
    'query' => function ($expression) {
        return "<?php global \$query; ?>".
               "<?php \$query = new WP_Query({$expression}); ?>";
    },

    /** Create @posts Blade directive */
    'posts' => function ($expression) {
        if (!empty($expression)) {
            return "<?php if ({$expression}->have_posts()) : ?>".
                   "<?php while ({$expression}->have_posts()) : {$expression}->the_post(); ?>";
        }

        return "<?php if (empty(\$query)) : ?>".
               "<?php global \$wp_query; ?>".
               "<?php \$query = \$wp_query; ?>".
               "<?php endif; ?>".
               "<?php if (\$query->have_posts()) : ?>".
               "<?php while (\$query->have_posts()) : \$query->the_post(); ?>";
    },

    /** Create @endposts Blade directive */
    'endposts' => function () {
        return "<?php endwhile; wp_reset_postdata(); endif; ?>";
    },

    /** Create @title Blade directive */
    'title' => function ($expression) {
        if (!empty($expression)) {
            return "<?= get_the_title({$expression}); ?>";
        }

        return "<?= get_the_title(); ?>";
    },

    /** Create @content Blade directive */
    'content' => function () {
        return "<?php the_content(); ?>";
    },

    /** Create @excerpt Blade directive */
    'excerpt' => function () {
        return "<?php the_excerpt(); ?>";
    },

    /** Create @shortcode() Blade directive */
    'shortcode' => function ($expression) {
        return "<?= do_shortcode({$expression}); ?>";
    },

    /** Create @user Blade directive */
    'user' => function () {
        return "<?php if (is_user_logged_in()) : ?>";
    },

    /** Create @enduser Blade directive */
    'enduser' => function () {
        return "<?php endif; ?>";
    },

    /** Create @guest Blade directive */
    'guest' => function () {
        return "<?php if (!is_user_logged_in()) : ?>";
    },

    /** Create @endguest Blade directive */
    'endguest' => function () {
        return "<?php endif; ?>";
    },
];
