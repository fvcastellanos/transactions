<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/12/2017
 * Time: 11:39 PM
 */

namespace AppBundle\Domain;


class Account
{
    public $id;
    public $profileId;
    public $name;
    public $number;
    public $currency;

    /**
     * Account constructor.
     * @param $id
     * @param $profileId
     * @param $name
     * @param $number
     * @param $currency
     */
    public function __construct($id, $profileId, $name, $number, $currency)
    {
        $this->id = $id;
        $this->profileId = $profileId;
        $this->name = $name;
        $this->number = $number;
        $this->currency = $currency;
    }
}