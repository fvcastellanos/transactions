<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/16/2017
 * Time: 12:32 PM
 */

namespace AppBundle\Domain;


class DepositRequirement
{
    public $id;
    public $accountId;
    public $account;
    public $requestedDate;
    public $amount;
    public $currency;
    public $status;
    public $resolution;
    public $resolutionDate;
    public $name;
}