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

    public function updateProfileStatus($profileId, $status) {
        try {
            DB::update('profile', array(
                'active' => $status
            ), "id=%i", $profileId);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function findProfileByUserName($userName) {
        try {
            $row = DB::queryFirstRow("select p.* from profile p " .
                " inner join user u on p.user_id = u.id where u.user = %s",
                $userName);

            if (isset($row)) {
                return new \AppBundle\Domain\Profile($row['id'], $row['user_id'], $row['name'],
                    $row['email'], $row['phone'], $row['active']);
            }

            return null;

        } catch (\Exception $ex) {
            throw $ex;
        }
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

    public function newProfile($name, $email, $phone, $active, $userId) {
        try {
            DB::insert('profile', array(
                'user_id' => $userId,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'active' => $active
            ));

            return $this->getLastInsertedId();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}