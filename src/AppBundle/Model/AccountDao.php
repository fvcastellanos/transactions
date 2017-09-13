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
use Doctrine\Bundle\DoctrineBundle\Registry;

class AccountDao extends BaseDao
{
    public function __construct(Registry $registry)
    {
        parent::__construct($registry);
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
        return $this->repository->getRepository(Account::class)
//            ->findBy(['profile' => 'not null']);
        ->findAll();
    }

}