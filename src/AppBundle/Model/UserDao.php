<?php

namespace AppBundle\Model;

use AppBundle\Domain\User;
use AppBundle\Service\ShaUtils;
use DB;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserDao extends BaseDBDao
{
    private $logger;

    public function __construct(ContainerInterface $container,
                                LoggerInterface $logger,
                                Registry $registry)
    {
        parent::__construct($container, $logger, $registry);
        $this->logger = $logger;
    }

    public function findByUserName($userName) {
        try {
            $row = DB::queryFirstRow("select * from user where user = %s", $userName);

            if (isset($row)) {
                return new User($row['id'], $row['user'], $row['password'], $row['role']);
            }

            return null;

        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function get($id) {
        try {
            $row = DB::queryFirstRow("select * from user where id = %i", $id);

            if (isset($row)) {
                return new User($row['id'], $row['user'], $row['password'], $row['role']);
            }

            return null;

        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function newUser($userName, $password, $role) {
        try {

            $convertedPassword = ShaUtils::sha512($password);

            DB::insert('user', array(
                'user' => $userName,
                'password' => $convertedPassword,
                'role' => $role
            ));

            return $this->getLastInsertedId();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}