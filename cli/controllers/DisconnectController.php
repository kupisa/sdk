<?php

namespace kupisa\cli\controllers;

use kupisa\cli\Config;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

/**
 * Forgets a connected site. Nothing on the site changes.
 */
class DisconnectController extends Controller
{
    /**
     * Takes the site named off the list of the connected sites.
     *
     * @param string $host The host name of a connected site.
     */
    public function actionIndex(string $host): int
    {
        $config = Config::load();

        if (!$config->has($host)) {
            $this->stderr("$host is not connected.\n", Console::FG_RED);

            return ExitCode::USAGE;
        }

        $config->remove($host);
        $config->save();

        $this->stdout("$host was disconnected.\n", Console::FG_GREEN);

        if ($config->current !== null) {
            $this->stdout("The selected site is {$config->current}.\n");
        }

        return ExitCode::OK;
    }
}
