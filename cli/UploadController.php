<?php

namespace kupisa\cli;

use yii\console\Controller;
use yii\helpers\Console;

/**
 * What the commands that upload to a site share: the site they work on, the selected one unless `--site`
 * names another connected site.
 */
abstract class UploadController extends Controller
{
    /** @var string|null The host name of a connected site to work on in place of the selected one. */
    public string|null $site = null;

    /**
     * @inheritdoc
     */
    public function options($actionID): array
    {
        return [...parent::options($actionID), 'site'];
    }

    /**
     * The server of the site the command works on, or null, with the reason written out, when there is none.
     */
    protected function remote(): Remote|null
    {
        $config = Config::load();
        $host   = $this->site ?? $config->current;

        if ($host === null || !$config->has($host)) {
            $this->stderr(
                ($host === null ? 'No site is connected yet.' : "$host is not connected.") . " Run `kupisa connect` first.\n",
                Console::FG_RED,
            );

            return null;
        }

        return new Remote($host, $config->sites[$host]['key']);
    }

    /**
     * Writes out the syntax errors that keep files from being uploaded.
     *
     * @param array<string, string> $errors The error by the path of the file.
     */
    protected function reportErrors(array $errors): void
    {
        foreach ($errors as $file => $error) {
            $this->stderr("  $file\n", Console::FG_RED);
            $this->stderr("    $error\n");
        }
    }
}
