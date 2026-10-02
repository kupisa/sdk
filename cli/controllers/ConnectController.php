<?php

namespace kupisa\cli\controllers;

use kupisa\cli\Config;
use kupisa\cli\Remote;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

/**
 * Connects this repository to a site, so its themes and modules can be uploaded there.
 */
class ConnectController extends Controller
{
    private const DEFAULT_KEY = '~/.ssh/id_ed25519';

    /**
     * Asks for the site and the SSH key, tries the connection and remembers the site.
     */
    public function actionIndex(): int
    {
        if (!is_dir('themes') && !is_dir('modules')) {
            $this->stderr("Run this in the root of your repository, where themes/ and modules/ are.\n", Console::FG_RED);

            return ExitCode::USAGE;
        }

        $config = Config::load();

        $host = $this->prompt('Site (host name):', [
            'required'  => true,
            'validator' => fn (string $input, string|null &$error): bool => $this->isHost($input, $error),
        ]);

        $key = $this->prompt('SSH key:', [
            'default'   => $config->lastKey() ?? self::DEFAULT_KEY,
            'validator' => fn (string $input, string|null &$error): bool => $this->isKey($input, $error),
        ]);

        $host = $this->normalize($host);

        $this->stdout("\nConnecting to $host... ");

        $error = (new Remote($host, $key))->check();

        if ($error !== null) {
            $this->stdout("failed\n\n", Console::FG_RED);
            $this->stderr("$error\n\n");
            $this->explain($key);

            return ExitCode::UNAVAILABLE;
        }

        $this->stdout("OK\n", Console::FG_GREEN);

        $config->add($host, $key);
        $config->save();

        $this->stdout($config->current === $host ?
            "$host is the selected site.\n" :
            "Connected. The selected site is still {$config->current}; change it with `kupisa switch`.\n");

        return ExitCode::OK;
    }

    /**
     * A host name as it is kept: lowercase, without the scheme and the slash of an address pasted from a browser.
     */
    private function normalize(string $host): string
    {
        $host = preg_replace('~^https?://~', '', strtolower(trim($host)));

        return rtrim($host, '/');
    }

    /**
     * Whether what was typed is a host name.
     */
    private function isHost(string $input, string|null &$error): bool
    {
        if (preg_match('/^[a-z0-9]([a-z0-9.-]*[a-z0-9])?$/', $this->normalize($input))) {
            return true;
        }

        $error = 'Type the host name of the site, like my-shop.kupisa.shop.';

        return false;
    }

    /**
     * Whether what was typed is the path of a file.
     */
    private function isKey(string $input, string|null &$error): bool
    {
        if (is_file(Remote::expand($input))) {
            return true;
        }

        $error = "There is no file at $input.";

        return false;
    }

    /**
     * Says what to do when the server did not let the key in: the public key has to be added there.
     */
    private function explain(string $key): void
    {
        $public = Remote::expand($key) . '.pub';

        $this->stdout("The site was not connected. If the server does not know your key yet, send us the public key");

        if (is_file($public)) {
            $this->stdout(":\n\n" . trim((string) file_get_contents($public)) . "\n\n");
        } else {
            $this->stdout(" ($key.pub).\n");
        }

        $this->stdout("Once it is added, run `kupisa connect` again.\n");
    }
}
