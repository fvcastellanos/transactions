<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DepositRequirement
 *
 * @ORM\Table(name="deposit_requirement", indexes={@ORM\Index(name="deposit_requirement_resolution_date_idx", columns={"resolution_date"}), @ORM\Index(name="deposit_requirement_status_idx", columns={"status"}), @ORM\Index(name="deposit_requirement_resolution_idx", columns={"requested_date", "status", "resolution_reason"}), @ORM\Index(name="profile_deposit_requirement_fk", columns={"profile_id"})})
 * @ORM\Entity
 */
class DepositRequirement
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="requested_date", type="datetime", nullable=false)
     */
    private $requestedDate = 'CURRENT_TIMESTAMP';

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float", precision=10, scale=0, nullable=false)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=3, nullable=false)
     */
    private $currency = 'GTQ';

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=1, nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="resolution_reason", type="string", length=150, nullable=false)
     */
    private $resolutionReason;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="resolution_date", type="datetime", nullable=false)
     */
    private $resolutionDate;

    /**
     * @var \Profile
     *
     * @ORM\ManyToOne(targetEntity="Profile")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     * })
     */
    private $profile;


}

