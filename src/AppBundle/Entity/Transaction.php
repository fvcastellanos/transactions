<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transaction
 *
 * @ORM\Table(name="transaction", indexes={@ORM\Index(name="transaction_credit_idx", columns={"credit"}), @ORM\Index(name="transaction_date_idx", columns={"date"}), @ORM\Index(name="transaction_type_transaction_fk", columns={"transaction_type_id"}), @ORM\Index(name="account_transaction_fk", columns={"account_id"})})
 * @ORM\Entity
 */
class Transaction
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
     * @var integer
     *
     * @ORM\Column(name="credit", type="smallint", nullable=false)
     */
    private $credit = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPTION", type="string", length=150, nullable=false)
     */
    private $description;

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
     * @var \Account
     *
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     * })
     */
    private $account;

    /**
     * @var \TransactionType
     *
     * @ORM\ManyToOne(targetEntity="TransactionType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="transaction_type_id", referencedColumnName="id")
     * })
     */
    private $transactionType;


}

