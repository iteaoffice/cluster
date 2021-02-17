<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

namespace Cluster\View\Helper\Cluster;

use Cluster\Entity\Cluster;
use General\ValueObject\Image\Image;
use General\ValueObject\Image\ImageDecoration;
use General\View\Helper\AbstractImage;

/**
 * Class Logo
 * @package Cluster\View\Helper\Cluster
 */
final class Logo extends AbstractImage
{
    public function __invoke(
        Cluster $cluster,
        int $width = null,
        string $show = ImageDecoration::SHOW_IMAGE
    ): string {
        if (! $cluster->hasLogo()) {
            return '';
        }

        $linkParams          = [];
        $linkParams['route'] = 'image/cluster-logo';
        $linkParams['show']  = $show;
        $linkParams['width'] = $width;

        /** @var Cluster\Logo $logo */
        $logo = $cluster->getLogo();

        $routeParams = [
            'id'          => $logo->getId(),
            'ext'         => $logo->getContentType()->getExtension(),
            'last-update' => $logo->getDateUpdated()->getTimestamp(),
        ];

        $linkParams['routeParams'] = $routeParams;

        return $this->parse(Image::fromArray($linkParams));
    }
}
