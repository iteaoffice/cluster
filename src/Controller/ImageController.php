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

use Laminas\Http\Response;
use Cluster\Entity\Cluster\Logo;
use Cluster\Service\ClusterService;
use Laminas\Mvc\Controller\AbstractActionController;

/**
 * Class ImageController
 *
 * @package Cluster\Controller
 */
final class ImageController extends AbstractActionController
{
    private ClusterService $clusterService;

    public function __construct(ClusterService $clusterService)
    {
        $this->clusterService = $clusterService;
    }

    public function clusterLogoAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var Logo $logo */
        $logo = $this->clusterService->find(Logo::class, (int) $this->params('id'));


        if (null === $logo) {
            return $response;
        }

        $response->getHeaders()
            ->addHeaderLine('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 36000))
            ->addHeaderLine('Cache-Control: max-age=36000, must-revalidate')
            ->addHeaderLine('Pragma: public')
            ->addHeaderLine('Content-Type: ' . $logo->getContentType()->getContentType());

        $response->setContent(stream_get_contents($logo->getClusterLogo()));

        return $response;
    }
}
