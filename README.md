# Kupiša SDK

Everything a theme or a module of the Kupiša platform is built with, for a developer who does not see the code of
the platform and for the AI assistant they work with.

| Path | What it holds |
|---|---|
| `AGENTS.md` | The rules an AI assistant follows when it writes a theme or a module. Start here. |
| `docs/` | The guides: how a theme is built, how a module is built, how a block is built, how a module or a theme builds on the shop, how the content of a site is written as files. |
| `examples/themes/starter/` | A small, complete theme to start from. |
| `examples/content/` | A small, complete package of content: pages, a menu, a picture and the areas of a site. |
| `reference/content.json` | What content is written with: every block of the platform and of its modules with its values, the areas, the kinds of pages and the taxonomies. |
| `stubs/` | The public classes of the platform: their comments and signatures, without the code. |
| `frontend/` | The views of the site and of its blocks, which a theme replaces file by file, and its design tokens. |
| `modules/shop/` | The views of the shop on the site and its stylesheet, which a theme replaces and styles. |
| `bin/kupisa`, `cli/` | The command line tool that uploads the themes and modules of a repository to a site, and carries the content of the site. |

`stubs/`, `examples/`, `reference/`, `frontend/` and `modules/` are written from the platform at every release, so they always match what
runs on the servers. Do not edit them by hand.

## Using it

The SDK is a development dependency of a repository of themes and modules, made from the
[template](https://github.com/kupisa/template):

```
composer install
```

puts it into `vendor/kupisa/sdk/`. Nothing of it runs on a server: the stubs are read by the editor, by PHPStan
and by the AI assistant, never loaded.

## The command line tool

`vendor/bin/kupisa`, run in the root of the repository, uploads its themes and modules to a site over SSH, and
carries the content of the site between the site and the repository.

| Command | What it does |
|---|---|
| `kupisa connect` | Asks for the host name of a site and for your SSH key, tries the connection and remembers the site with what it is: its uid, name, region, language, theme and enabled modules. The first site connected is the selected one. |
| `kupisa info` | Asks the selected site what it is now, remembers it and writes it out. `push` and `content/push` ask it too, so it stays as the site is. |
| `kupisa switch [<host>]` | Selects another connected site: the one named, or one of the list it shows. |
| `kupisa disconnect <host>` | Forgets a connected site. |
| `kupisa push` | Makes the themes and modules on the selected site the same as in the repository, once: uploads what is new or changed, deletes what is gone. |
| `kupisa dev` | Does the same, then watches the files and uploads every change as soon as it is saved, until Ctrl+C. Open `https://<site>/dev/on` in your browser, logged in, and the page reloads on every change (`/dev/off` stops that). |
| `kupisa content/pull` | Writes the content of the selected site (its pages, menus, terms, pictures and areas) into `content/` of the repository, in place of what it holds: a package of files, a file per record, each known by its `uid`. |
| `kupisa content/push` | Brings the selected site to `content/`: a record the site has by its `uid` is updated, one it does not have is created, and the site tells what it did. A package with an error changes nothing. `--dry-run` only tells what would change; `--delete` also removes the pages, terms, menus and files of the site that `content/` does not hold. |

- `push`, `dev`, `info` and the `content` commands take `--site=<host>` to work on another connected site without selecting it.
- `push` and `dev` never touch `content/`, and the `content` commands never touch `themes/` and `modules/`. What
  else is kept in `content/` (notes of your own) stays in the repository and is uploaded with the package, but the
  site reads `site.json`, `media.json`, `media/`, `pages/`, `menus/` and `terms/` only.
- `push` and `dev` warn about a theme or a module whose code uses the shop (`modules\shop\…`) but whose
  `requires()` does not name `shop`: a site without the shop would break on it. It is uploaded all the same.
- A PHP file with a syntax error is never uploaded: `push` uploads nothing until it is fixed, `dev` leaves the
  file as the site has it and says why.
- `themes/` and `modules/` of the repository are mirrored on the server, under the directory of the repository
  in the `sites/` directory of the platform: the repository in `.../sites/template` on your computer has its
  `themes/` in `sites/template/themes/` on the server, and the same for `modules/`. The name of the directory
  of the repository is what counts, so it has to be the one its namespace names (`sites\template\themes\demo`
  for `themes/demo` of `template`); a theme or a module with another namespace stops the upload, since the site
  would not find it. A repository whose directory is `sites` itself lands right in `sites/`.
- A mirror means the repository is the truth: what is deleted here is deleted there, a whole theme or module
  too. Nothing else in the directory of the repository on the server is touched, and nothing outside it is
  reached.
- The connected sites are kept in `.kupisa.json`, which belongs to your computer and stays out of git: each with
  its key and what the site is, which your AI assistant reads to know what it builds for.
- A server lets you in once we have added your public SSH key to it; `connect` shows the key to send us.
- It needs PHP and `rsync` on your computer. To type `kupisa` alone, add
  `alias kupisa='vendor/bin/kupisa'` to the profile of your shell.
