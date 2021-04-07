<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace Cluster\Repository\Statistics;

use Cluster\Entity;
use Doctrine\ORM\EntityRepository;
use Program\Entity\Funder;

class PartnerRepository extends EntityRepository
{
    public function deletePartnersByInternalIdentifier(string $internalIdentifier): void
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->delete(Entity\Statistics\Partner::class, 'cluster_entity_statistics_partner');
        $queryBuilder->where('cluster_entity_statistics_partner.identifier = :internalIdentifier');
        $queryBuilder->setParameter('internalIdentifier', $internalIdentifier);

        $queryBuilder->getQuery()->execute();
    }

    public function getResults(Funder $funder, array $filter, int $result): array
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('cluster_entity_statistics_partner');
        $queryBuilder->from(Entity\Statistics\Partner::class, 'cluster_entity_statistics_partner');

        if ($result === Entity\Statistics\Partner::RESULT_PROJECT) {
            $queryBuilder->addGroupBy('cluster_entity_statistics_partner.identifier');
        }

        //Filters filters filters
        $countryFilter = $filter['country'] ?? [];
        if (! empty($countryFilter)) {
            switch ($filter['country_method']) {
                case 'and':
                    //Find the projects where the country is active
                    $countryFilterSubSelect = $this->_em->createQueryBuilder();
                    $countryFilterSubSelect->select('cluster_entity_statistics_partner_country_filter.identifier');
                    $countryFilterSubSelect->from(Entity\Statistics\Partner::class, 'cluster_entity_statistics_partner_country_filter');
                    $countryFilterSubSelect->where($queryBuilder->expr()->in('cluster_entity_statistics_partner_country_filter.country', $countryFilter));
                    $countryFilterSubSelect->addGroupBy('cluster_entity_statistics_partner_country_filter.identifier');
                    $countryFilterSubSelect->having('COUNT(DISTINCT cluster_entity_statistics_partner_country_filter.country) > ' . (count($countryFilter) - 1));

                    $queryBuilder->andWhere($queryBuilder->expr()->in('cluster_entity_statistics_partner.identifier', $countryFilterSubSelect->getDQL()));

                    break;
                case 'or':
                    $queryBuilder->andWhere($queryBuilder->expr()->in('cluster_entity_statistics_partner.country', $countryFilter));
                    break;
            }
        }


        //Results are always limited to projects where the country of the funder is active in

        //Find the projects where the country is active
        $funderSubSelect = $this->_em->createQueryBuilder();
        $funderSubSelect->select('cluster_entity_statistics_partner_country.identifier');
        $funderSubSelect->from(Entity\Statistics\Partner::class, 'cluster_entity_statistics_partner_country');
        $funderSubSelect->andWhere('cluster_entity_statistics_partner_country.country = :funder_country');

        $queryBuilder->andWhere($queryBuilder->expr()->in('cluster_entity_statistics_partner.identifier', $funderSubSelect->getDQL()));
        $queryBuilder->setParameter('funder_country', $funder->getCountry()->getCd());

        return $queryBuilder->getQuery()->getArrayResult();
    }

    public function fetchCountries(Funder $funder, $filter, int $result): array
    {
        $queryBuilder = $this->_em->createQueryBuilder();

        if ($result === Entity\Statistics\Partner::RESULT_PROJECT) {
            $queryBuilder->select(
                'cluster_entity_statistics_partner.country',
                $queryBuilder->expr()->countDistinct('cluster_entity_statistics_partner.identifier')
            );
        }

        if ($result === Entity\Statistics\Partner::RESULT_PARTNER) {
            $queryBuilder->select(
                'cluster_entity_statistics_partner.country',
                $queryBuilder->expr()->countDistinct('cluster_entity_statistics_partner.id')
            );
        }


        $queryBuilder->from(Entity\Statistics\Partner::class, 'cluster_entity_statistics_partner');
        $queryBuilder->groupBy('cluster_entity_statistics_partner.country');

        //Find the projects where the country is active
        $funderSubSelect = $this->_em->createQueryBuilder();
        $funderSubSelect->select('cluster_entity_statistics_partner_country.identifier');
        $funderSubSelect->from(Entity\Statistics\Partner::class, 'cluster_entity_statistics_partner_country');
        $funderSubSelect->andWhere('cluster_entity_statistics_partner_country.country = :funder_country');

        $queryBuilder->andWhere($queryBuilder->expr()->in('cluster_entity_statistics_partner.identifier', $funderSubSelect->getDQL()));
        $queryBuilder->setParameter('funder_country', $funder->getCountry()->getCd());

        return $queryBuilder->getQuery()->getArrayResult();
    }

    public function fetchPartnerTypes(Funder $funder, $filter, int $result): array
    {
        $queryBuilder = $this->_em->createQueryBuilder();

        if ($result === Entity\Statistics\Partner::RESULT_PROJECT) {
            $queryBuilder->select(
                'cluster_entity_statistics_partner.partnerTypeCode',
                $queryBuilder->expr()->countDistinct('cluster_entity_statistics_partner.identifier')
            );
        }

        if ($result === Entity\Statistics\Partner::RESULT_PARTNER) {
            $queryBuilder->select(
                'cluster_entity_statistics_partner.partnerTypeCode',
                $queryBuilder->expr()->countDistinct('cluster_entity_statistics_partner.id')
            );
        }

        $queryBuilder->from(Entity\Statistics\Partner::class, 'cluster_entity_statistics_partner');
        $queryBuilder->groupBy('cluster_entity_statistics_partner.partnerTypeCode');

        //Find the projects where the country is active
        $funderSubSelect = $this->_em->createQueryBuilder();
        $funderSubSelect->select('cluster_entity_statistics_partner_country.identifier');
        $funderSubSelect->from(Entity\Statistics\Partner::class, 'cluster_entity_statistics_partner_country');
        $funderSubSelect->andWhere('cluster_entity_statistics_partner_country.country = :funder_country');

        $queryBuilder->andWhere($queryBuilder->expr()->in('cluster_entity_statistics_partner.identifier', $funderSubSelect->getDQL()));
        $queryBuilder->setParameter('funder_country', $funder->getCountry()->getCd());

        return $queryBuilder->getQuery()->getArrayResult();
    }
}
