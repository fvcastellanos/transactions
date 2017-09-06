<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SchemaVersion
 *
 * @ORM\Table(name="schema_version", indexes={@ORM\Index(name="schema_version_s_idx", columns={"success"})})
 * @ORM\Entity
 */
class SchemaVersion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="installed_rank", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $installedRank;

    /**
     * @var string
     *
     * @ORM\Column(name="version", type="string", length=50, nullable=true)
     */
    private $version;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=20, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="script", type="string", length=1000, nullable=false)
     */
    private $script;

    /**
     * @var integer
     *
     * @ORM\Column(name="checksum", type="integer", nullable=true)
     */
    private $checksum;

    /**
     * @var string
     *
     * @ORM\Column(name="installed_by", type="string", length=100, nullable=false)
     */
    private $installedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="installed_on", type="datetime", nullable=false)
     */
    private $installedOn = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="execution_time", type="integer", nullable=false)
     */
    private $executionTime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="success", type="boolean", nullable=false)
     */
    private $success;


}

