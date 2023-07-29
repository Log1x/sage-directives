<?php

describe('@fields', function () {
    it('compiles correctly', function () {
        $directive = "@fields('item')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (have_rows('item')) : ?><?php while (have_rows('item')) : the_row(); ?>");
    });

    it('compiles correctly with post ID', function () {
        $directive = "@fields('item', 1)";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (have_rows('item', 1)) : ?><?php while (have_rows('item', 1)) : the_row(); ?>");
    });

    it('compiles correctly with post object', function () {
        $directive = "@fields('item', \$post->ID)";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (have_rows('item', \$post->ID)) : ?><?php while (have_rows('item', \$post->ID)) : the_row(); ?>");
    });
});

describe('@endfields', function () {
    it('compiles correctly', function () {
        $directive = '@endfields';

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual('<?php endwhile; endif; ?>');
    });
});

describe('@hasfields', function () {
    it('compiles correctly', function () {
        $directive = "@hasfields('item')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (have_rows('item')) : ?>");
    });

    it('compiles correctly with post ID', function () {
        $directive = "@hasfields('item', 1)";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (have_rows('item', 1)) : ?>");
    });

    it('compiles correctly with post object', function () {
        $directive = "@hasfields('item', \$post->ID)";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (have_rows('item', \$post->ID)) : ?>");
    });
});

describe('@endhasfields', function () {
    it('compiles correctly', function () {
        $directive = '@endhasfields';

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual('<?php endif; ?>');
    });
});

describe('@field', function () {
    it('compiles correctly', function () {
        $directive = "@field('item')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php echo get_field('item'); ?>");
    });

    it('compiles correctly with key', function () {
        $directive = "@field('item', 'key')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php echo get_field('item', null, true)['key']; ?>");
    });

    it('compiles correctly with post ID', function () {
        $directive = "@field('item', 1)";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php echo get_field('item', 1, true); ?>");
    });

    it('compiles correctly with key and post ID', function () {
        $directive = "@field('item', 'key', 1)";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php echo get_field('item', 1, true)['key']; ?>");
    });

    it('compiles correctly with post ID and formatting', function () {
        $directive = "@field('item', 1, false)";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php echo get_field('item', 1, false); ?>");
    });

    it('compiles correctly with key, post ID and formatting', function () {
        $directive = "@field('item', 'key', 1, false)";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php echo get_field('item', 1, false)['key']; ?>");
    });

    it('compiles correctly with post object', function () {
        $directive = "@field('item', \$post->ID)";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php echo get_field('item', \$post->ID, true); ?>");
    });

    it('compiles correctly with key and post object', function () {
        $directive = "@field('item', 'key', \$post->ID)";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php echo get_field('item', \$post->ID, true)['key']; ?>");
    });

    it('compiles correctly with post object and formatting', function () {
        $directive = "@field('item', \$post->ID, false)";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php echo get_field('item', \$post->ID, false); ?>");
    });

    it('compiles correctly with key, post object and formatting', function () {
        $directive = "@field('item', 'key', \$post->ID, false)";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php echo get_field('item', \$post->ID, false)['key']; ?>");
    });
});

describe('@hasfield', function () {
    it('compiles correctly', function () {
        $directive = "@hasfield('item')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (get_field('item')) : ?>");
    });

    it('compiles correctly with key', function () {
        $directive = "@hasfield('item', 'key')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (get_field('item')['key']) : ?>");
    });

    it('compiles correctly with post ID', function () {
        $directive = "@hasfield('item', 1)";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (get_field('item', 1)) : ?>");
    });
});

describe('@isfield', function () {
    it('compiles correctly', function () {
        $directive = "@isfield('cta_type', 'phone')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (get_field('cta_type') === 'phone') : ?>");
    });

    it('compiles correctly with post ID', function () {
        $directive = "@isfield('cta_type', 'phone', 1)";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (get_field('cta_type', 1) === 'phone') : ?>");
    });

    it('compiles correctly with post object', function () {
        $directive = "@isfield('cta_type', 'phone', \$post->ID)";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (get_field('cta_type', \$post->ID) === 'phone') : ?>");
    });

    it('compiles correctly with array key', function () {
        $directive = "@isfield('cta', 'type', 'phone')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (get_field('cta')['type'] === 'phone') : ?>");
    });

    it('compiles correctly with array key and post ID', function () {
        $directive = "@isfield('cta', 'type', 'phone', 1)";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (get_field('cta', 1)['type'] === 'phone') : ?>");
    });

    it('compiles correctly with array key and post object', function () {
        $directive = "@isfield('cta', 'type', 'phone', \$post->ID)";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (get_field('cta', \$post->ID)['type'] === 'phone') : ?>");
    });
});

describe('@endfield', function () {
    it('compiles correctly', function () {
        $directive = '@endfield';

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual('<?php endif; ?>');
    });
});

describe('@sub', function () {
    it('compiles correctly', function () {
        $directive = "@sub('item')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php echo get_sub_field('item'); ?>");
    });

    it('compiles correctly with key', function () {
        $directive = "@sub('item', 'key')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php echo get_sub_field('item')['key']; ?>");
    });

    it('compiles correctly with array key', function () {
        $directive = "@sub('item', 'key', 'value')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php echo get_sub_field('item')['key']['value']; ?>");
    });
});

describe('@hassub', function () {
    it('compiles correctly', function () {
        $directive = "@hassub('item')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (get_sub_field('item')) : ?>");
    });

    it('compiles correctly with key', function () {
        $directive = "@hassub('item', 'key')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (get_sub_field('item')['key']) : ?>");
    });

    it('compiles correctly with array key', function () {
        $directive = "@hassub('item', 'key', 'value')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (get_sub_field('item')['key']['value']) : ?>");
    });
});

describe('@issub', function () {
    it('compiles correctly', function () {
        $directive = "@issub('cta_type', 'phone')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (get_sub_field('cta_type') === 'phone') : ?>");
    });

    it('compiles correctly with array key', function () {
        $directive = "@issub('cta', 'type', 'phone')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (get_sub_field('cta')['type'] === 'phone') : ?>");
    });
});

describe('@layouts', function () {
    it('compiles correctly', function () {
        $directive = "@layouts('item')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (have_rows('item')) : ?><?php while (have_rows('item')) : the_row(); ?>");
    });

    it('compiles correctly with post ID', function () {
        $directive = "@layouts('item', 1)";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (have_rows('item', 1)) : ?><?php while (have_rows('item', 1)) : the_row(); ?>");
    });

    it('compiles correctly with post object', function () {
        $directive = "@layouts('item', \$post->ID)";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (have_rows('item', \$post->ID)) : ?><?php while (have_rows('item', \$post->ID)) : the_row(); ?>");
    });
});

describe('@endlayouts', function () {
    it('compiles correctly', function () {
        $directive = '@endlayouts';

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual('<?php endwhile; endif; ?>');
    });
});

describe('@layout', function () {
    it('compiles correctly', function () {
        $directive = "@layout('item')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (get_row_layout() === 'item') : ?>");
    });
});

describe('@endlayout', function () {
    it('compiles correctly', function () {
        $directive = '@endlayout';

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual('<?php endif; ?>');
    });
});

describe('@group', function () {
    it('compiles correctly', function () {
        $directive = "@group('item')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (have_rows('item')) : ?><?php while (have_rows('item')) : the_row(); ?>");
    });

    it('compiles correctly with post ID', function () {
        $directive = "@group('item', 1)";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (have_rows('item', 1)) : ?><?php while (have_rows('item', 1)) : the_row(); ?>");
    });

    it('compiles correctly with post object', function () {
        $directive = "@group('item', \$post->ID)";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (have_rows('item', \$post->ID)) : ?><?php while (have_rows('item', \$post->ID)) : the_row(); ?>");
    });
});

describe('@endgroup', function () {
    it('compiles correctly', function () {
        $directive = '@endgroup';

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual('<?php endwhile; endif; ?>');
    });
});

describe('@options', function () {
    it('compiles correctly', function () {
        $directive = "@options('item')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (have_rows('item', 'option')) : ?><?php while (have_rows('item', 'option')) : the_row(); ?>");
    });
});

describe('@endoptions', function () {
    it('compiles correctly', function () {
        $directive = '@endoptions';

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual('<?php endwhile; endif; ?>');
    });
});

describe('@hasoptions', function () {
    it('compiles correctly', function () {
        $directive = "@hasoptions('item')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (have_rows('item', 'option')) : ?>");
    });
});

describe('@endhasoptions', function () {
    it('compiles correctly', function () {
        $directive = '@endhasoptions';

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual('<?php endif; ?>');
    });
});

describe('@option', function () {
    it('compiles correctly', function () {
        $directive = "@option('item')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php echo get_field('item', 'option'); ?>");
    });

    it('compiles correctly with key', function () {
        $directive = "@option('item', 'key')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php echo get_field('item', 'option')['key']; ?>");
    });
});

describe('@hasoption', function () {
    it('compiles correctly', function () {
        $directive = "@hasoption('item')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (get_field('item', 'option')) : ?>");
    });

    it('compiles correctly with key', function () {
        $directive = "@hasoption('item', 'key')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (get_field('item', 'option')['key']) : ?>");
    });
});

describe('@isoption', function () {
    it('compiles correctly', function () {
        $directive = "@isoption('item', 'value')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (get_field('item', 'option') === 'value') : ?>");
    });

    it('compiles correctly with key', function () {
        $directive = "@isoption('item', 'key', 'value')";

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual("<?php if (get_field('item', 'option')['key'] === 'value') : ?>");
    });
});

describe('@endoption', function () {
    it('compiles correctly', function () {
        $directive = '@endoption';

        $compiled = $this->compile($directive);

        expect($compiled)->toEqual('<?php endif; ?>');
    });
});
