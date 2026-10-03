# Kupiša SDK

Everything a theme of the Kupiša platform is built with, for a developer who does not see the code of the platform
and for the AI assistant they work with.

| Path | What it holds |
|---|---|
| `AGENTS.md` | The rules an AI assistant follows when it writes a theme. Start here. |
| `docs/` | The guides: how a theme is built, how a block is built. |
| `examples/themes/starter/` | A small, complete theme to start from. |
| `stubs/` | The public classes of the platform: their comments and signatures, without the code. |
| `frontend/` | The views of the site and of its blocks, which a theme replaces file by file, and its design tokens. |
| `bin/kupisa`, `cli/` | The command line tool that uploads the themes and modules of a repository to a site. |

`stubs/`, `examples/` and `frontend/` are written from the platform at every release, so they always match what
runs on the servers. Do not edit them by hand.

## Using it

The SDK is a development dependency of a repository of themes, made from the
[template](https://github.com/kupisa/template):

```
composer install
```

puts it into `vendor/kupisa/sdk/`. Nothing of it runs on a server: the stubs are read by the editor, by PHPStan
and by the AI assistant, never loaded.

## The command line tool

`vendor/bin/kupisa`, run in the root of the repository, uploads its themes and modules to a site over SSH.

| Command | What it does |
|---|---|
| `kupisa connect` | Asks for the host name of a site and for your SSH key, tries the connection and remembers the site. The first site connected is the selected one. |
| `kupisa switch [<host>]` | Selects another connected site: the one named, or one of the list it shows. |
| `kupisa disconnect <host>` | Forgets a connected site. |
| `kupisa push` | Makes the themes and modules on the selected site the same as in the repository, once: uploads what is new or changed, deletes what is gone. |
| `kupisa dev` | Does the same, then watches the files and uploads every change as soon as it is saved, until Ctrl+C. Open `https://<site>/dev/on` in your browser, logged in, and the page reloads on every change (`/dev/off` stops that). |

- `push` and `dev` take `--site=<host>` to work on another connected site without selecting it.
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
- The connected sites are kept in `.kupisa.json`, which belongs to your computer and stays out of git.
- A server lets you in once we have added your public SSH key to it; `connect` shows the key to send us.
- It needs PHP and `rsync` on your computer. To type `kupisa` alone, add
  `alias kupisa='vendor/bin/kupisa'` to the profile of your shell.
