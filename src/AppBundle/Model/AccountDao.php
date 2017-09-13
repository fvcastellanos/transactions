<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/6/2017
 * Time: 11:34 PM
 */

namespace AppBundle\Model;

use AppBundle\Entity\Account;
use AppBundle\Entity\Profile;
use DB;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AccountDao extends BaseDBDao
{

    public function __construct(ContainerInterface $container,
                                LoggerInterface $logger,
                                Registry $registry)
    {
        parent::__construct($container, $logger, $registry);
    }

    public function createAccount($accountNumber, $currency, Profile $profile) : Account {
        $account = new Account();
        $account->setNumber($accountNumber);
        $account->setCurrency($currency);
        $account->setProfile($profile);

        $this->entityManager->persist($account);
        $this->entityManager->flush();

        return $account;
    }

    public function associateAccountToProfile(Account $account, Profile $profile) {
        $account->setProfile($profile);

        $this->entityManager->merge($account);
        $this->entityManager->flush();
    }

    public function findByAccountNumber($accountNumber){
        $account = $this->repository->getRepository(Account::class)
            ->findOneBy(['number' => $accountNumber]);

        return $account;
    }

    public function findAccountsWithProfile() {
        try {
            $row = DB::queryAllLists("select * from account where profile_id is not null");

            if (isset($row)) {
                return new \AppBundle\Domain\Account($row['id'], $row['profile_id'], $row['number'], $row['currency']);
            }

        } catch (\Exception $ex) {
            throw $ex;
        }

//        return $this->repository->getRepository(Account::class)
//        ->findAll();
    }

    public function getAccount($accountNumber) {
        try {
            $row = DB::queryFirstRow("select * from account where number = %s", $accountNumber);

            if (isset($row)) {
                return new \AppBundle\Domain\Account($row['id'], $row['profile_id'], $row['number'], $row['currency']);
            }

            return null;

        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}