<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/12/2017
 * Time: 11:50 PM
 */

namespace AppBundle\Domain;


class Profile
{
    public $id;
    public $userId;
    public $name;
    public $email;
    public $phone;
    public $active;

    /**
     * Profile constructor.
     * @param $id
     * @param $userId
     * @param $name
     * @param $email
     * @param $phone
     * @param $active
     */
    public function __construct($id, $userId, $name, $email, $phone, $active)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->active = $active;
    }


}