<?php

describe('@istrue', function () {
    it('compiles correctly', function () {
        $directive = "@istrue(false)";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php if (isset(false) && (bool) false === true) : ?>");
    });

    it('compiles correctly with output', function () {
        $directive = "@istrue(false, 'Hello World')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php if (isset(false) && (bool) false === true) : ?><?php echo 'Hello World'; ?><?php endif; ?>");
    });
});

describe('@endistrue', function () {
    it('compiles correctly', function () {
        $directive = "@endistrue";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php endif; ?>");
    });
});

describe('@isfalse', function () {
    it('compiles correctly', function () {
        $directive = "@isfalse(true)";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php if (isset(true) && (bool) true === false) : ?>");
    });

    it('compiles correctly with output', function () {
        $directive = "@isfalse(true, 'Hello World')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php if (isset(true) && (bool) true === false) : ?><?php echo 'Hello World'; ?><?php endif; ?>");
    });
});

describe('@endisfalse', function () {
    it('compiles correctly', function () {
        $directive = "@endisfalse";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php endif; ?>");
    });
});

describe('@isnull', function () {
    it('compiles correctly', function () {
        $directive = "@isnull('Hello World')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php if (is_null('Hello World')) : ?>");
    });

    it('compiles correctly with output', function () {
        $directive = "@isnull('Hello World', 'Hello World')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php if (is_null('Hello World')) : ?><?php echo 'Hello World'; ?><?php endif; ?>");
    });
});

describe('@endisnull', function () {
    it('compiles correctly', function () {
        $directive = "@endisnull";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php endif; ?>");
    });
});

describe('@isnotnull', function () {
    it('compiles correctly', function () {
        $directive = "@isnotnull('Hello World')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php if (! is_null('Hello World')) : ?>");
    });

    it('compiles correctly with output', function () {
        $directive = "@isnotnull('Hello World', 'Hello World')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php if (! is_null('Hello World')) : ?><?php echo 'Hello World'; ?><?php endif; ?>");
    });
});

describe('@endisnotnull', function () {
    it('compiles correctly', function () {
        $directive = "@endisnotnull";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php endif; ?>");
    });
});

describe('@notempty', function () {
    it('compiles correctly', function () {
        $directive = "@notempty('Hello World')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php if (! empty('Hello World')) : ?>");
    });

    it('compiles correctly with output', function () {
        $directive = "@notempty('Hello World', 'Hello World')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php if (! empty('Hello World')) : ?><?php echo 'Hello World'; ?><?php endif; ?>");
    });
});

describe('@endnotempty', function () {
    it('compiles correctly', function () {
        $directive = "@endnotempty";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php endif; ?>");
    });
});

describe('@instanceof', function () {
    it('compiles correctly', function () {
        $directive = "@instanceof(\$post, 'WP_Post')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php if (is_a(\$post, 'WP_Post')) : ?>");
    });
});

describe('@endinstanceof', function () {
    it('compiles correctly', function () {
        $directive = "@endinstanceof";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php endif; ?>");
    });
});

describe('@typeof', function () {
    it('compiles correctly', function () {
        $directive = "@typeof(1, 'integer')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php if (gettype(1) === 'integer') : ?>");
    });
});

describe('@endtypeof', function () {
    it('compiles correctly', function () {
        $directive = "@endtypeof";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php endif; ?>");
    });
});

describe('@global', function () {
    it('compiles correctly', function () {
        $directive = "@global(\$post)";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php global \$post; ?>");
    });
});

describe('@set', function () {
    it('compiles correctly', function () {
        $directive = "@set(\$post, get_post())";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php \$post = get_post(); ?>");
    });
});

describe('@unset', function () {
    it('compiles correctly', function () {
        $directive = "@unset(\$post)";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php unset(\$post); ?>");
    });
});

describe('@extract', function () {
    it('compiles correctly', function () {
        $directive = "@extract(\$post)";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php extract(\$post); ?>");
    });
});

describe('@implode', function () {
    it('compiles correctly', function () {
        $directive = "@implode(', ', \$post)";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php echo implode(', ', \$post); ?>");
    });
});

describe('@repeat', function () {
    it('compiles correctly', function () {
        $directive = "@repeat(5)";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php for (\$iteration = 0 ; \$iteration < (int) 5; \$iteration++) : ?><?php \$loop = (object) [ 'index' => \$iteration, 'iteration' => \$iteration + 1, 'remaining' =>(int) 5 - \$iteration, 'count' => (int) 5, 'first' => \$iteration === 0, 'last' => \$iteration + 1 === (int) 5 ]; ?>");
    });
});

describe('@endrepeat', function () {
    it('compiles correctly', function () {
        $directive = "@endrepeat";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php endfor; ?>");
    });
});

describe('@style', function () {
    it('compiles correctly', function () {
        $directive = "@style";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<style>');
    });

    it('compiles correctly with stylesheet', function () {
        $directive = "@style('path/to/stylesheet.css')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<link rel="stylesheet" href="path/to/stylesheet.css">');
    });
});

describe('@endstyle', function () {
    it('compiles correctly', function () {
        $directive = "@endstyle";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('</style>');
    });
});

describe('@script', function () {
    it('compiles correctly', function () {
        $directive = "@script";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<script>');
    });

    it('compiles correctly with script', function () {
        $directive = "@script('path/to/script.js')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('<script src="path/to/script.js"></script>');
    });
});

describe('@endscript', function () {
    it('compiles correctly', function () {
        $directive = "@endscript";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe('</script>');
    });
});

describe('@js', function () {
    it('compiles correctly', function () {
        $directive = "@js('example', ['foo' => 'bar'])";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<script>window.example = <?php echo is_array(['foo' => 'bar']) ? json_encode(['foo' => 'bar']) : '\'' . ['foo' => 'bar'] . '\''; ?>;</script>");
    });
});

describe('@inline', function () {
    it('compiles correctly', function () {
        $directive = "@inline('path/to/inline.php')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<?php include get_theme_file_path('path/to/inline.php') ?>");
    });

    it('compiles correctly with style', function () {
        $directive = "@inline('path/to/inline.css')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<style><?php include get_theme_file_path('path/to/inline.css') ?></style>");
    });

    it('compiles correctly with script', function () {
        $directive = "@inline('path/to/inline.js')";

        $compiled = $this->compile($directive);

        expect($compiled)->toBe("<script><?php include get_theme_file_path('path/to/inline.js') ?></script>");
    });
});
