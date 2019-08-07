## v1.1.0 (08-07-2019)

### Enhancements
- Added `@image` directive which echos attachment images as well as ACF field images responsively using `wp_get_attachment_image()`
- Added `Util::field()` which if ACF is present, attempts to get and return a field value from `get_field()`, `get_sub_field()`, or `get_field($field, 'option')` (in that order).
- Added `Util::toString()` which attempts to convert expressions such as arrays back to strings before passing them to Blade to avoid array to string exceptions after modifying arrays passed as a Blade expression.
- Added `Util::wrap()` which simply wraps the passed value in single quotes.
- Added `Util::isArray()` in a simple but effective attempt to detect when an expression is an array in a conditional outside of the compiled Blade scope.
- Bump dependencies.
- Clean up `README.md`.
- Update `docs/installation.md` to match recent changes to the `README`.
- Change `Discourse` to `Support` in the documentation header navigation.
- Other small clean up.

### Bug fixes
- Fix documentation styles with the new VuePress.
- Only pull the Google font weights we're actually using on the documentation.

## v1.0.9 (05-12-2019)

### Enhancements
- Move docs from DocPress to VuePress (woo-hoo search!)
- Add CHANGELOG.md
- Clean up `package.json`
- Clean up `.gitignore` and `.gitattributes`

### Bug Fixes
- Fix various typo's within the documentation.

## v1.0.8 (05-03-2019)

### Enhancements
- Use a better method of getting Sage 10's Blade compiler.

### Bug Fixes
- Remove an unused `@asset` implementation that clashes with Sage 10.

## v1.0.7 (04-30-2019)

### Enhancements
- Add Sage 10 support 🎈 
- `@sub` and `@hassub` can now accept a third parameter for deeper nested arrays (e.g. `@sub('images', 'sizes', 'thumbnail')`). (Fixes #12)
- New `@permalink`, `@categories`, `@category`, `@term`, `@role`, and `@endrole` directives.

### Bug Fixes
- Change `get()` to a protected function.
- Fix a few typos in the docs. (Fixes #13)

## v1.0.6 (03-02-2019)

### Enhancements
- Change namespace to `Log1x\SageDirectives`
- The project README was getting a little insane with the amount of Directives currently in the project. They now have a new home: https://log1x.github.io/sage-directives-docs/

### Bug Fixes
- Set `ignore_sticky_posts` to `true` when passing an array of post IDs/objects to `@posts`
- Properly return the collection array when passing post IDs/objects to `@posts`

## v1.0.5 (02-13-2019)

### Bug Fixes
- Change `is_number()` to `is_numeric()` (oops)
- Fix `@published` typo on README

## v1.0.4 (02-11-2019)

### Features
- Added 12+ new helper directives: `@istrue`, `@isfalse`, `@isnull`, `@isnotnull`, `@instanceof`, `@typeof`, `@repeat`, `@style`, `@script`, `@js`, `@inline`, `@fa`
- Added 6 new WordPress directives: `@author`, `@authorurl`, `@published`, `@modified`, `@wpautop`, `@wpautokp`
- Refactored `@posts` allowing it to accept post ID's, `WP_Post` instances, or an array with a combination of the two (#8)
- Refactored source code DocBlocks and formatting of directives
- Improved formatting and examples for documentation

### Bug Fixes
- Fix missing closing parenthesis in fields directive (#7)
- Added missing parameters to allow passing an array key to `@isfield`, `@issub`, and `@isoption`

### Breaking Change
- `@condition` has been replaced with `@istrue` / `@isfalse`

## v1.0.3 (01-30-2019)

### Bug Fixes
- Add a `function_exists()` check for `add_action()` to prevent issues in scenarios where WordPress core is not loaded (e.g. Composer).

## v1.0.2 (12-07-2018)

### Features
- Add usage documentation with examples.
- Allow `@posts` to accept a custom WP_Query instance as a variable as well as return the main loop if `$query` is undefined and no variable is passed. (Thanks to @mmirus on #1)
- Add `@title`, `@content`, and `@excerpt` WordPress directives.
- Allow `@option` to accept a second parameter to return a value in an array.

### Enhancements
- Clean up `@sub`, `@hassub`, and `@issub` (were mistakenly accepting unusable param's originally from `@field`).
- Clean up `@condition` syntax to be more uniform with the other directives.

### Bug Fixes
- Add missing `@isoption` directive.
- Fix `@options` param typo.

## v1.0.1 (10-29-2018)

### Enhancements
- Rewrite utility methods making use of `collect()` and allowing easier argument splitting.
- Refactor directives using new utility methods as well as allowing additional arguments to be passed for ACF directives such as post ID.
- Make directive source code more readable.
- Remove `@getsub`, `@getfield`, `@dump`, and `@console`.
- Various miscellaneous clean up.

## v1.0.0 (10-17-2018)

Initial release
