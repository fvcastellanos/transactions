<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/16/2017
 * Time: 9:25 PM
 */

namespace AppBundle\Domain;


class Balance
{
    public $accountId;
    public $debits;
    public $credits;
    public $balance;

    /**
     * Balance constructor.
     * @param $accountId
     * @param $debits
     * @param $credits
     * @param $balance
     */
    public function __construct($accountId, $debits, $credits, $balance)
    {
        $this->accountId = $accountId;
        $this->debits = $debits;
        $this->credits = $credits;
        $this->balance = $balance;
    }
}