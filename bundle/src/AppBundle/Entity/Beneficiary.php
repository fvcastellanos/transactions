<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Beneficiary
 *
 * @ORM\Table(name="beneficiary", indexes={@ORM\Index(name="beneficiary_alias_idx", columns={"alias"}), @ORM\Index(name="account_beneficiary_fk", columns={"account_id"})})
 * @ORM\Entity
 */
class Beneficiary
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
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=50, nullable=false)
     */
    private $alias;

    /**
     * @var float
     *
     * @ORM\Column(name="max_amount_transfer", type="float", precision=10, scale=0, nullable=false)
     */
    private $maxAmountTransfer;

    /**
     * @var integer
     *
     * @ORM\Column(name="transactions_quota", type="smallint", nullable=false)
     */
    private $transactionsQuota;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created = 'CURRENT_TIMESTAMP';

    /**
     * @var \Account
     *
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     * })
     */
    private $account;


}

