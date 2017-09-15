<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/15/2017
 * Time: 1:22 PM
 */

namespace AppBundle\Domain;


class TransactionType
{
    public $id;
    public $name;
    public $description;

    /**
     * TransactionType constructor.
     * @param $id
     * @param $name
     * @param $description
     */
    public function __construct($id, $name, $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }
}