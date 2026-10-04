<?php

namespace kupisa\cli;

/**
 * Runs a program of the computer (rsync, php) and gives what it wrote.
 */
class Process
{
    /**
     * Runs a command, its arguments given one by one, so none of them needs quoting.
     *
     * @param string[] $command
     * @return array{int, string} The exit code and everything the program wrote, its errors among the rest.
     */
    public static function run(array $command): array
    {
        $pipes   = [];
        // @phpstan-ignore argument.type (PHPStan does not know the `redirect` descriptor)
        $process = proc_open($command, [1 => ['pipe', 'w'], 2 => ['redirect', 1]], $pipes);

        if ($process === false) {
            return [1, "$command[0] could not be started. Is it installed?"];
        }

        $output = (string) stream_get_contents($pipes[1]);

        fclose($pipes[1]);

        return [proc_close($process), $output];
    }

    /**
     * Runs a command that writes for the person at the terminal: what it writes is shown as it comes. With a
     * file, what it writes to its output goes there in place of the terminal, and only its errors are shown.
     *
     * @param string[] $command
     * @return int The exit code.
     */
    public static function show(array $command, string|null $file = null): int
    {
        $pipes   = [];
        $output  = $file === null ? STDOUT : ['file', $file, 'w'];
        $process = proc_open($command, [1 => $output, 2 => STDERR], $pipes);

        if ($process === false) {
            fwrite(STDERR, "$command[0] could not be started. Is it installed?\n");

            return 1;
        }

        return proc_close($process);
    }
}
