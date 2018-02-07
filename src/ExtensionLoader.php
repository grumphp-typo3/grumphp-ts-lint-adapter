<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 12.01.18
 * Time: 14:27
 */

namespace Pluswerk\GrumphpTsLinter;

define("APP_NAME", "typoscript-lint");
define("APP_VERSION", "dev");

use GrumPHP\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class ExtensionLoader implements ExtensionInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @return Definition
     * @throws \Exception
     */
    public function load(ContainerBuilder $container)
    {

        $container->register('linter.tslint', TsLinter::class);
        return $container->register('task.tslint', TsLinterTask::class)
                         ->addArgument($container->get('config'))
                         ->addArgument($container->get('linter.tslint'))
                         ->addArgument($container->get('process_builder'))
                         ->addArgument($container->get('formatter.raw_process'))
                         ->addTag('grumphp.task', ['config' => 'tslint']);
    }
}