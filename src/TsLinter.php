<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 15.01.18
 * Time: 10:11
 */

namespace Pluswerk\GrumphpTsLinter;


use GrumPHP\Collection\LintErrorsCollection;
use GrumPHP\Linter\LinterInterface;
use Helmich\TypoScriptLint\Linter\Linter as TypoScriptLinter;
use Helmich\TypoScriptLint\Linter\LinterConfiguration;
use Helmich\TypoScriptLint\Linter\Report\Report;
use Helmich\TypoScriptLint\Linter\ReportPrinter\ConsoleReportPrinter;
use Helmich\TypoScriptLint\Linter\Sniff\SniffLocator;
use Helmich\TypoScriptLint\Logging\CompactConsoleLogger;
use Helmich\TypoScriptLint\Logging\LinterLoggerInterface;
use Helmich\TypoScriptLint\Util\Filesystem;
use Helmich\TypoScriptLint\Util\Finder;
use Helmich\TypoScriptParser\Parser\Parser;
use Helmich\TypoScriptParser\Tokenizer\Tokenizer;
use Pluswerk\GrumphpTsLinter\Logger\DummyLogger;
use SplFileInfo;

class TsLinter implements LinterInterface
{
    /**
     * @var TypoScriptLinter
     */
    private $typoScriptLinter;

    /**
     * @var Report
     */
    private $report;

    /**
     * @var LinterConfiguration
     */
    private $linterConfiguration;

    /**
     * @var LinterLoggerInterface
     */
    private $linterLogger;

    /**
     * @var Finder
     */
    private $finder;

    public function __construct()
    {
        $tokenizer = new Tokenizer();
        $parser = new Parser($tokenizer);
        $sniffLocator = new SniffLocator();
        $this->typoScriptLinter = new TypoScriptLinter($tokenizer, $parser, $sniffLocator);
        $this->report = new Report();
        $this->linterLogger = new DummyLogger();
    }

    /**
     * @param SplFileInfo $file
     *
     * @return LintErrorsCollection
     */
    public function lint(SplFileInfo $file)
    {
        $errors = new \Pluswerk\GrumphpTsLinter\LintErrorsCollection();
        $fileReport = $this->typoScriptLinter->lintFile(
            $file->getRealPath(),
            $this->report,
            $this->linterConfiguration,
            $this->linterLogger
        );
        foreach ($fileReport->getIssues() as $issue) {
            $errors->add(new LintError($fileReport->getFilename(), $issue));
        }

        return $errors;
    }

    /**
     * @return bool
     */
    public function isInstalled()
    {
        return true;
    }

    /**
     * @param LinterConfiguration $linterConfiguration
     */
    public function setLinterConfiguration(LinterConfiguration $linterConfiguration)
    {
        $this->linterConfiguration = $linterConfiguration;
    }
}