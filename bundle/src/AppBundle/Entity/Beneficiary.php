<?php

namespace AppBundle\Entity;

use DateTime;
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
     * @var DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var Account
     *
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     * })
     */
    private $account;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     */
    public function setAlias(string $alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return float
     */
    public function getMaxAmountTransfer()
    {
        return $this->maxAmountTransfer;
    }

    /**
     * @param float $maxAmountTransfer
     */
    public function setMaxAmountTransfer(float $maxAmountTransfer)
    {
        $this->maxAmountTransfer = $maxAmountTransfer;
    }

    /**
     * @return int
     */
    public function getTransactionsQuota()
    {
        return $this->transactionsQuota;
    }

    /**
     * @param int $transactionsQuota
     */
    public function setTransactionsQuota(int $transactionsQuota)
    {
        $this->transactionsQuota = $transactionsQuota;
    }

    /**
     * @return DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param DateTime $created
     */
    public function setCreated(DateTime $created)
    {
        $this->created = $created;
    }

    /**
     * @return Account
     */
    public function getAccount() : Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;
    }


}

