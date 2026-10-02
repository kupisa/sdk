# Building for the Kupiša platform

Kupiša is a multi-site platform built on PHP 8.4 and Yii2: one server serves many sites, and every site picks a
theme. This repository holds themes made for particular sites. You do not see the code of the platform; what you
may build on is in the SDK, in the directory of this file:

- `docs/theme.md`: how a theme is built. Read it before you write or change a theme.
- `docs/blocks.md`: how a block is built. Read it before you write or change a block.
- `examples/themes/starter/`: a small, complete theme. A new theme starts as a copy of it.
- `stubs/`: the public classes of the platform, by namespace (`stubs/frontend/blocks/Block.php` is
  `frontend\blocks\Block`). The comments and the signatures are real; the bodies of the methods are left out,
  so an empty method does not mean it does nothing.
- `frontend/`: the views of the site and of its blocks as the platform draws them, and its design tokens
  (`frontend/public/css/src/_tokens.scss`).

`stubs/`, `examples/` and `frontend/` come from the platform. Read them, never edit them.

## What you may use

- Only the classes in `stubs/` and Yii2 itself. A class of the platform that has no stub is not public: do not
  call it, even when a view in `frontend/` or a comment names it. When a task needs something the stubs do not
  offer, say so instead of guessing.
- A theme never touches the database schema, the configuration of the platform or the files of another theme.

## Where code goes

- A theme lives in `themes/<name>/` of this repository, with a `Theme` class in it. Modules (`modules/<name>/`)
  are not covered by the SDK yet.
- The namespace of a class is its path from the `sites` directory of the platform, which this repository is
  cloned into. The `AGENTS.md` of this repository names the prefix: with the prefix `sites\acme`, the theme
  `paris` is `sites\acme\themes\paris\Theme` in `themes/paris/Theme.php`.
- The name of a theme is the name of its directory: lowercase letters and digits only, and unique on the whole
  server. It may not be the name of a theme or a module of the platform (`default`, `starter`, `dental`,
  `construction`, `craftsman`, `realestate`, `realify`, `shop`, `blog`, `seo`, `form`, `cookie`, ...): pick
  a name of the client or the project.

## Rules of the code

- **English only.** Code, comments, the names of files and the source of every text are in English.
- **Translate every text.** A text shown to a person goes through `Yii::t('<theme name>', '...')`, with the
  name of the theme as the category, and gets its translation in `messages/<language>/<theme name>.php` in the
  same change, for every language the theme has (see `docs/theme.md`).
- **Readable first.** Prefer the plain solution Yii2 already has. Every class and every method has a comment
  that says what it is for, as the example theme does.
- **Views carry no logic.** A view only echoes, with `if` and `foreach`, what its block or controller passes.
  It declares every variable it gets in a `@var` comment at its top, and encodes every value it prints
  (`Html::encode()`).
- **Styles build on the tokens.** `public/theme.css` gives the design tokens other values and styles what the
  theme adds, with the tokens (`var(--color-primary)`, `var(--space-3)`), never a colour or a size of its own
  outside `:root`. Sizes are in `rem`. One class per selector where it can be; the class of a block is
  `block-<name>`. The site loads no CSS framework and no JavaScript library, and a theme adds none.
- **Layout:** arrays over several lines have one element per line, a trailing comma and their `=>` in one
  column; assignments that follow one another align their `=`; a block (`if`, `foreach`) has a blank line
  before and after it; a line stays under 120 characters.

## Checking your work

- `vendor/bin/phpstan` checks the code against the stubs: a call to something the platform does not offer
  fails. Run it after every change and leave it without errors.
- The theme itself is seen on a site of the platform once it is deployed; you cannot run it here. Say what you
  could not check.
