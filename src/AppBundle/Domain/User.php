<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/15/2017
 * Time: 7:28 PM
 */

namespace AppBundle\Domain;


class User
{
    public $id;
    public $user;
    public $password;
    public $role;

    /**
     * User constructor.
     * @param $id
     * @param $user
     * @param $password
     * @param $role
     */
    public function __construct($id, $user, $password, $role)
    {
        $this->id = $id;
        $this->user = $user;
        $this->password = $password;
        $this->role = $role;
    }
}