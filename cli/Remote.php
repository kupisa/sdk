<?php

namespace kupisa\cli;

/**
 * The server of a site, reached with rsync over SSH. Every site is reached as the same user, and the server
 * ties the key to the directory of the repository, so no path on the server is ever named here.
 */
class Remote
{
    public const USER = 'kupisa';

    /**
     * @param string $host The host name of the site, which is the address of its server too.
     * @param string $key  The path of the private SSH key, as it was typed (`~/.ssh/id_ed25519`).
     */
    public function __construct(
        public readonly string $host,
        public readonly string $key,
    ) {
    }

    /**
     * The path of a key with the `~` it starts with replaced by the home directory.
     */
    public static function expand(string $key): string
    {
        $home = getenv('HOME') ?: getenv('USERPROFILE');

        return str_starts_with($key, '~') && $home ? $home . substr($key, 1) : $key;
    }

    /**
     * Tries the connection by uploading an empty directory as a dry run, which writes nothing on the server.
     * (Listing the directory would be simpler, but the rsync of macOS asks for a listing with `--dirs`, which
     * the server refuses.)
     *
     * @return string|null What rsync answered when it failed, null when the server let the key in.
     */
    public function check(): string|null
    {
        $empty = sys_get_temp_dir() . '/kupisa-' . uniqid();

        mkdir($empty);

        [$exitCode, $output] = $this->rsync(['--dry-run', "$empty/", $this->target()]);

        rmdir($empty);

        return $exitCode === 0 ? null : trim($output);
    }

    /**
     * The directory of the repository on the server, as rsync names it.
     */
    private function target(): string
    {
        return self::USER . '@' . $this->host . ':.';
    }

    /**
     * The SSH command rsync connects with: this key only, never asking anything, a new server trusted the
     * first time it is seen.
     */
    private function ssh(): string
    {
        $key = self::expand($this->key);

        if (str_contains($key, ' ')) {
            $key = escapeshellarg($key);
        }

        return "ssh -i $key -o IdentitiesOnly=yes -o BatchMode=yes -o ConnectTimeout=10"
            . ' -o StrictHostKeyChecking=accept-new';
    }

    /**
     * Runs rsync with the arguments given, going through directories with `--recursive` and never with
     * `--dirs`, which the server refuses from the rsync of macOS.
     *
     * @param string[] $arguments
     * @return array{int, string} The exit code and everything rsync wrote.
     */
    private function rsync(array $arguments): array
    {
        $pipes   = [];
        $command = ['rsync', '-e', $this->ssh(), '--recursive', '--no-dirs', ...$arguments];
        $process = proc_open($command, [1 => ['pipe', 'w'], 2 => ['redirect', 1]], $pipes);

        if ($process === false) {
            return [1, 'rsync could not be started. Is it installed?'];
        }

        $output = (string) stream_get_contents($pipes[1]);

        fclose($pipes[1]);

        return [proc_close($process), $output];
    }
}
