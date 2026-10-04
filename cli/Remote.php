<?php

namespace kupisa\cli;

/**
 * The server of a site, reached with rsync over SSH. Every site is reached as the same user, and the server
 * ties the key to the `sites/` directory of the platform, so no path outside it can be reached, and to the two
 * commands that import and export the content of a site.
 */
class Remote
{
    public const USER = 'kupisa';

    /** How SSH connects: this key only, never asking anything, a new server trusted the first time it is seen. */
    private const SSH_OPTIONS = [
        '-o', 'IdentitiesOnly=yes',
        '-o', 'BatchMode=yes',
        '-o', 'ConnectTimeout=10',
        '-o', 'StrictHostKeyChecking=accept-new',
    ];

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
     * Makes `themes/` and `modules/` on the server the same as those of the current directory: what is new or
     * changed is uploaded, what is no longer here is deleted there, a whole theme or module too. They are the
     * only thing touched in the directory of the repository on the server, and nothing outside it is reached.
     *
     * @param string|null $client  The directory under `sites/` the repository is, null for `sites/` itself.
     * @param string[]    $skip    The files left as the server has them, by their path from the current directory.
     * @param bool        $verbose Whether rsync lists what it uploads and deletes.
     * @return array{int, string} The exit code and everything rsync wrote.
     */
    public function upload(string|null $client, array $skip = [], bool $verbose = false): array
    {
        $filters = ['--exclude=.DS_Store'];

        foreach ($skip as $path) {
            $filters[] = "--exclude=/$path";
        }

        foreach (Files::KINDS as $kind) {
            $filters[] = "--include=/$kind/";
            $filters[] = "--include=/$kind/**";
        }

        $filters[] = '--exclude=*';

        return $this->rsync([
            ...($verbose ? ['--verbose'] : []),
            '--times',
            '--delete',
            ...$filters,
            './',
            $this->target($client),
        ]);
    }

    /**
     * Makes `content/` on the server the same as the one of the current directory, like {@see upload()} does
     * with the themes and modules, which it leaves alone.
     *
     * @param string|null $client The directory under `sites/` the repository is, null for `sites/` itself.
     * @return array{int, string} The exit code and everything rsync wrote.
     */
    public function uploadContent(string|null $client): array
    {
        return $this->rsync([
            '--times',
            '--delete',
            '--exclude=.DS_Store',
            '--include=/' . Files::CONTENT . '/',
            '--include=/' . Files::CONTENT . '/**',
            '--exclude=*',
            './',
            $this->target($client),
        ]);
    }

    /**
     * Imports the content uploaded with {@see uploadContent()} into the site, and shows what the site answers.
     *
     * @param string[] $options `--delete`, `--dry-run`.
     * @return int The exit code of the import.
     */
    public function importContent(string|null $client, array $options): int
    {
        return Process::show([...$this->sshCommand(), 'content', 'import', $this->host, $client ?? '.', ...$options]);
    }

    /**
     * Writes the content of the site into a file, as a tar, and shows what the site left out of it.
     *
     * @return int The exit code of the export.
     */
    public function exportContent(string $file): int
    {
        return Process::show([...$this->sshCommand(), 'content', 'export', $this->host], $file);
    }

    /**
     * Where rsync writes on the server: the `sites/` directory of the platform, which the server ties the key
     * to, or the directory of a client in it.
     */
    private function target(string|null $client = null): string
    {
        return self::USER . '@' . $this->host . ':' . ($client === null ? '.' : "$client/");
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

        return "ssh -i $key " . implode(' ', self::SSH_OPTIONS);
    }

    /**
     * SSH to the server, as a command a word of the server is added to.
     *
     * @return string[]
     */
    private function sshCommand(): array
    {
        return ['ssh', '-i', self::expand($this->key), ...self::SSH_OPTIONS, self::USER . '@' . $this->host];
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
        return Process::run(['rsync', '-e', $this->ssh(), '--recursive', '--no-dirs', ...$arguments]);
    }
}
