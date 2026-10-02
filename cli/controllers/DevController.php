<?php

namespace kupisa\cli\controllers;

use kupisa\cli\Files;
use kupisa\cli\Lint;
use kupisa\cli\Remote;
use kupisa\cli\UploadController;
use yii\console\ExitCode;
use yii\helpers\Console;

/**
 * Watches the themes and modules of this repository and uploads every change to the selected site at once.
 */
class DevController extends UploadController
{
    /** How long the watcher waits between two looks at the files, in microseconds. */
    private const INTERVAL = 500000;

    /** @var array<string, string> The PHP files with a syntax error, which are not uploaded: the error by path. */
    private array $broken = [];

    /**
     * Uploads everything once, then every file as soon as it is saved, and deletes on the site what is deleted
     * here, until it is stopped with Ctrl+C. A PHP file with a syntax error is not uploaded: the site keeps
     * the one it has until the error is fixed.
     */
    public function actionIndex(): int
    {
        $remote = $this->remote();

        if ($remote === null) {
            return ExitCode::USAGE;
        }

        $this->stdout("Uploading to {$remote->host}\n", Console::BOLD);

        $files        = Files::all();
        $this->broken = Lint::errors(array_keys($files));

        $this->reportErrors($this->broken);

        if (!$this->upload($remote)) {
            return ExitCode::UNAVAILABLE;
        }

        $this->stdout("Watching themes/ and modules/. Press Ctrl+C to stop.\n\n");
        $this->watch($remote, $files);
    }

    /**
     * Looks at the files again and again, and uploads as soon as one was changed, added or deleted.
     *
     * @param array<string, string> $files The files as they were uploaded last (see `Files::all()`).
     */
    private function watch(Remote $remote, array $files): never
    {
        while (true) {
            usleep(self::INTERVAL);

            $current = Files::all();
            $changed = array_keys(array_diff_assoc($current, $files));
            $deleted = array_keys(array_diff_key($files, $current));
            $files   = $current;

            if ($changed !== [] || $deleted !== []) {
                $this->check($changed, $deleted);
                $this->report($changed, $deleted);
                $this->upload($remote);
            }
        }
    }

    /**
     * Checks the syntax of the changed files and remembers which are broken; a file fixed or deleted no longer is.
     *
     * @param string[] $changed
     * @param string[] $deleted
     */
    private function check(array $changed, array $deleted): void
    {
        foreach ([...$changed, ...$deleted] as $file) {
            unset($this->broken[$file]);
        }

        $this->broken = [...$this->broken, ...Lint::errors($changed)];
    }

    /**
     * Writes out what changed, and the syntax error of a file that is not uploaded.
     *
     * @param string[] $changed
     * @param string[] $deleted
     */
    private function report(array $changed, array $deleted): void
    {
        foreach ($changed as $file) {
            if (isset($this->broken[$file])) {
                $this->stderr("  $file  not uploaded\n", Console::FG_RED);
                $this->stderr("    {$this->broken[$file]}\n");
            } else {
                $this->stdout("  $file\n");
            }
        }

        foreach ($deleted as $file) {
            $this->stdout("  $file  deleted\n", Console::FG_YELLOW);
        }
    }

    /**
     * Uploads everything but the broken files, and says so when the upload fails.
     */
    private function upload(Remote $remote): bool
    {
        Files::touchDirectories();

        [$exitCode, $output] = $remote->upload(Files::directories(), array_keys($this->broken));

        if ($exitCode !== 0) {
            $this->stderr("The upload failed:\n$output\n", Console::FG_RED);
        }

        return $exitCode === 0;
    }
}
