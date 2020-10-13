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

use Affiliation\Entity\Affiliation;
use General\Entity\Country;
use Cluster\Entity\Cluster;
use Cluster\Entity\Feedback;
use Cluster\Entity\Type;
use Project\Entity\Funding\Funding;
use Project\Entity\Funding\Source;
use Project\Entity\Funding\Status;
use Project\Entity\Project;

use function in_array;
use function strlen;

/**
 * Class ClusterService
 * @package Cluster\Service
 */
class ClusterService extends AbstractService
{
}
