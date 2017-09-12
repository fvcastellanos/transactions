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
use Doctrine\Bundle\DoctrineBundle\Registry;

class BeneficiaryDao extends BaseDao
{

    public function __construct(Registry $registry)
    {
        parent::__construct($registry);
    }

    public function getBeneficiariesFor($profileId) {

        $profile = $this->repository->getRepository(Profile::class)
            ->find($profileId);

        $account = $this->repository->getRepository(Account::class)
            ->findOneBy(['profile' => $profile]);

        return $this->repository->getRepository(Beneficiary::class)
            ->findBy(['account' => $account]);
    }
}