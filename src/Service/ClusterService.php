<?php

/**
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/project for the canonical source repository
 */

declare(strict_types=1);

namespace Cluster\Service;

use Cluster\Entity;

/**
 * Class ClusterService
 * @package Cluster\Service
 */
class ClusterService extends AbstractService
{
    public function findClusterById(int $id): ?Entity\Cluster
    {
        return $this->entityManager->find(Entity\Cluster::class, $id);
    }

    public function canDeleteCluster(Entity\Cluster $cluster): bool
    {
        $cannotDeleteClusterReasons = [];

        return count($cannotDeleteClusterReasons) === 0;
    }
}
