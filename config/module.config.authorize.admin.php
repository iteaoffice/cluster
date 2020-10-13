<?php

declare(strict_types=1);

namespace Cluster;

use BjyAuthorize\Guard\Route;

return [
    'bjyauthorize' => [
        // resource providers provide a list of resources that will be tracked
        // in the ACL. like roles, they can be hierarchical
        'guards' => [
            Route::class => [
                ['route' => 'zfcadmin/cluster/list', 'roles' => ['office'],],
                ['route' => 'zfcadmin/cluster/edit', 'roles' => ['office'],],
                ['route' => 'zfcadmin/cluster/view', 'roles' => ['office'],],
                ['route' => 'zfcadmin/cluster/new', 'roles' => ['office'],],
            ],
        ],
    ],
];
