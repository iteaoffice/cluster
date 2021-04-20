<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace Cluster\Controller;

use Cluster\Controller\Plugin\GetFilter;
use Contact\Entity\Contact;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\Mvc\Plugin\Identity\Identity;
use Laminas\View\Model\ViewModel;

/**
 * @method Identity|Contact identity()
 * @method FlashMessenger flashMessenger()
 * @method GetFilter getClusterFilter()
 */
final class ClusterController extends AbstractActionController
{
    public function reportingAction(): ViewModel
    {
        return new ViewModel();
    }
}
