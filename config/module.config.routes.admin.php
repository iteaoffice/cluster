<?php

/**
 * ITEA Office copyright message placeholder
 *
 * @category    Contact
 * @package     Config
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

namespace Cluster;

return [
    'router' => [
        'routes' => [
            'zfcadmin' => [
                'cluster' => [
                    'type'          => 'Segment',
                    'options'       => [
                        'route'    => '/cluster',
                        'defaults' => [
                            'action'     => 'list',
                            'controller' => Controller\ClusterController::class,
                        ],
                    ],
                    'may_terminate' => true,
                    'child_routes'  => [
                        'list' => [
                            'type'     => 'Segment',
                            'priority' => 1000,
                            'options'  => [
                                'route'    => '/list[/f-:encodedFilter][/page-:page].html',
                                'defaults' => [
                                    'action' => 'list',
                                ],
                            ],
                        ],
                        'new'  => [
                            'type'    => 'Segment',
                            'options' => [
                                'route'    => '/new.html',
                                'defaults' => [
                                    'action' => 'new',
                                ],
                            ],
                        ],
                        'view' => [
                            'type'    => 'Segment',
                            'options' => [
                                'route'    => '/view/[:id].html',
                                'defaults' => [
                                    'action' => 'view',

                                ],
                            ],
                        ],
                        'edit' => [
                            'type'    => 'Segment',
                            'options' => [
                                'route'    => '/edit/[:id].html',
                                'defaults' => [
                                    'action' => 'edit',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
