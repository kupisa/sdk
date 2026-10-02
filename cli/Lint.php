<?php

namespace kupisa\cli;

/**
 * Checks the syntax of PHP files with `php -l`, so a file PHP cannot read never reaches a site.
 */
class Lint
{
    /**
     * The syntax errors of the PHP files among the files given.
     *
     * @param string[] $files
     * @return array<string, string> The error by the path of the file; empty when every file is fine.
     */
    public static function errors(array $files): array
    {
        $errors = [];

        foreach ($files as $file) {
            $error = str_ends_with($file, '.php') ? self::error($file) : null;

            if ($error !== null) {
                $errors[$file] = $error;
            }
        }

        return $errors;
    }

    /**
     * The syntax error of a PHP file, null when it has none.
     */
    public static function error(string $file): string|null
    {
        $command             = [PHP_BINARY, '-d', 'display_errors=1', '-d', 'log_errors=0', '-l', $file];
        [$exitCode, $output] = Process::run($command);

        return $exitCode === 0 ? null : (strtok(trim($output), "\n") ?: 'Syntax error');
    }
}
