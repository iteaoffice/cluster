<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace Cluster\Navigation\Invokable;

use Cluster\Entity\Cluster;
use General\Navigation\Invokable\AbstractNavigationInvokable;
use Laminas\Navigation\Page\Mvc;

/**
 * Class ClusterLabel
 * @package Cluster\Navigation\Invokable
 */
final class ClusterLabel extends AbstractNavigationInvokable
{
    public function __invoke(Mvc $page): void
    {
        $label = $this->translate('txt-nav-cluster');
        if ($this->getEntities()->containsKey(Cluster::class)) {

            /** @var Cluster $cluster */
            $cluster = $this->getEntities()->get(Cluster::class);

            $page->setParams(
                array_merge(
                    $page->getParams(),
                    [
                        'id' => $cluster->getId(),
                    ]
                )
            );
            $label = (string)$cluster;
        }
        $page->set('label', $label);
    }
}
