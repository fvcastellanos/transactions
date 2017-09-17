<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/17/2017
 * Time: 9:13 AM
 */

namespace AppBundle\Domain;


class TransferCount
{
    public $transfers;
    public $month;
    public $year;
    public $accountId;
    public $targetAccountId;

    /**
     * TransferCount constructor.
     * @param $transfers
     * @param $month
     * @param $year
     * @param $accountId
     * @param $targetAccountId
     */
    public function __construct($transfers, $month, $year, $accountId, $targetAccountId)
    {
        $this->transfers = $transfers;
        $this->month = $month;
        $this->year = $year;
        $this->accountId = $accountId;
        $this->targetAccountId = $targetAccountId;
    }


}