<?php

namespace kupisa\cli\controllers;

use kupisa\cli\Config;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

/**
 * Selects the connected site the other commands work on.
 */
class SwitchController extends Controller
{
    /**
     * Selects the site named, or lists the connected sites and asks which one.
     *
     * @param string|null $host The host name of a connected site.
     */
    public function actionIndex(string|null $host = null): int
    {
        $config = Config::load();
        $hosts  = array_keys($config->sites);

        if ($hosts === []) {
            $this->stderr("No site is connected yet. Run `kupisa connect` first.\n", Console::FG_RED);

            return ExitCode::USAGE;
        }

        $host ??= $this->choose($hosts, $config->current);

        if (!$config->has($host)) {
            $this->stderr("$host is not connected. Run `kupisa connect` first.\n", Console::FG_RED);

            return ExitCode::USAGE;
        }

        $config->current = $host;
        $config->save();

        $this->stdout("$host is the selected site.\n", Console::FG_GREEN);

        return ExitCode::OK;
    }

    /**
     * Lists the sites by number, the selected one marked, and asks for the number of one.
     *
     * @param string[] $hosts
     */
    private function choose(array $hosts, string|null $current): string
    {
        foreach ($hosts as $index => $host) {
            $this->stdout(sprintf("  %d  %s%s\n", $index + 1, $host, $host === $current ? '  (selected)' : ''));
        }

        $number = $this->prompt('Site:', [
            'default'   => (string) (array_search($current, $hosts, true) + 1),
            'validator' => function (string $input, string|null &$error) use ($hosts): bool {
                $error = 'Type the number of a site.';

                return ctype_digit($input) && isset($hosts[(int) $input - 1]);
            },
        ]);

        return $hosts[(int) $number - 1];
    }
}
