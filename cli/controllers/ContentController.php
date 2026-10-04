<?php

namespace kupisa\cli\controllers;

use kupisa\cli\Files;
use kupisa\cli\UploadController;
use PharData;
use yii\console\ExitCode;
use yii\helpers\Console;
use yii\helpers\FileHelper;

/**
 * Carries the content of the selected site (its pages, menus, terms, pictures, areas) between the site and
 * `content/` of this repository, where it is a package of files (see the README).
 */
class ContentController extends UploadController
{
    /** @var bool Whether the pages, terms, menus and files of the site that `content/` does not hold are removed. */
    public bool $delete = false;

    /** @var bool Whether the site only tells what the push would do, and stays as it is. */
    public bool $dryRun = false;

    /**
     * @inheritdoc
     */
    public function options($actionID): array
    {
        return [...parent::options($actionID), ...($actionID === 'push' ? ['delete', 'dryRun'] : [])];
    }

    /**
     * Brings the site to `content/`: uploads the package and imports it. What the site has by its uid is
     * updated, what it does not have is created, and a package with an error changes nothing; the site tells
     * what it did.
     */
    public function actionPush(): int
    {
        $remote = $this->remote();

        if ($remote === null) {
            return ExitCode::USAGE;
        }

        if (!is_dir(Files::CONTENT)) {
            $this->stderr("There is no content/ directory to push. `kupisa content/pull` writes the one of the site.\n", Console::FG_RED);

            return ExitCode::USAGE;
        }

        $this->stdout("Uploading content/ to {$remote->host}\n", Console::BOLD);

        [$exitCode, $output] = $remote->uploadContent(Files::client());

        if ($exitCode !== 0) {
            $this->stderr("The upload failed:\n$output\n", Console::FG_RED);

            return ExitCode::UNAVAILABLE;
        }

        $options = [...($this->delete ? ['--delete'] : []), ...($this->dryRun ? ['--dry-run'] : [])];

        return $remote->importContent(Files::client(), $options);
    }

    /**
     * Writes the content of the site into `content/`, in place of the package it holds: every record of the
     * site with its uid, so what is changed here and pushed changes that record.
     */
    public function actionPull(): int
    {
        $remote = $this->remote();

        if ($remote === null) {
            return ExitCode::USAGE;
        }

        $this->stdout("Downloading the content of {$remote->host}\n", Console::BOLD);

        $archive  = sys_get_temp_dir() . '/kupisa-content-' . uniqid() . '.tar';
        $exitCode = $remote->exportContent($archive);

        if ($exitCode !== 0) {
            $this->stderr("The download failed.\n", Console::FG_RED);
            @unlink($archive);

            return ExitCode::UNAVAILABLE;
        }

        // Unpacked next to the package first, so a download that cannot be read leaves the package as it was.
        $unpacked = Files::CONTENT . '/.pull-' . uniqid();

        FileHelper::createDirectory($unpacked);

        (new PharData($archive))->extractTo($unpacked, null, true);
        unlink($archive);

        foreach (Files::CONTENT_PARTS as $part) {
            $path = Files::CONTENT . "/$part";

            is_dir($path) ? FileHelper::removeDirectory($path) : @unlink($path);

            if (file_exists("$unpacked/$part")) {
                rename("$unpacked/$part", $path);
            }
        }

        FileHelper::removeDirectory($unpacked);

        $this->stdout("The content was written to content/.\n", Console::FG_GREEN);

        return ExitCode::OK;
    }
}
