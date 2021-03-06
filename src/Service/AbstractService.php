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

use Cluster\Repository\FilteredObjectRepository;
use Cluster\Entity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

use function class_implements;
use function in_array;

/**
 * Class AbstractService
 * @package Cluster\Service
 */
abstract class AbstractService
{
    protected EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findFiltered(string $entity, array $filter): ?QueryBuilder
    {
        /** @var FilteredObjectRepository $repository */
        $repository = $this->entityManager->getRepository($entity);
        if (in_array(FilteredObjectRepository::class, class_implements($repository), true)) {
            return $repository->findFiltered($filter);
        }
        return null;
    }

    public function findAll(string $entity): array
    {
        return $this->entityManager->getRepository($entity)->findAll();
    }

    public function find(string $entity, int $id): ?Entity\AbstractEntity
    {
        return $this->entityManager->getRepository($entity)->find($id);
    }

    public function findByName(string $entity, string $column, string $name): ?Entity\AbstractEntity
    {
        return $this->entityManager->getRepository($entity)->findOneBy([$column => $name]);
    }

    public function count(string $entity, array $criteria = []): int
    {
        return $this->entityManager->getRepository($entity)->count($criteria);
    }

    public function save(Entity\AbstractEntity $entity): Entity\AbstractEntity
    {
        if (! $this->entityManager->contains($entity)) {
            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush();

        return $entity;
    }

    public function delete(Entity\AbstractEntity $entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    public function refresh(Entity\AbstractEntity $entity): void
    {
        $this->entityManager->refresh($entity);
    }
}
