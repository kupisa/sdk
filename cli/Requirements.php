<?php

namespace kupisa\cli;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Whether the themes and modules of the repository that build on a module of the platform (the shop) name it in
 * `requires()`. The platform offers such a theme or module only to a site that has the module, so one that
 * forgets it may be given to a site without it, and break there.
 */
class Requirements
{
    /** The modules of the platform a theme or a module may build on, by the namespace of their classes. */
    public const MODULES = ['shop' => 'modules\\shop\\'];

    /**
     * What builds on a module of the platform without naming it, a line for each.
     *
     * @return list<string>
     */
    public static function missing(): array
    {
        $missing = [];

        foreach (Files::kinds() as $kind) {
            foreach (glob("$kind/*/", GLOB_ONLYDIR) ?: [] as $directory) {
                $directory = rtrim($directory, '/');
                $file      = $directory . ($kind === 'themes' ? '/Theme.php' : '/Module.php');
                $required  = is_file($file) ? self::required((string) file_get_contents($file)) : [];

                foreach (self::MODULES as $module => $namespace) {
                    $class = in_array($module, $required, true) ? null : self::use($directory, $kind, $namespace);

                    if ($class !== null) {
                        $missing[] = "$directory uses $class, but its requires() does not name '$module': "
                            . "a site without the $module would break on it.";
                    }
                }
            }
        }

        return $missing;
    }

    /**
     * The modules the `requires()` of a class names, read from its code.
     *
     * @return list<string>
     */
    private static function required(string $code): array
    {
        if (!preg_match('~function\s+requires\s*\(\s*\)[^{]*\{(.*?)\}~s', $code, $match)) {
            return [];
        }

        preg_match_all('~[\'"]([a-z0-9]+)[\'"]~', $match[1], $names);

        return $names[1];
    }

    /**
     * The first class of a namespace a theme or a module uses, or null when it uses none. The views of a module
     * a theme replaces (under its `modules/<name>/`) do not count: they are used only while the module is there.
     */
    private static function use(string $directory, string $kind, string $namespace): string|null
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
        );

        $pattern = '~(?<![\w\\\\])\\\\?(' . preg_quote($namespace, '~') . '[\w\\\\]*\w)~';

        foreach ($files as $path => $file) {
            $path     = str_replace('\\', '/', $path);
            $replaced = $kind === 'themes' && str_starts_with($path, "$directory/modules/");

            if ($file->getExtension() !== 'php' || $replaced) {
                continue;
            }

            if (preg_match($pattern, (string) file_get_contents($path), $match)) {
                return $match[1];
            }
        }

        return null;
    }
}
