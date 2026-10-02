<?php

namespace kupisa\cli;

use yii\console\Exception;

/**
 * The sites a repository is connected to, kept in `.kupisa.json` in its root: every site by its host name with
 * the SSH key it is reached with, and the selected one, which the commands work on. The file belongs to one
 * developer on one computer, so it is kept out of git.
 */
class Config
{
    public const FILE = '.kupisa.json';

    /** @var array<string, array{key: string}> The connected sites by host name, in the order they were connected. */
    public array $sites = [];

    /** @var string|null The host name of the selected site, null while no site is connected. */
    public string|null $current = null;

    /**
     * Reads the file of the current directory, or gives an empty configuration when there is none yet.
     */
    public static function load(): self
    {
        $config = new self();

        if (!is_file(self::FILE)) {
            return $config;
        }

        $data = json_decode((string) file_get_contents(self::FILE), true);

        if (!is_array($data)) {
            throw new Exception(self::FILE . ' is not valid JSON. Fix it, or delete it and connect again.');
        }

        $config->sites   = $data['sites'] ?? [];
        $config->current = $data['current'] ?? null;

        return $config;
    }

    /**
     * Writes the file, and names it in `.gitignore` the first time.
     */
    public function save(): void
    {
        if (!is_file(self::FILE)) {
            $this->ignore();
        }

        $data = [
            'current' => $this->current,
            'sites'   => $this->sites,
        ];

        file_put_contents(self::FILE, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
    }

    /**
     * Whether a site is connected.
     */
    public function has(string $host): bool
    {
        return isset($this->sites[$host]);
    }

    /**
     * Connects a site, or gives a connected one another key. The first site connected is the selected one.
     */
    public function add(string $host, string $key): void
    {
        $this->sites[$host] = ['key' => $key];
        $this->current    ??= $host;
    }

    /**
     * Disconnects a site. When it was the selected one, the first of those left is selected.
     */
    public function remove(string $host): void
    {
        unset($this->sites[$host]);

        if ($this->current === $host) {
            $this->current = array_key_first($this->sites);
        }
    }

    /**
     * The key of the site connected last, offered as the key of the next one.
     */
    public function lastKey(): string|null
    {
        $last = array_key_last($this->sites);

        return $last === null ? null : $this->sites[$last]['key'];
    }

    /**
     * Adds the file to `.gitignore`, unless it is named there already.
     */
    private function ignore(): void
    {
        $lines = is_file('.gitignore') ? file('.gitignore', FILE_IGNORE_NEW_LINES) : [];

        if (in_array('/' . self::FILE, $lines, true)) {
            return;
        }

        $comment = '# The sites this copy is connected to (`vendor/bin/kupisa connect`), with the SSH key of this computer.';

        file_put_contents('.gitignore', ($lines === [] ? '' : "\n") . "$comment\n/" . self::FILE . "\n", FILE_APPEND);
    }
}
