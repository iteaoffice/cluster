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
    'navigation' => [
        'admin' => [
            'config' => [
                'pages' => [
                    'cluster' => [
                        'label' => _("txt-nav-cluster-list"),
                        'route' => 'zfcadmin/cluster/list',
                        'pages' => [
                            'view' => [
                                'route'   => 'zfcadmin/cluster/view',
                                'visible' => false,
                                'params'  => [
                                    'entities'   => [
                                        'id' => Entity\Cluster::class,
                                    ],
                                    'invokables' => [
                                        Navigation\Invokable\ClusterLabel::class,
                                    ],
                                ],
                                'pages'   => [
                                    'edit' => [
                                        'label'   => _('txt-nav-edit'),
                                        'route'   => 'zfcadmin/cluster/edit',
                                        'visible' => false,
                                        'params'  => [
                                            'entities' => [
                                                'id' => Entity\Cluster::class,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'new'  => [
                                'label' => _('txt-nav-new-cluster'),
                                'route' => 'zfcadmin/cluster/new',
                            ],
                        ],
                    ],
                ]
            ],
        ],
    ],
];
