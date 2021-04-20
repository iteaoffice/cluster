<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

namespace Cluster;

return [
    'router' => [
        'routes' => [
            'community' => [
                'child_routes' => [
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
                            'reporting' => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/reporting.html',
                                    'defaults' => [
                                        'action' => 'reporting',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
