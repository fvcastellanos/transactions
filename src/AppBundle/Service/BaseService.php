<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/5/2017
 * Time: 11:33 PM
 */

namespace AppBundle\Service;


use AppBundle\Domain\Result;

abstract class BaseService
{
    protected function returnError($error) : Result {
        return Result::forErrors($error);
    }

    protected function returnValue($value) : Result {
        return Result::forSuccess($value);
    }
}