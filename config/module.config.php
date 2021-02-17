<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

namespace Cluster;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use General\Navigation\Factory\NavigationInvokableFactory;
use General\View\Factory\ImageHelperFactory;
use General\View\Factory\LinkHelperFactory;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\Stdlib;

$config = [
    'controllers'        => [
        'factories' => [
            Controller\ClusterController::class => ConfigAbstractFactory::class,
            Controller\ImageController::class   => ConfigAbstractFactory::class,
        ],
    ],
    'controller_plugins' => [
        'aliases'   => [
            'getClusterFilter' => Controller\Plugin\GetFilter::class,
        ],
        'factories' => [
            Controller\Plugin\GetFilter::class => Factory\InvokableFactory::class,
        ],
    ],
    'view_manager'       => [
        'template_map' => include __DIR__ . '/../template_map.php',
    ],
    'view_helpers'       => [
        'aliases'    => [
            'clusterLink' => View\Helper\ClusterLink::class,
            'clusterLogo' => View\Helper\Cluster\Logo::class,
        ],
        'invokables' => [
        ],
        'factories'  => [
            View\Helper\ClusterLink::class  => LinkHelperFactory::class,
            View\Helper\Cluster\Logo::class => ImageHelperFactory::class,
        ],
    ],
    'service_manager'    => [
        'factories' => [
            Acl\Assertion\ClusterAssertion::class    => Factory\InvokableFactory::class,
            Form\ClusterForm::class                  => ConfigAbstractFactory::class,
            InputFilter\ClusterFilter::class         => Factory\InputFilterFactory::class,
            Service\ClusterService::class            => ConfigAbstractFactory::class,
            Service\FormService::class               => Factory\FormServiceFactory::class,
            Navigation\Invokable\ClusterLabel::class => NavigationInvokableFactory::class

        ],
    ],
    'doctrine'           => [
        'driver' => [
            'cluster_annotation_driver' => [
                'class' => AnnotationDriver::class,
                'paths' => [__DIR__ . '/../src/Entity/'],
            ],
            'orm_default'               => [
                'drivers' => [
                    'Cluster\Entity' => 'cluster_annotation_driver',
                ],
            ],
        ],
    ],
];
foreach (Stdlib\Glob::glob(__DIR__ . '/module.config.{,*}.php', Stdlib\Glob::GLOB_BRACE) as $file) {
    $config = Stdlib\ArrayUtils::merge($config, include $file);
}
return $config;
