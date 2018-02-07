<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 12.01.18
 * Time: 14:31
 */

namespace Pluswerk\GrumphpTsLinter;


use GrumPHP\Collection\FilesCollection;
use GrumPHP\Runner\TaskResult;
use GrumPHP\Runner\TaskResultInterface;
use GrumPHP\Task\AbstractExternalTask;
use GrumPHP\Task\AbstractLinterTask;
use GrumPHP\Task\Context\ContextInterface;
use GrumPHP\Task\Context\GitPreCommitContext;
use GrumPHP\Task\Context\RunContext;
use Helmich\TypoScriptLint\Linter\LinterConfiguration;
use Helmich\TypoScriptLint\Util\Filesystem;
use Helmich\TypoScriptLint\Util\Finder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Helmich\TypoScriptLint\Application;
use Helmich\TypoScriptParser\TypoScriptParserExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class TsLinterTask extends AbstractLinterTask
{
    /**
     * @var LinterConfiguration
     */
    private $linterConfiguration;

    /**
     * @return string
     */
    public function getName()
    {
        return 'tslint';
    }

    /**
     * @return OptionsResolver
     */
    public function getConfigurableOptions()
    {
        $resolver = parent::getConfigurableOptions();
        $resolver->setDefaults(
            [
                'paths' => [],
                'sniffs' => [],
                'filePatterns' => []
            ]
        );

        $resolver->addAllowedTypes('paths', ['array']);
        $resolver->addAllowedTypes('sniffs', ['array']);
        $resolver->addAllowedTypes('filePatterns', ['array']);

        return $resolver;
    }

    /**
     * This methods specifies if a task can run in a specific context.
     *
     * @param ContextInterface $context
     *
     * @return bool
     */
    public function canRunInContext(ContextInterface $context)
    {
        return ($context instanceof GitPreCommitContext || $context instanceof RunContext);
    }

    /**
     * @param ContextInterface $context
     *
     * @return TaskResult|TaskResultInterface
     */
    public function run(ContextInterface $context)
    {
        $grumphpConf = $this->getConfiguration();

        $linterConfiguration = new LinterConfiguration();
        $linterConfiguration->setConfiguration($grumphpConf);

        $this->linter->setLinterConfiguration($linterConfiguration);

        $symfonyFinder = new \Symfony\Component\Finder\Finder();
        $fileSystem = new Filesystem();
        $finder = new Finder($symfonyFinder,$fileSystem);

        $paths    = $linterConfiguration->getPaths();
        $patterns = $linterConfiguration->getFilePatterns();

        $files = $finder->getFilenames($paths, $patterns);

        $fileCollection = new FilesCollection();
        foreach ($files as $file) {
            $fileCollection->add(new \SplFileInfo($file));
        }

        $lintErrors = $this->lint($fileCollection);

        if ($lintErrors->count()) {
            return TaskResult::createFailed($this, $context, (string)$lintErrors);
        }

        return TaskResult::createPassed($this, $context);
    }

    /**
     * @param FilesCollection $files
     *
     * @return LintErrorsCollection
     */
    protected function lint(FilesCollection $files)
    {
        $this->guardLinterIsInstalled();

        // Skip ignored patterns:
        $configuration = $this->getConfiguration();
        foreach ($configuration['ignore_patterns'] as $pattern) {
            $files = $files->notPath($pattern);
        }

        // Lint every file:
        $lintErrors = new LintErrorsCollection();
        foreach ($files as $file) {
            foreach ($this->linter->lint($file) as $error) {
                $lintErrors->add($error);
            }
        }

        return $lintErrors;
    }
}
