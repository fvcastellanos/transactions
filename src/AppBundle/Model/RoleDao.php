<?php
/**
 * Created by PhpStorm.
 * User: fcastellanos
 * Date: 9/4/17
 * Time: 18:16
 */

namespace AppBundle\Model;

use Doctrine\Bundle\DoctrineBundle\Registry;
use AppBundle\Entity\Role;

class RoleDao extends BaseDao
{
    /**
     * RoleDao constructor.
     */
    public function __construct(Registry $registry) {
        parent::__construct($registry);
    }

    public function getUserRole() {
        return $this->repository
            ->getRepository(Role::class)
            ->findOneBy(['name' => 'USER']);
    }

}