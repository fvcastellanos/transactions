<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/5/2017
 * Time: 11:27 PM
 */

namespace AppBundle\Domain;


class Result
{
    private $object;
    private $errors;

    /**
     * Result constructor.
     * @param $object
     * @param $errors
     */
    private function __construct($object, $errors)
    {
        $this->object = $object;
        $this->errors = $errors;
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return array($this->errors);
    }

    public function hasErrors() {
        return isset($errors);
    }

    public function isSuccess() {
        return !$this->hasErrors();
    }

    public static function forSuccess($object) {
        return new Result($object, null);
    }

    public static function forErrors($errors) {
        return new Result(null, $errors);
    }

}