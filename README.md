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
