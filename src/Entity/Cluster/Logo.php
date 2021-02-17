<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace Cluster\Entity\Cluster;

use Cluster\Entity\AbstractEntity;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Laminas\Form\Annotation;

/**
 * @ORM\Table(name="cluster_cluster_logo")
 * @ORM\Entity
 * @Annotation\Hydrator("Laminas\Hydrator\ObjectPropertyHydrator")
 * @Annotation\Name("cluster_cluster_logo")
 */
class Logo extends AbstractEntity
{
    /**
     * @ORM\Column(name="logo_id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private ?int $id = null;
    /**
     * @ORM\Column(name="cluster_logo", type="blob", nullable=false)
     * @var string|resource
     */
    private $clusterLogo;
    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\ContentType", cascade={"persist"}, inversedBy="clusterLogo")
     * @ORM\JoinColumn(name="contenttype_id", referencedColumnName="contenttype_id", nullable=false)
     * @Annotation\Type("\Laminas\Form\Element\File")
     * @Annotation\Options({"label":"txt-cluster-logo-file-label","help-block":"txt-cluster-logo-file-help-block"})
     */
    private \General\Entity\ContentType $contentType;
    /**
     * @ORM\Column(name="date_updated", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="update")
     */
    private ?DateTime $dateUpdated = null;
    /**
     * @ORM\OneToOne(targetEntity="Cluster\Entity\Cluster", inversedBy="logo", cascade={"persist"})
     * @ORM\JoinColumn(name="cluster_id", referencedColumnName="cluster_id")
     */
    private \Cluster\Entity\Cluster $cluster;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): Logo
    {
        $this->id = $id;
        return $this;
    }

    public function getClusterLogo()
    {
        return $this->clusterLogo;
    }

    public function setClusterLogo(string $clusterLogo): Logo
    {
        $this->clusterLogo = $clusterLogo;
        return $this;
    }

    public function getContentType(): \General\Entity\ContentType
    {
        return $this->contentType;
    }

    public function setContentType(\General\Entity\ContentType $contentType): Logo
    {
        $this->contentType = $contentType;
        return $this;
    }

    public function getDateUpdated(): ?DateTime
    {
        return $this->dateUpdated;
    }

    public function setDateUpdated(?DateTime $dateUpdated): Logo
    {
        $this->dateUpdated = $dateUpdated;
        return $this;
    }

    public function getCluster(): \Cluster\Entity\Cluster
    {
        return $this->cluster;
    }

    public function setCluster(\Cluster\Entity\Cluster $cluster): Logo
    {
        $this->cluster = $cluster;
        return $this;
    }
}
