<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace Cluster\Service;

use Cluster\Entity;
use Program\Entity\Call\Call;
use Project\Entity\Project;

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

    public function getCusterByProgramCall(Call $call): array
    {
        $clusters = [];

        foreach ($call->getCluster() as $cluster) {
            $clusters[$cluster->getId()] = $cluster;
        }

        foreach ($call->getProgram()->getCluster() as $cluster) {
            $clusters[$cluster->getId()] = $cluster;
        }

        ksort($clusters);

        return $clusters;
    }

    public function getPrimaryClusterByProject(Project $project): ?Entity\Cluster
    {
        /** @var \Project\Entity\Project\Cluster|false $primary */
        $primary = $project->getProjectCluster()->filter(static function (\Project\Entity\Project\Cluster $projectCluster) {
            return $projectCluster->isPrimary();
        })->first();

        if (! $primary) {
            return null;
        }

        return $primary->getCluster();
    }

    public function getSecondaryClusterByProject(Project $project): ?Entity\Cluster
    {
        /** @var \Project\Entity\Project\Cluster|false $primary */
        $primary = $project->getProjectCluster()->filter(static function (\Project\Entity\Project\Cluster $projectCluster) {
            return $projectCluster->isSecondary();
        })->first();

        if (! $primary) {
            return null;
        }

        return $primary->getCluster();
    }

    public function canDeleteCluster(Entity\Cluster $cluster): bool
    {
        $cannotDeleteClusterReasons = [];

        return count($cannotDeleteClusterReasons) === 0;
    }
}
