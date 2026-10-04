# The content of a site

The pages, menus, terms, pictures and areas of a site can be written as files, next to the theme that shows
them: the `content/` directory of the repository is a **package**, which `vendor/bin/kupisa content/push` brings
the site to and `vendor/bin/kupisa content/pull` writes from the site. `examples/content/` is a small, complete
package; `reference/content.json` lists what a package is written with (the blocks and their values, the areas,
the kinds of pages, the taxonomies). Read both before you write content.

```
content/
  site.json                    the areas (header, footer, appearance) and the settings that name a page or a picture
  media.json                   the files of the media library, by uid
  media/hero.jpg               the files themselves
  pages/about-us.json          a page of any kind, one file each
  menus/main.json              a menu with the tree of its items, one file each
  terms/blog_category.json     the terms of a taxonomy as a tree, one file per taxonomy
```

## How a push works

- **A record is known by its `uid`**, never by an id and never by the name of its file. A record the site has
  with that uid is updated; one it does not have is created with it. So the same package is pushed as often as
  it changes, and nothing is made twice.
- **The uid of a new record is yours to choose**: a short name that says what it is (`page-about`,
  `menu-main`, `cat-news`), 64 characters at most, unique among the records of its kind. A record the site
  made has a UUID; keep it as it is. Never change the uid of a record that was pushed: the site would take it
  for a new one.
- **The package is checked whole before anything is written**, and a package with an error changes nothing.
  Every error names its file. Fix them all and push again.
- **What the package does not say stays as the site has it**: a page that has no file, a value of the meta that
  is not named, the terms of a taxonomy a page does not name. Nothing is deleted unless the push is told to
  (`--delete`: the pages, terms, menus and files of the site that the package does not hold are removed).
- **Start from the site.** Unless the site is new and empty, run `content/pull` first and change the files it
  wrote. Writing a page the site already has under a uid of your own makes a second page, or fails on its
  address, which is taken.
- **`--dry-run`** tells what a push would create, update and delete, and changes nothing. Use it first.

## References

A value that holds a record of the site is written as a reference to its uid, in place of an id:

| Reference | Names |
|---|---|
| `@media:<uid>` | a file of the media library |
| `@page:<uid>` | a page of any kind (a page, a post, a form, a product) |
| `@menu:<uid>` | a menu |
| `@term:<uid>` | a term of a taxonomy |
| `@user:<email address>` | a user of the site, who is named by the email address: users do not travel |

A reference has to name a record that is in the package or on the site already; anything else is an error. A
user the site does not have is the exception: the value is left empty and the push says so.

Which values take a reference is said in `reference/content.json`: the `references` of a block
(`{"image": "media"}`, or `{"items.image": "media"}` for a field of the items of a repeater), of a kind of page
and of a taxonomy. Any other value is written as it is. A link (`{"label": ..., "url": ..., "new_tab": ...}`)
holds an address, not a reference: write the address of the page (`/about-us`).

## Pictures: `media.json` and `media/`

```json
{
    "hero": {"file": "hero.jpg", "name": "Hills at sunrise", "alt": "Green hills under a rising sun", "description": null}
}
```

Every file by its uid: the `file` in `media/`, the `name` the media library shows, the `alt` text and a
`description`. Only `file` is required.

**A file is never replaced.** A record the site has keeps its file and takes the name, alternative text and
description of the package. To change a picture, add the new file under a new uid and name that uid where the
old one was.

## Pages: `pages/<name>.json`

```json
{
    "uid": "page-about",
    "type": "page",
    "title": "About us",
    "slug": "about-us",
    "status": "published",
    "meta": {},
    "blocks": [
        {"name": "heading", "values": {"text": "About us", "level": "h1"}, "settings": {}},
        {"name": "image", "values": {"image": "@media:hero"}, "settings": {"spacing": "lg"}}
    ]
}
```

- `type` is the kind of page (`pages` in the reference): `page`, or a kind a module brings (`post`, `form`,
  `product`), which the site has only while that module is enabled. `page` when left out.
- `title` is required. `slug` is the address of the page (`/about-us`): lowercase letters, digits and hyphens,
  unique among the pages of its kind; left out, a new page gets one from its title.
- `status` is `draft` or `published`; `draft` when left out.
- `blocks` is the whole content, in order. Each block has its `name`, its `values` and its `settings`. Use only
  the blocks of the reference whose `group` is one of the `blockGroups` of the kind of page, and the blocks of
  the theme of this repository; a block of a module only while the site has the module. Give a block only the
  values it has: an unknown block or value is an error. A value left out takes its default.
- The `settings` every block has are `title` (its name in the page editor), `spacing` (the space below it:
  `none`, `sm`, `lg`), `padding`, `anchor` (its id on the page, for a link like `/contact#form`) and `class`.
- `meta` holds the values of the kind of page (`meta` in the reference: the `excerpt` of a post) and what a
  module keeps about a page under its own prefix. The SEO module keeps `seo.title`, `seo.description`,
  `seo.image` (a `@media:` reference) and `seo.noindex` (true or false).
- `terms`, for a kind that is put in terms (`taxonomies` in the reference), names them by taxonomy:
  `{"blog_category": ["@term:cat-news"], "blog_tag": []}`.

A post of the blog:

```json
{
    "uid": "post-opening",
    "type": "post",
    "title": "We are open",
    "status": "published",
    "meta": {
        "published_at": "2026-10-01",
        "excerpt": "The first walk of the season.",
        "image": "@media:hero",
        "author_id": "@user:ana@example.com"
    },
    "terms": {"blog_category": ["@term:cat-news"]},
    "blocks": [{"name": "paragraph", "values": {"text": "See you on Saturday."}, "settings": {}}]
}
```

A product of the shop is a page of the `product` kind with what it sells, its variants, as a list in its file,
in their order:

```json
{
    "uid": "prod-cap",
    "type": "product",
    "title": "Cap",
    "status": "published",
    "meta": {"images": ["@media:cap"], "options": ["@term:attr-colour"], "related": []},
    "terms": {"shop_category": ["@term:cat-hats"]},
    "variants": [
        {"uid": "var-cap-red", "sku": "CAP-RED", "price": "12.50", "compare_price": null, "stock": 7,
         "availability": "in_stock", "image": "@media:cap", "values": ["@term:val-red"], "meta": {}}
    ],
    "blocks": []
}
```

- A new product needs one variant at least; a product without options (`"options": []`) has exactly one, with
  no `values`. A product with options has a variant per combination, each naming one value of every option.
- The variants are brought to the list as a whole: one the list does not name is deleted. A product whose file
  has no `variants` at all keeps the ones it has.
- `stock` counts for a new variant only: the shop counts the stock of a variant it has. `availability` is
  `in_stock`, `on_order` or `out_of_stock`.
- The rates of VAT, the ways of delivery and of payment, the orders and the customers are not content: they are
  set up in the administration of the shop.

## Terms: `terms/<taxonomy>.json`

```json
[
    {
        "uid": "cat-news",
        "name": "News",
        "slug": "news",
        "status": "visible",
        "description": null,
        "meta": {},
        "children": [
            {"uid": "cat-walks", "name": "Walks"}
        ]
    }
]
```

One file per taxonomy, named after it (`taxonomies` in the reference: `blog_category`, `blog_tag`,
`shop_category`, `shop_tag`, `shop_attribute`), holding its terms as a tree. Only `uid` and `name` are required.
The order of the list is the order of the terms; a file the site wrote names the `position` of every term too,
which then counts instead. `status` is `visible` or `hidden`. A taxonomy whose `hierarchy` is `none` (tags)
takes no `children`; one of `levels` (the attributes of the shop) has the attributes at the top and their
values right under them, never deeper.

## Menus: `menus/<name>.json`

```json
{
    "uid": "menu-main",
    "label": "Main menu",
    "items": [
        {"label": "Home", "type": "page", "target": "@page:page-home", "new_tab": false, "children": []},
        {"label": "News", "type": "blog_category", "target": "@term:cat-news"},
        {"label": "Our shop", "type": "url", "target": "https://shop.example.com", "new_tab": true}
    ]
}
```

The items are the whole menu, in order: the items it had are replaced. An item points to a page of any kind
(`"type": "page"`), to a term (the `type` is the taxonomy) or to an address (`"type": "url"`). Three levels at
most.

## Areas and settings: `site.json`

```json
{
    "areas": {
        "header": {"menu": "@menu:menu-main"},
        "footer": {"text": "Guided walks in the hills since 2019.", "menu": "@menu:menu-main"}
    },
    "settings": {"home_page": "@page:page-home", "contact_page": "@page:page-contact", "logo": "@media:logo"}
}
```

- `areas` gives an area its values, all of them: the values it had are replaced, so name every value the area
  is to keep. The areas of the platform and their values are in the reference (`areas`); a theme that replaces
  the header or the footer with a block of its own (`Theme::areas()`) gives that area the values of its block.
  `appearance` holds the colours, fonts and sizes the site set over its theme.
- `settings` names the pages the site uses for a purpose (`home_page`, `privacy_page`, `terms_page`,
  `contact_page`), its `logo` and its `favicon`, each a reference or `null` for none. The name of the site, its
  language, its email address and the settings of the modules are not in a package: they are set in the
  administration.
- An area or a setting the file does not name stays as the site has it.

## What a pull writes

`content/pull` writes every record of the site, so its files are fuller than the ones written by hand: every
value of every block, UUIDs as uids, files named after the addresses of the pages. It replaces `site.json`,
`media.json`, `media/`, `pages/`, `menus/` and `terms/` of `content/` and leaves anything else there alone. It
leaves out what the site no longer has (a block of a theme or a module that is gone, a file missing from the
storage) and says so.
