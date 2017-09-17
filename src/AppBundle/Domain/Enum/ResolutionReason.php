<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/15/2017
 * Time: 5:36 PM
 */

namespace AppBundle\Domain\Enum;


class ResolutionReason
{
    public static function approved() {
        return "Approved";
    }

    public static function denied() {
        return "Denied";
    }
}