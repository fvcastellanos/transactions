<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/16/2017
 * Time: 8:38 AM
 */

namespace AppBundle\Domain;


class AccountProfile
{
    public $name;
    public $user;
    public $account;
    public $currency;
    public $active;

    /**
     * AccountProfile constructor.
     * @param $name
     * @param $user
     * @param $account
     * @param $currency
     * @param $active
     */
    public function __construct($name, $user, $account, $currency, $active)
    {
        $this->name = $name;
        $this->user = $user;
        $this->account = $account;
        $this->currency = $currency;
        $this->active = $active;
    }


}