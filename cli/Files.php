<?php

namespace kupisa\cli;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * The themes and modules of the current directory, the root of a repository, and their files.
 */
class Files
{
    /**
     * The directories of the themes and modules, as `themes/<name>` and `modules/<name>`.
     *
     * @return string[]
     */
    public static function directories(): array
    {
        return [
            ...(glob('themes/*', GLOB_ONLYDIR) ?: []),
            ...(glob('modules/*', GLOB_ONLYDIR) ?: []),
        ];
    }

    /**
     * Every file of the themes and modules with what tells a change of it: the time it was written and its size.
     *
     * @return array<string, string> The time and size by the path of the file from the current directory.
     */
    public static function all(): array
    {
        clearstatcache();

        $files = [];

        foreach (self::directories() as $directory) {
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

        foreach (self::directories() as $directory) {
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
