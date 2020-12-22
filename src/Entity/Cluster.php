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

namespace Cluster\Entity;

use DateTime;
use Doctrine\Common\Collections;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Laminas\Form\Annotation;
use Program\Entity\Program;

/**
 * @ORM\Table(name="cluster_cluster")
 * @ORM\Entity(repositoryClass="Cluster\Repository\ClusterRepository")
 */
class Cluster extends AbstractEntity
{
    /**
     * @ORM\Column(name="cluster_id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Type("\Laminas\Form\Element\Hidden")
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="name", type="string", nullable=true)
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Attributes({"placeholder":"txt-cluster-name-placeholder"})
     * @Annotation\Options({"label":"txt-cluster-name-label","help-block": "txt-cluster-name-help-block"})
     *
     * @var string
     */
    private $name;
    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Annotation\Type("\Laminas\Form\Element\Textarea")
     * @Annotation\Attributes({"rows":15,"placeholder":"txt-cluster-description-placeholder"})
     * @Annotation\Options({"label":"txt-cluster-description-label","help-block": "txt-cluster-description-help-block"})
     *
     * @var string
     */
    private $description;
    /**
     * @ORM\Column(name="date_created", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="create")
     * @Annotation\Exclude()
     *
     * @var DateTime
     */
    private $dateCreated;
    /**
     * @ORM\Column(name="date_updated", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     * @Annotation\Exclude()
     *
     * @var DateTime
     */
    private $dateUpdated;
    /**
     * @ORM\ManyToMany(targetEntity="Program\Entity\Program", cascade="persist", mappedBy="cluster")
     *
     * @var Program[]|Collections\ArrayCollection
     */
    private $program;
    /**
     * @ORM\ManyToMany(targetEntity="Program\Entity\Call\Call", cascade="persist", mappedBy="cluster")
     *
     * @var \Program\Entity\Call\Call[]|Collections\ArrayCollection
     */
    private $call;
    /**
     * @ORM\OneToMany(targetEntity="\Project\Entity\Project\Cluster", cascade="persist", mappedBy="cluster")
     *
     * @var \Project\Entity\Project\Cluster[]|Collections\ArrayCollection
     */
    private $projectCluster;

    public function __construct()
    {
        $this->program        = new Collections\ArrayCollection();
        $this->call           = new Collections\ArrayCollection();
        $this->projectCluster = new Collections\ArrayCollection();
    }

    public function __toString(): string
    {
        return (string)$this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Cluster
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Cluster
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Cluster
    {
        $this->description = $description;
        return $this;
    }

    public function getDateCreated(): ?DateTime
    {
        return $this->dateCreated;
    }

    public function setDateCreated(?DateTime $dateCreated): Cluster
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    public function getDateUpdated(): ?DateTime
    {
        return $this->dateUpdated;
    }

    public function setDateUpdated(?DateTime $dateUpdated): Cluster
    {
        $this->dateUpdated = $dateUpdated;
        return $this;
    }

    public function getProgram()
    {
        return $this->program;
    }

    public function setProgram($program): Cluster
    {
        $this->program = $program;
        return $this;
    }

    public function getCall()
    {
        return $this->call;
    }

    public function setCall($call): Cluster
    {
        $this->call = $call;
        return $this;
    }

    public function getProjectCluster()
    {
        return $this->projectCluster;
    }

    public function setProjectCluster($projectCluster): Cluster
    {
        $this->projectCluster = $projectCluster;
        return $this;
    }
}
