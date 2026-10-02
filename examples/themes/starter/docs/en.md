## What it looks like

A warm, paper-like page: a cream background, dark brown text, an amber accent for links and buttons, and serif
headings. The corners of buttons and pictures are tighter than in the default theme.

## What it adds

- A banner block for the page editor: one line of text on the accent colour across the width of the page, with a
  link after it.
- A line above the title of the hero that names the site.

## For developers

The theme lives in `themes/starter`. Its `Theme` class describes it; `public/theme.css` gives the design tokens of
the site other values and styles the banner; `blocks/hero/views/hero.php` replaces the view of the hero of the site,
at the same path as in `apps/frontend/blocks`; `blocks/banner/` is the block of its own;
`public/screenshots/` holds the pictures shown here, `messages/` its translations and `docs/` this text. Copy the
directory to start a theme of your own.
