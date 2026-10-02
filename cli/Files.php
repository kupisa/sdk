<?php

namespace kupisa\cli;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use yii\console\Exception;

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
     * The directory of this repository under `sites/` of the platform, read from the namespaces of its themes
     * and modules: `template` for `sites\template\themes\demo`, null for `sites\themes\demo`, which lives right
     * under `sites/`. The platform finds a class by its namespace, so that is where the files have to go,
     * whatever the directory of the repository is called on this computer.
     *
     * @throws Exception When a namespace is not one the platform would find, or two of them name another directory.
     */
    public static function client(): string|null
    {
        $clients = [];

        foreach (self::directories() as $directory) {
            [$kind, $name] = explode('/', $directory);
            $file          = $directory . ($kind === 'themes' ? '/Theme.php' : '/Module.php');

            if (!is_file($file)) {
                continue;
            }

            $pattern = '~^namespace\s+sites\\\\(?:(\w+)\\\\)?' . $kind . '\\\\' . $name . '\s*;~m';

            if (!preg_match($pattern, (string) file_get_contents($file), $matches)) {
                throw new Exception(
                    "The namespace of $file has to be sites\\$kind\\$name, or sites\\<client>\\$kind\\$name.",
                );
            }

            $clients[$matches[1] ?? ''][] = $file;
        }

        if (count($clients) > 1) {
            throw new Exception(
                'The themes and modules of a repository share one namespace, and these differ: '
                . implode(', ', array_merge(...array_values($clients))) . '.',
            );
        }

        return (string) array_key_first($clients) ?: null;
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
