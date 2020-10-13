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

namespace Cluster\Navigation\Invokable;

use General\Navigation\Invokable\AbstractNavigationInvokable;
use Cluster\Entity\Cluster;
use Laminas\Navigation\Page\Mvc;

/**
 * Class ProjectLabel
 *
 * @package Project\Navigation\Invokable
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
