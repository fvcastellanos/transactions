<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/12/2017
 * Time: 10:29 PM
 */

namespace AppBundle\Model;

use DB;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BaseDBDao extends BaseDao
{
    private $container;
    private $logger;

    public function __construct(ContainerInterface $container,
                                LoggerInterface $logger,
                                Registry $registry)
    {
        parent::__construct($registry);
        $this->container = $container;
        $this->logger = $logger;

        DB::$user = $this->getDbUser();
        DB::$password = $this->getDbPassword();
        DB::$host = $this->getDbHost();
        DB::$port = $this->getDbPort();
        DB::$dbName = $this->getDbName();
    }

    protected function getDbHost() {
        return $this->container->getParameter('database_host');
    }

    protected function getDbPort() {
        return $this->container->getParameter('database_port');
    }

    protected function getDbUser() {
        return $this->container->getParameter('database_user');
    }

    protected function getDbPassword() {
        return $this->container->getParameter('database_password');
    }

    protected function getDbName() {
        return $this->container->getParameter('database_name');
    }

    protected function getLastInsertedId() {
        try {
            return DB::queryFirstField("select LAST_INSERT_ID()");
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}