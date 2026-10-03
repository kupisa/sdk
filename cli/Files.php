<?php

namespace kupisa\cli;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * The `themes/` and `modules/` of the current directory, the root of a repository, and their files.
 */
class Files
{
    /** The directories a repository keeps its themes and modules in, mirrored on the server. */
    public const KINDS = ['themes', 'modules'];

    /**
     * The directory of the repository under `sites/` of the platform: the name of its directory on this
     * computer (`template` for `.../sites/template`), so `themes/` lands in `sites/template/themes/`; null
     * for a repository whose directory is `sites` itself, which lands right in `sites/`.
     */
    public static function client(): string|null
    {
        $name = basename((string) getcwd());

        return $name === 'sites' ? null : $name;
    }

    /**
     * The theme or module whose namespace does not match where the upload puts it, or null when they all do.
     * The platform finds a class by its namespace, so a theme in `themes/demo` of the repository `template`
     * has to be `sites\template\themes\demo\Theme`; with any other namespace it would be left out on the site.
     *
     * @return string|null The file and the namespace it has to declare.
     */
    public static function misplaced(): string|null
    {
        $client = self::client();

        foreach (self::KINDS as $kind) {
            foreach (glob("$kind/*/", GLOB_ONLYDIR) ?: [] as $directory) {
                $name      = basename($directory);
                $file      = $directory . ($kind === 'themes' ? 'Theme.php' : 'Module.php');
                $namespace = 'sites\\' . ($client === null ? '' : "$client\\") . "$kind\\$name";

                if (!is_file($file)) {
                    continue;
                }

                $pattern = '~^namespace\s+' . preg_quote($namespace, '~') . '\s*;~m';

                if (!preg_match($pattern, (string) file_get_contents($file))) {
                    return "$file has to declare the namespace $namespace.";
                }
            }
        }

        return null;
    }

    /**
     * Every file of `themes/` and `modules/` with what tells a change of it: the time it was written and its size.
     *
     * @return array<string, string> The time and size by the path of the file from the current directory.
     */
    public static function all(): array
    {
        clearstatcache();

        $files = [];

        foreach (self::kinds() as $directory) {
            foreach (self::iterate($directory) as $path => $file) {
                if ($file->isFile() && $file->getFilename() !== '.DS_Store') {
                    $files[str_replace('\\', '/', $path)] = $file->getMTime() . ':' . $file->getSize();
                }
            }
        }

        return $files;
    }

    /**
     * Gives every directory the time of the newest file below it, which the upload carries to the server. The
     * site publishes the public files of a theme or a module again only when the time of their directory
     * changes, and a file saved in place, or in a directory further down, would not change it.
     */
    public static function touchDirectories(): void
    {
        clearstatcache();

        foreach (self::kinds() as $directory) {
            // The directories come after what they hold, so each one already knows the times below it.
            $times = [];

            foreach (self::iterate($directory, childFirst: true) as $path => $file) {
                $time   = max($file->getMTime(), $times[$path] ?? 0);
                $parent = dirname($path);

                if ($file->isDir() && $time > $file->getMTime()) {
                    touch($path, $time);
                }

                $times[$parent] = max($times[$parent] ?? 0, $time);
            }
        }
    }

    /**
     * The directories of `KINDS` the repository has.
     *
     * @return string[]
     */
    public static function kinds(): array
    {
        return array_filter(self::KINDS, 'is_dir');
    }

    /**
     * Everything in a directory, at any depth; a directory before what it holds, or after it.
     *
     * @return RecursiveIteratorIterator<RecursiveDirectoryIterator>
     */
    private static function iterate(string $directory, bool $childFirst = false): RecursiveIteratorIterator
    {
        return new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
            $childFirst ? RecursiveIteratorIterator::CHILD_FIRST : RecursiveIteratorIterator::SELF_FIRST,
        );
    }
}
