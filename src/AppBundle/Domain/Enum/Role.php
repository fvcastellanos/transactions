<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/15/2017
 * Time: 7:26 PM
 */

namespace AppBundle\Domain\Enum;


class Role
{
    public static function admin() {
        return "ADMIN";
    }

    public static function user() {
        return "USER";
    }
}