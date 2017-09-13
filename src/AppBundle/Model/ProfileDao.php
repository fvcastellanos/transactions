<?php
/**
 * Created by PhpStorm.
 * User: fvcg
 * Date: 9/3/2017
 * Time: 4:01 PM
 */

namespace AppBundle\Model;

use DB;
use Doctrine\Bundle\DoctrineBundle\Registry;
use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProfileDao extends BaseDBDao
{

    public function __construct(ContainerInterface $container,
                                LoggerInterface $logger,
                                Registry $registry)
    {
        parent::__construct($container, $logger, $registry);
    }

    public function createUserProfile($name, $phone, $email, User $user) : Profile {
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

    public function updateProfileStatus(Profile $profile, $status) {
        $profile->setActive($status);

        $this->entityManager->merge($profile);
        $this->entityManager->flush();

        return $profile;
    }

    public function findProfileByUserName($userName) {
        $user = $this->repository
            ->getRepository(User::class)
            ->findOneBy(["user" => $userName]);

        $profile = $this->repository
            ->getRepository(Profile::class)
            ->findOneBy(["user" => $user]);

        return $profile;
    }

    public function getProfile($profileId) {
        try {
            $row = DB::queryFirstRow("select * from profile where id = %i", $profileId);

            if (isset($row)) {
                return new \AppBundle\Domain\Profile($row['id'], $row['user_id'], $row['name'],
                    $row['email'], $row['phone'], $row['active']);
            }

            return null;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}