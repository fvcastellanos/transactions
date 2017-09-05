<?php
/**
 * Created by PhpStorm.
 * User: fvcg
 * Date: 9/3/2017
 * Time: 4:01 PM
 */

namespace AppBundle\Model;

use Doctrine\Bundle\DoctrineBundle\Registry;
use AppBundle\Entity\Profile;
use AppBundle\Entity\User;

class ProfileDao extends BaseDao
{

   /**
     * ProfileDao constructor.
     */
    public function __construct(Registry $registry)
    {
        parent::__construct($registry);
    }

    public function createUserProfile($name, $phone, $email, User $user) {
        $profile = new Profile();

        $profile->setName($name);
        $profile->setPhone($phone);
        $profile->setEmail($email);
        $profile->setActive(0);
        $profile->setUser($user);

        $this->entityManager->persist($profile);
        $this->entityManager->flush();

        return $profile;
    }
}