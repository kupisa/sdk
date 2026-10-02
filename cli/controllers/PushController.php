<?php

namespace kupisa\cli\controllers;

use kupisa\cli\Files;
use kupisa\cli\Lint;
use kupisa\cli\UploadController;
use yii\console\ExitCode;
use yii\helpers\Console;

/**
 * Uploads the themes and modules of this repository to the selected site, once.
 */
class PushController extends UploadController
{
    /**
     * Makes the themes and modules on the site the same as here: uploads what is new or changed and deletes
     * what is no longer here. Nothing is uploaded while a PHP file has a syntax error.
     */
    public function actionIndex(): int
    {
        $remote = $this->remote();

        if ($remote === null) {
            return ExitCode::USAGE;
        }

        $directories = Files::directories();

        if ($directories === []) {
            $this->stdout("There is no theme or module to upload.\n");

            return ExitCode::OK;
        }

        $errors = Lint::errors(array_keys(Files::all()));

        if ($errors !== []) {
            $this->stderr("Nothing was uploaded. Fix the syntax errors first:\n", Console::FG_RED);
            $this->reportErrors($errors);

            return ExitCode::DATAERR;
        }

        $this->stdout("Uploading to {$remote->host}\n", Console::BOLD);

        Files::touchDirectories();

        [$exitCode, $output] = $remote->upload($directories, verbose: true);

        if ($exitCode !== 0) {
            $this->stderr("The upload failed:\n$output\n", Console::FG_RED);

            return ExitCode::UNAVAILABLE;
        }

        $this->stdout($this->changes($output) . "Done.\n", Console::FG_GREEN);

        return ExitCode::OK;
    }

    /**
     * What rsync uploaded and deleted, a file per line, without its own remarks and the directories.
     */
    private function changes(string $output): string
    {
        $noise = '~^(Transfer starting|sending incremental|sent \d|total size)|/$~';
        $lines = array_filter(
            explode("\n", $output),
            fn (string $line): bool => $line !== '' && !preg_match($noise, $line),
        );

        return $lines === [] ? '' : '  ' . implode("\n  ", $lines) . "\n";
    }
}
