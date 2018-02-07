<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 15.01.18
 * Time: 10:38
 */

namespace Pluswerk\GrumphpTsLinter\Logger;


use Helmich\TypoScriptLint\Linter\Report\File;
use Helmich\TypoScriptLint\Linter\Report\Report;
use Helmich\TypoScriptLint\Logging\LinterLoggerInterface;

class DummyLogger implements LinterLoggerInterface
{

    /**
     * Called before linting any input file
     *
     * @param string[] $files The list of filenames to lint
     *
     * @return void
     */
    public function notifyFiles(array $files)
    {
        // TODO: Implement notifyFiles() method.
    }

    /**
     * Called before linting any specific file
     *
     * @param string $filename The name of the file to be linted
     *
     * @return void
     */
    public function notifyFileStart($filename)
    {
        // TODO: Implement notifyFileStart() method.
    }

    /**
     * Called before running a specific sniff on a file
     *
     * @param string $filename The name of the file to be linted
     * @param string $sniffClass The class name of the sniff to be run
     *
     * @return void
     */
    public function notifyFileSniffStart($filename, $sniffClass)
    {
        // TODO: Implement notifyFileSniffStart() method.
    }

    /**
     * Called after completing a specific sniff on a file
     *
     * @param string $filename The name of the file that was linted
     * @param string $sniffClass The class name of the sniff that was run
     * @param File $report The (preliminary) linting report for this file
     *
     * @return void
     */
    public function nofifyFileSniffComplete($filename, $sniffClass, File $report)
    {
        // TODO: Implement nofifyFileSniffComplete() method.
    }

    /**
     * Called after completing all sniffs on a file
     *
     * @param string $filename The name of the file that was linted
     * @param File $report The (final) linting report for this file
     *
     * @return void
     */
    public function notifyFileComplete($filename, File $report)
    {
        // TODO: Implement notifyFileComplete() method.
    }

    /**
     * Called after all files have been linted
     *
     * @param Report $report The final linting report for all files
     *
     * @return void
     */
    public function notifyRunComplete(Report $report)
    {
        // TODO: Implement notifyRunComplete() method.
    }
}