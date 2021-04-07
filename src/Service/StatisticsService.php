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

use Cluster\Entity\Statistics\Partner;
use Program\Entity\Funder;

/**
 * Class ClusterService
 * @package Cluster\Service
 */
class StatisticsService extends AbstractService
{
    public function deletePartnersByInternalIdentifier(string $internalIdentifier): void
    {
        $this->entityManager->getRepository(Partner::class)->deletePartnersByInternalIdentifier($internalIdentifier);
    }

    public function getResults(Funder $funder, array $filter, int $output = Partner::RESULT_PROJECT): array
    {
        return $this->entityManager->getRepository(Partner::class)->getResults($funder, $filter, $output);
    }

    public function generateFacets(Funder $funder, array $filter, int $output = Partner::RESULT_PROJECT): array
    {
        $countries    = $this->entityManager->getRepository(Partner::class)->fetchCountries($funder, $filter, $output);
        $partnerTypes = $this->entityManager->getRepository(Partner::class)->fetchPartnerTypes($funder, $filter, $output);

        $countriesIndexed = array_map(static function (array $country) {
            return [
                'country' => $country['country'],
                'amount'  => $country[1]
            ];
        }, $countries);

        $partnerTypesIndexed = array_map(static function (array $partnerType) {
            return [
                'partnerTypeCode' => $partnerType['partnerTypeCode'],
                'amount'          => $partnerType[1]
            ];
        }, $partnerTypes);

        return [
            'countries'          => $countriesIndexed,
            'organisation_types' => $partnerTypesIndexed
        ];
    }
}
