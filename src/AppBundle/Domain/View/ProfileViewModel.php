<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/5/2017
 * Time: 11:59 PM
 */

namespace AppBundle\Domain\View;


class ProfileViewModel
{
    public $name;
    public $phone;
    public $email;
    public $active;

    /**
     * ProfileViewModel constructor.
     * @param $name
     * @param $phone
     * @param $email
     * @param $active
     */
    public function __construct($name, $phone, $email, $active)
    {
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
        $this->active = $active;
    }
}