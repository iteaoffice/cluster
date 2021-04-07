<?php

/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2004-2017 Jield BV (https://jield.nl)
 * @license     https://jield.net/license.txt proprietary
 * @link        https://jield.net
 */

declare(strict_types=1);

namespace ClusterTest;

use Cluster\Module;
use Testing\Util\AbstractServiceTest;
use Laminas\Mvc\Application;
use Laminas\Mvc\Controller\PluginManager;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\View\HelperPluginManager;

use function is_string;

/**
 * Class ModuleTest
 *
 * @package ClusterTest
 */
class ModuleTest extends AbstractServiceTest
{
    protected bool $setUpForEveryTest = false;

    public function testCanFindConfiguration(): void
    {
        $module = new Module();
        $config = $module->getConfig();

        self::assertArrayHasKey('service_manager', $config);
        self::assertArrayHasKey(ConfigAbstractFactory::class, $config);
    }

    public function testInstantiationOfConfigAbstractFactories(): void
    {
        $module = new Module();
        $config = $module->getConfig();

        $abstractFacories = $config[ConfigAbstractFactory::class] ?? [];

        foreach ($abstractFacories as $service => $dependencies) {
            // Skip the Filters
            if (strpos($service, 'Filter') !== false) {
                continue;
            }
            if (strpos($service, 'Handler') !== false) {
                continue;
            }

            $instantiatedDependencies = [];
            foreach ($dependencies as $dependency) {
                if ($dependency === 'Application') {
                    $dependency = Application::class;
                }
                if ($dependency === 'Config') {
                    $dependency = [];
                }
                if ($dependency === 'ViewHelperManager') {
                    $dependency = HelperPluginManager::class;
                }
                if ($dependency === 'ControllerPluginManager') {
                    $dependency = PluginManager::class;
                }

                if (is_string($dependency)) {
                    $instantiatedDependencies[] = $this->getMockBuilder($dependency)->disableOriginalConstructor()
                        ->getMock();
                } else {
                    $instantiatedDependencies[] = [];
                }
            }

            $instance = new $service(...$instantiatedDependencies);

            self::assertInstanceOf($service, $instance);
        }
    }
}
