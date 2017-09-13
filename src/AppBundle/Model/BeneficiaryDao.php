<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/11/2017
 * Time: 10:27 PM
 */

namespace AppBundle\Model;


use AppBundle\Entity\Account;
use AppBundle\Entity\Beneficiary;
use AppBundle\Entity\Profile;
use DB;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BeneficiaryDao extends BaseDBDao
{

    public function __construct(ContainerInterface $container, LoggerInterface $logger, Registry $registry)
    {
        parent::__construct($container, $logger, $registry);
    }

    public function getBeneficiariesFor($profileId) {

        $profile = $this->repository->getRepository(Profile::class)
            ->find($profileId);

        $account = $this->repository->getRepository(Account::class)
            ->findOneBy(['profile' => $profile]);

        return $this->repository->getRepository(Beneficiary::class)
            ->findBy(['account' => $account]);
    }

    public function createBeneficiary($accountId, $alias, $maxAmount, $quota) {
        try {
            DB::insert('beneficiary', array(
                'account_id' => $accountId,
                'alias' => $alias,
                'max_amount_transfer' => $maxAmount,
                'transactions_quota' => $quota
            ));

            return $this->getLastInsertedId();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}