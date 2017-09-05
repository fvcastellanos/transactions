<?php

namespace AppBundle\Model;

use AppBundle\Service\ShaUtils;
use Doctrine\Bundle\DoctrineBundle\Registry;
use AppBundle\Entity\User;
use AppBundle\Entity\Role;

class UserDao extends BaseDao
{
    /**
     * UserDao constructor.
     */
    public function __construct(Registry $registry) {
        parent::__construct($registry);
    }

    public function findByUserName($userName) {
        return $this->repository
            ->getRepository(User::class)
            ->findOneBy(['user' => $userName]);
    }

    public function createUser($userName, $password, Role $role) {

        $newPassword = ShaUtils::sha512($password);

        $user = new User();

        $user->setUser($userName);
        $user->setPassword($newPassword);
        $user->setRole($role);

        $role->setUser($user);

        $em = $this->repository->getManager();

        $em->persist($user);
        $em->persist($role);
        $em->flush();

        return $user;
    }

}