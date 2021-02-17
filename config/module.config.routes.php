<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace Cluster;

return [
    'router' => [
        'routes' => [
            'image'     => [
                'child_routes' => [
                    'cluster-logo' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/c/l/[:id]-[:last-update].[:ext]',
                            'defaults' => [
                                //Explicitly add the controller here as the assets are collected
                                'controller' => Controller\ImageController::class,
                                'action'     => 'cluster-logo',
                            ],
                        ],
                    ],
                ],
            ],
            'community' => [

            ],
        ],
    ],
];
