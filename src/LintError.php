<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 06.02.18
 * Time: 09:46
 */

namespace Pluswerk\GrumphpTsLinter;


use Helmich\TypoScriptLint\Linter\Report\Issue;

class LintError
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var Issue
     */
    private $issue;

    /**
     * LintError constructor.
     *
     * @param $filename
     * @param Issue $issue
     */
    public function __construct($filename, Issue $issue)
    {
        $this->issue = $issue;
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return Issue
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * @param Issue $issue
     */
    public function setIssue($issue)
    {
        $this->issue = $issue;
    }
}