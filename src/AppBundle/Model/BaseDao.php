<?php

namespace AppBundle\Model;

use Doctrine\Bundle\DoctrineBundle\Registry;

abstract class BaseDao
{
    protected $repository;
    protected $entityManager;

    /**
     * BaseDao constructor.
     */
    public function __construct(Registry $registry)
    {
        $this->repository = $registry;
        $this->entityManager = $registry->getManager();
    }


}