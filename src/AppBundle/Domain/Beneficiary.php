<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/16/2017
 * Time: 4:28 PM
 */

namespace AppBundle\Domain;


class Beneficiary
{
    public $id;
    public $alias;
    public $accountId;
    public $account;
    public $profileId;
    public $name;
    public $maxAmountTransfer;
    public $transactionsQuota;

}