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
}
