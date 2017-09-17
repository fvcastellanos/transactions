<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/17/2017
 * Time: 12:22 PM
 */

namespace AppBundle\Domain;


class TransactionDetails
{
    public $id;
    public $accountId;
    public $account;
    public $otherAccountId;
    public $otherAccount;
    public $otherName;
    public $type;
    public $credit;
    public $date;
    public $description;
    public $amount;
    public $currency;
}