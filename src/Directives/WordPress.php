<?php

namespace Log1x\SageDirectives\Directives;

use Illuminate\Support\Str;

class WordPress extends Directives
{
    /**
     * The WordPress directives.
     */
    public function directives(): array
    {
        return [
            /*
            |---------------------------------------------------------------------
            | @query / @posts / @endposts
            |---------------------------------------------------------------------
            */

            'query' => function ($expression) {
                return '<?php global $query; ?>'.
                    "<?php \$query = new WP_Query({$expression}); ?>";
            },

            'posts' => function ($expression) {
                $iterateLoop = '$__env->incrementLoopIndices(); $loop = $__env->getLastLoop();';

                if (! empty($expression)) {
                    return '<?php $posts = collect(); ?>'.

                        "<?php if (is_a({$expression}, 'WP_Post') || is_numeric({$expression})) : ?>".
                        "<?php \$posts->put('p', is_a({$expression}, 'WP_Post') ? ({$expression})->ID : {$expression}); ?>".
                        '<?php endif; ?>'.

                        "<?php if (is_array({$expression})) : ?>".
                        "<?php \$posts
                            ->put('ignore_sticky_posts', true)
                            ->put('posts_per_page', -1)
                            ->put('post__in', collect({$expression})
                                ->map(fn (\$post) => is_a(\$post, 'WP_Post') ? \$post->ID : \$post)->all()
                            )
                            ->put('orderby', 'post__in');
                        ?>".
                        '<?php endif; ?>'.

                        "<?php \$query = \$posts->isNotEmpty() ? new WP_Query(\$posts->all()) : {$expression}; ?>".
                        "<?php if (\$query->have_posts()) : \$__currentLoopData = range(1, \$query->post_count); \$__env->addLoop(\$__currentLoopData); while (\$query->have_posts()) : {$iterateLoop} \$query->the_post(); ?>";
                }

                $handleQuery = '<?php if (empty($query)) : ?>'.
                    '<?php global $wp_query; ?>'.
                    '<?php $query = $wp_query; ?>'.
                    '<?php endif; ?>';

                return "{$handleQuery} <?php if (\$query->have_posts()) : ?>".
                    "<?php \$__currentLoopData = range(1, \$query->post_count); \$__env->addLoop(\$__currentLoopData); while (\$query->have_posts()) : {$iterateLoop} \$query->the_post(); ?>";
            },

            'endposts' => function () {
                return '<?php endwhile; wp_reset_postdata(); $__env->popLoop(); $loop = $__env->getLastLoop(); endif; ?>';
            },

            /*
            |---------------------------------------------------------------------
            | @hasposts / @endhasposts / @noposts / @endnoposts
            |---------------------------------------------------------------------
            */

            'hasposts' => function ($expression) {
                if (! empty($expression)) {
                    return '<?php $posts = collect(); ?>'.

                        "<?php if (is_a({$expression}, 'WP_Post') || is_numeric({$expression})) : ?>".
                        "<?php \$posts->put('p', is_a({$expression}, 'WP_Post') ? ({$expression})->ID : {$expression}); ?>".
                        '<?php endif; ?>'.

                        "<?php if (is_array({$expression})) : ?>".
                        "<?php \$posts
                            ->put('ignore_sticky_posts', true)
                            ->put('posts_per_page', -1)
                            ->put('post__in', collect({$expression})
                                ->map(fn (\$post) => is_a(\$post, 'WP_Post') ? \$post->ID : \$post)->all()
                            )
                            ->put('orderby', 'post__in');
                        ?>".
                        '<?php endif; ?>'.

                        "<?php \$query = \$posts->isNotEmpty() ? new WP_Query(\$posts->all()) : {$expression}; ?>".
                        '<?php if ($query->have_posts()) : ?>';
                }

                return '<?php if (empty($query)) : ?>'.
                    '<?php global $wp_query; ?>'.
                    '<?php $query = $wp_query; ?>'.
                    '<?php endif; ?>'.

                    '<?php if ($query->have_posts()) : ?>';
            },

            'endhasposts' => function () {
                return '<?php wp_reset_postdata(); endif; ?>';
            },

            'noposts' => function ($expression) {
                if (! empty($expression)) {
                    return '<?php $posts = collect(); ?>'.

                        "<?php if (is_a({$expression}, 'WP_Post') || is_numeric({$expression})) : ?>".
                        "<?php \$posts->put('p', is_a({$expression}, 'WP_Post') ? ({$expression})->ID : {$expression}); ?>".
                        '<?php endif; ?>'.

                        "<?php if (is_array({$expression})) : ?>".
                        "<?php \$posts
                            ->put('ignore_sticky_posts', true)
                            ->put('posts_per_page', -1)
                            ->put('post__in', collect({$expression})
                                ->map(fn (\$post) => is_a(\$post, 'WP_Post') ? \$post->ID : \$post)->all()
                            )
                            ->put('orderby', 'post__in');
                        ?>".
                        '<?php endif; ?>'.

                        "<?php \$query = \$posts->isNotEmpty() ? new WP_Query(\$posts->all()) : {$expression}; ?>".
                        '<?php if (! $query->have_posts()) : ?>';
                }

                return '<?php if (empty($query)) : ?>'.
                    '<?php global $wp_query; ?>'.
                    '<?php $query = $wp_query; ?>'.
                    '<?php endif; ?>'.

                    '<?php if (! $query->have_posts()) : ?>';
            },

            'endnoposts' => function () {
                return '<?php wp_reset_postdata(); endif; ?>';
            },

            /*
            |---------------------------------------------------------------------
            | @postmeta
            |---------------------------------------------------------------------
            */

            'postmeta' => function ($expression) {
                if (! empty($expression)) {
                    $expression = $this->parse($expression);

                    if ($this->isToken($expression->get(0))) {
                        if (empty($expression->get(1))) {
                            return "<?php echo get_post_meta({$expression->get(0)}); ?>";
                        }

                        if (empty($expression->get(2))) {
                            $expression->put(2, 'false');
                        }

                        return "<?php echo get_post_meta({$expression->get(0)}, {$expression->get(1)}, {$expression->get(2)}); ?>";
                    }

                    if ($this->isToken($expression->get(1))) {
                        if (empty($expression->get(2))) {
                            $expression->put(2, 'false');
                        }

                        return "<?php echo get_post_meta({$expression->get(1)}, {$expression->get(0)}, {$expression->get(2)}); ?>";
                    }

                    if (empty($expression->get(1))) {
                        $expression->put(1, 'false');
                    }

                    if (! $this->isToken($expression->get(0))) {
                        return "<?php echo get_post_meta(get_the_ID(), {$expression->get(0)}, {$expression->get(1)}); ?>";
                    }

                    return "<?php echo get_post_meta(get_the_ID(), {$expression->get(0)}, {$expression->get(1)}); ?>";
                }

                return '<?php echo get_post_meta(get_the_ID()); ?>';
            },

            /*
            |---------------------------------------------------------------------
            | @title / @content / @excerpt / @permalink / @thumbnail
            |---------------------------------------------------------------------
            */

            'title' => function ($expression) {
                if (! empty($expression)) {
                    return "<?php echo get_the_title({$expression}); ?>";
                }

                return '<?php echo get_the_title(); ?>';
            },

            'content' => function () {
                return '<?php the_content(); ?>';
            },

            'excerpt' => function () {
                return '<?php the_excerpt(); ?>';
            },

            'permalink' => function ($expression) {
                return "<?php echo get_permalink({$expression}); ?>";
            },

            'thumbnail' => function ($expression) {
                if (! empty($expression)) {
                    $expression = $this->parse($expression);

                    if (! empty($expression->get(2))) {
                        if ($expression->get(2) === 'false') {
                            return "<?php echo get_the_post_thumbnail_url({$expression->get(0)}, is_numeric({$expression->get(1)}) ? [{$expression->get(1)}, {$expression->get(1)}] : {$expression->get(1)}); ?>"; // phpcs:ignore
                        }

                        return "<?php echo get_the_post_thumbnail({$expression->get(0)}, is_numeric({$expression->get(1)}) ? [{$expression->get(1)}, {$expression->get(1)}] : {$expression->get(1)}); ?>"; // phpcs:ignore
                    }

                    if (! empty($expression->get(1))) {
                        if ($expression->get(1) === 'false') {
                            if ($this->isToken($expression->get(0))) {
                                return "<?php echo get_the_post_thumbnail_url({$expression->get(0)}, 'thumbnail'); ?>";
                            }

                            return "<?php echo get_the_post_thumbnail_url(get_the_ID(), {$expression->get(0)}); ?>";
                        }

                        return "<?php echo get_the_post_thumbnail({$expression->get(0)}, is_numeric({$expression->get(1)}) ? [{$expression->get(1)}, {$expression->get(1)}] : {$expression->get(1)}); ?>"; // phpcs:ignore
                    }

                    if (! empty($expression->get(0))) {
                        if ($expression->get(0) === 'false') {
                            return "<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); ?>";
                        }

                        if ($this->isToken($expression->get(0))) {
                            return "<?php echo get_the_post_thumbnail({$expression->get(0)}, 'thumbnail'); ?>";
                        }

                        return "<?php echo get_the_post_thumbnail(get_the_ID(), {$expression->get(0)}); ?>";
                    }
                }

                return "<?php echo get_the_post_thumbnail(get_the_ID(), 'thumbnail'); ?>";
            },

            /*
            |---------------------------------------------------------------------
            | @author / @authorurl / @published / @modified
            |---------------------------------------------------------------------
            */

            'author' => function ($expression) {
                if (! empty($expression)) {
                    return "<?php echo get_the_author_meta('display_name', {$expression}); ?>";
                }

                return "<?php echo get_the_author_meta('display_name'); ?>";
            },

            'authorurl' => function ($expression) {
                if (! empty($expression)) {
                    return "<?php echo get_author_posts_url({$expression}, get_the_author_meta('user_nicename', {$expression})); ?>";
                }

                return "<?php echo get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename')); ?>";
            },

            'published' => function ($expression) {
                if (! empty($expression)) {
                    $expression = $this->parse($expression);

                    if ($this->isToken($expression->get(0))) {
                        return "<?php echo get_the_date('', {$expression->get(0)}); ?>";
                    }

                    if (! $this->isToken($expression->get(0)) && empty($expression->get(1))) {
                        return "<?php echo get_the_date({$expression->get(0)}); ?>";
                    }

                    return "<?php echo get_the_date({$expression->get(0)}, {$expression->get(1)}); ?>";
                }

                return '<?php echo get_the_date(); ?>';
            },

            'modified' => function ($expression) {
                if (! empty($expression)) {
                    $expression = $this->parse($expression);

                    if ($this->isToken($expression->get(0))) {
                        return "<?php echo get_the_modified_date('', {$expression->get(0)}); ?>";
                    }

                    if ($this->isToken($expression->get(1))) {
                        return "<?php echo get_the_modified_date({$expression->get(0)}, {$expression->get(1)}); ?>";
                    }

                    return "<?php echo get_the_modified_date({$expression->get(0)}); ?>";
                }

                return '<?php echo get_the_modified_date(); ?>';
            },

            /*
            |---------------------------------------------------------------------
            | @category / @categories / @term / @terms
            |---------------------------------------------------------------------
            */

            'category' => function ($expression) {
                $expression = $this->parse($expression);

                if ($expression->get(1) === 'true') {
                    return "<?php if (collect(get_the_category({$expression->get(0)}))->isNotEmpty()) : ?>".
                        "<a href=\"<?php echo get_category_link(collect(get_the_category({$expression->get(0)}))->shift()->cat_ID); ?>\">". // phpcs:ignore
                        "<?php echo collect(get_the_category({$expression->get(0)}))->shift()->name; ?>".
                        '</a>'.
                        '<?php endif; ?>';
                }

                if (! empty($expression->get(0))) {
                    if ($expression->get(0) === 'true') {
                        return '<?php if (collect(get_the_category())->isNotEmpty()) : ?>'.
                            '<a href="<?php echo get_category_link(collect(get_the_category())->shift()->cat_ID); ?>">'.
                            '<?php echo collect(get_the_category())->shift()->name; ?>'.
                            '</a>'.
                            '<?php endif; ?>';
                    }

                    return "<?php if (collect(get_the_category({$expression->get(0)}))->isNotEmpty()) : ?>".
                        "<?php echo collect(get_the_category({$expression->get(0)}))->shift()->name; ?>".
                        '<?php endif; ?>';
                }

                return '<?php if (collect(get_the_category())->isNotEmpty()) : ?>'.
                    '<?php echo collect(get_the_category())->shift()->name; ?>'.
                    '<?php endif; ?>';
            },

            'categories' => function ($expression) {
                $expression = $this->parse($expression);

                if ($expression->get(1) === 'true') {
                    return "<?php echo get_the_category_list(', ', '', {$expression->get(0)}); ?>";
                }

                if ($expression->get(0) === 'true') {
                    return "<?php echo get_the_category_list(', ', '', get_the_ID()); ?>";
                }

                if (is_numeric($expression->get(0))) {
                    return "<?php echo strip_tags(get_the_category_list(', ', '', {$expression->get(0)})); ?>";
                }

                return "<?php echo strip_tags(get_the_category_list(', ', '', get_the_ID())); ?>";
            },

            'term' => function ($expression) {
                $expression = $this->parse($expression);

                if (! empty($expression->get(2)) && $expression->get(2) === 'true') {
                    return "<?php if (get_the_terms({$expression->get(1)}, {$expression->get(0)})) : ?>". // phpcs:ignore
                        "<a href=\"<?php echo get_term_link(collect(get_the_terms({$expression->get(1)}, {$expression->get(0)}))->shift()->term_id); ?>\">". // phpcs:ignore
                        "<?php echo collect(get_the_terms({$expression->get(1)}, {$expression->get(0)}))->shift()->name; ?>".
                        '</a>'.
                        '<?php endif; ?>';
                }

                if (! empty($expression->get(1))) {
                    if ($expression->get(1) === 'true') {
                        return "<?php if (get_the_terms(get_the_ID(), {$expression->get(0)})) : ?>".
                            "<a href=\"<?php echo get_term_link(collect(get_the_terms(get_the_ID(), {$expression->get(0)}))->shift()->term_id); ?>\">". // phpcs:ignore
                            "<?php echo collect(get_the_terms(get_the_ID(), {$expression->get(0)}))->shift()->name; ?>".
                            '</a>'.
                            '<?php endif; ?>';
                    }

                    return "<?php if (get_the_terms({$expression->get(1)}, {$expression->get(0)})) : ?>". // phpcs:ignore
                        "<?php echo collect(get_the_terms({$expression->get(1)}, {$expression->get(0)}))->shift()->name; ?>".
                        '<?php endif; ?>';
                }

                if (! empty($expression->get(0))) {
                    return "<?php if (get_the_terms(get_the_ID(), {$expression->get(0)})) : ?>".
                        "<?php echo collect(get_the_terms(get_the_ID(), {$expression->get(0)}))->shift()->name; ?>".
                        '<?php endif; ?>';
                }
            },

            'terms' => function ($expression) {
                $expression = $this->parse($expression);

                if ($expression->get(2) === 'true') {
                    return "<?php echo get_the_term_list({$expression->get(1)}, {$expression->get(0)}, '', ', '); ?>";
                }

                if (! empty($expression->get(1))) {
                    if ($expression->get(1) === 'true') {
                        return "<?php echo get_the_term_list(get_the_ID(), {$expression->get(0)}, '', ', '); ?>";
                    }

                    return "<?php echo strip_tags(get_the_term_list({$expression->get(1)}, {$expression->get(0)}, '', ', ')); ?>";
                }

                if (! empty($expression->get(0))) {
                    return "<?php echo strip_tags(get_the_term_list(get_the_ID(), {$expression->get(0)}, '', ', ')); ?>";
                }
            },

            /*
            |---------------------------------------------------------------------
            | @image
            |---------------------------------------------------------------------
            */

            'image' => function ($expression) {
                $expression = $this->parse($expression);
                $output = "<?php \$__imageDirective = {$expression->get(0)}; ?>";

                if (! $this->isToken($expression->get(0))) {
                    $output .= "<?php \$__imageDirective = function_exists('acf') \n
                        ? (get_field(\$__imageDirective) ?? get_sub_field(\$__imageDirective) ?? get_field(\$__imageDirective, 'option') ?? \$__imageDirective)
                        : \$__imageDirective; ?>";

                    $output .= "<?php \$__imageDirective = is_array(\$__imageDirective) && ! empty(\$__imageDirective['id']) ? \$__imageDirective['id'] : \$__imageDirective; ?>";
                }

                if ($this->strip($expression->get(1)) == 'raw') {
                    return $output.'<?php echo wp_get_attachment_url($__imageDirective); ?>';
                }

                if (! empty($expression->get(3))) {
                    $expression = $expression->put(2, $this->unwrap($this->toString($expression->slice(2)->all(), true)));
                }

                if (! empty($expression->get(2)) && ! $this->isArray($expression->get(2))) {
                    $expression = $expression->put(2, $this->toString(['alt' => $this->strip($expression->get(2))]));
                }

                if ($expression->get(1)) {
                    if ($expression->get(2)) {
                        return $output."<?php echo wp_get_attachment_image(
                            \$__imageDirective,
                            {$expression->get(1)},
                            false,
                            {$expression->get(2)}
                        ); ?>";
                    }

                    return $output."<?php echo wp_get_attachment_image(\$__imageDirective, {$expression->get(1)}, false); ?>";
                }

                if ($expression->get(2)) {
                    return $output."<?php echo wp_get_attachment_image(
                        \$__imageDirective,
                        'full',
                        false,
                        {$expression->get(2)}
                    ); ?>";
                }

                return $output."<?php echo wp_get_attachment_image(\$__imageDirective, 'thumbnail', false); ?>";
            },

            /*
            |---------------------------------------------------------------------
            | @role / @endrole / @user / @enduser / @guest / @endguest
            |---------------------------------------------------------------------
            */

            'role' => function ($expression) {
                $expression = $this->parse($expression);
                $condition = [];

                foreach ($expression as $value) {
                    $condition[] = "in_array(strtolower({$value}), (array) wp_get_current_user()->roles) ||";
                }

                $conditions = implode(' ', $condition);

                $conditions = Str::beforeLast($conditions, ' ||');

                return "<?php if (is_user_logged_in() && ({$conditions})) : ?>";
            },

            'endrole' => function () {
                return '<?php endif; ?>';
            },

            'user' => function () {
                return '<?php if (is_user_logged_in()) : ?>';
            },

            'enduser' => function () {
                return '<?php endif; ?>';
            },

            'guest' => function () {
                return '<?php if (! is_user_logged_in()) : ?>';
            },

            'endguest' => function () {
                return '<?php endif; ?>';
            },

            /*
            |---------------------------------------------------------------------
            | @shortcode
            |---------------------------------------------------------------------
            */

            'shortcode' => function ($expression) {
                return "<?php echo do_shortcode({$expression}); ?>";
            },

            /*
            |---------------------------------------------------------------------
            | @wpautop / @wpautokp
            |---------------------------------------------------------------------
            */

            'wpautop' => function ($expression) {
                return "<?php echo wpautop({$expression}); ?>";
            },

            'wpautokp' => function ($expression) {
                return "<?php echo wpautop(wp_kses_post({$expression})); ?>";
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
                return "<?php echo apply_filters({$expression}); ?>";
            },

            /*
            |---------------------------------------------------------------------
            | @wphead / @wpfooter
            |---------------------------------------------------------------------
            */

            'wphead' => function () {
                return '<?php wp_head(); ?>';
            },

            'wpfooter' => function () {
                return '<?php wp_footer(); ?>';
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
            | @postclass
            |---------------------------------------------------------------------
            */

            'postclass' => function ($expression) {
                return "<?php post_class({$expression}); ?>";
            },

            /*
            |---------------------------------------------------------------------
            | @sidebar / @hassidebar / @endhassidebar
            |---------------------------------------------------------------------
            */

            'sidebar' => function ($expression) {
                return "<?php dynamic_sidebar($expression); ?>";
            },

            'hassidebar' => function ($expression) {
                return "<?php if (is_active_sidebar($expression)) : ?>";
            },

            'endhassidebar' => function () {
                return '<?php endif; ?>';
            },

            /*
            |---------------------------------------------------------------------
            | @__
            |---------------------------------------------------------------------
            */

            '__' => function ($expression) {
                $expression = $this->parse($expression);

                return "<?php _e({$expression[0]}, {$expression[1]}); ?>";
            },

            /*
            |---------------------------------------------------------------------
            | @thememod
            |---------------------------------------------------------------------
            */

            'thememod' => function ($expression) {
                $expression = $this->parse($expression);

                $mod = $expression->get(0);
                $default = $expression->get(1);

                if (! empty($default)) {
                    return "<?php echo get_theme_mod({$mod}, {$default}); ?>";
                }

                return "<?php echo get_theme_mod({$mod}); ?>";
            },

            /*
            |---------------------------------------------------------------------
            | @menu / @hasmenu / @endhasmenu
            |---------------------------------------------------------------------
            */

            'menu' => function ($expression) {
                $expression = $this->parse($expression);
                return "<?php wp_nav_menu({$expression->get(0)}); ?>";
            },

            'hasmenu' => function ($expression) {
                return "<?php if (has_nav_menu($expression)) : ?>";
            },

            'endhasmenu' => function () {
                return '<?php endif; ?>';
            },
        ];
    }
}
