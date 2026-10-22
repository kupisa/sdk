<?php

namespace kupisa\cli\controllers;

use kupisa\cli\UploadController;
use yii\console\ExitCode;
use yii\helpers\Console;

/**
 * Tells what the selected site is: what a theme or a module made for it is built for.
 */
class InfoController extends UploadController
{
    /**
     * Asks the site what it is now (its uid, name, region, language, theme and enabled modules), keeps it in
     * `.kupisa.json` and writes it out.
     */
    public function actionIndex(): int
    {
        $remote = $this->remote();

        if ($remote === null) {
            return ExitCode::USAGE;
        }

        $info = $this->refresh($remote);

        if (is_string($info)) {
            $this->stderr("The site did not answer:\n$info\n", Console::FG_RED);

            return ExitCode::UNAVAILABLE;
        }

        $lines = [
            'uid'          => $info['uid'],
            'name'         => $info['name'],
            'region'       => $info['region'] ?? 'none',
            'language'     => $info['language'],
            'theme'        => $info['theme'],
            'core modules' => implode(', ', $info['modules']['core']) ?: 'none',
            'modules'      => implode(', ', $info['modules']['regular']) ?: 'none',
        ];

        $this->stdout("{$remote->host}\n", Console::BOLD);

        foreach ($lines as $label => $value) {
            $this->stdout(sprintf("  %-13s %s\n", $label, $value));
        }

        return ExitCode::OK;
    }
}
