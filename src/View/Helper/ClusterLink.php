<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace Cluster\View\Helper;

use Cluster\Entity\Cluster;
use General\ValueObject\Link\Link;
use General\View\Helper\AbstractLink;

/**
 * Class ClusterLink
 * @package General\View\Helper
 */
final class ClusterLink extends AbstractLink
{
    public function __invoke(
        Cluster $cluster = null,
        string $action = 'view',
        string $show = 'name'
    ): string {
        $cluster ??= new Cluster();

        $routeParams = [];
        $showOptions = [];

        if (null !== $cluster) {
            $routeParams['id']   = $cluster->getId();
            $showOptions['name'] = $cluster->getName();
        }

        switch ($action) {
            case 'new':
                $linkParams = [
                    'icon'  => 'fas fa-plus',
                    'route' => 'zfcadmin/cluster/new',
                    'text'  => $showOptions[$show]
                        ?? $this->translator->translate('txt-new-cluster')
                ];
                break;
            case 'edit':
                $linkParams = [
                    'icon'  => 'far fa-edit',
                    'route' => 'zfcadmin/cluster/edit',
                    'text'  => $showOptions[$show]
                        ?? $this->translator->translate('txt-edit-cluster')
                ];
                break;
            case 'view':
                $linkParams = [
                    'icon'  => 'fas fa-link',
                    'route' => 'zfcadmin/cluster/view',
                    'text'  => $showOptions[$show] ?? $cluster->getName()
                ];
                break;
        }

        $linkParams['action']      = $action;
        $linkParams['show']        = $show;
        $linkParams['routeParams'] = $routeParams;

        return $this->parse(Link::fromArray($linkParams));
    }
}
