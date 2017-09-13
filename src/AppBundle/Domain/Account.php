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
    public $number;
    public $currency;

    /**
     * Account constructor.
     * @param $id
     * @param $profileId
     * @param $number
     * @param $currency
     */
    public function __construct($id, $profileId, $number, $currency)
    {
        $this->id = $id;
        $this->profileId = $profileId;
        $this->number = $number;
        $this->currency = $currency;
    }


}