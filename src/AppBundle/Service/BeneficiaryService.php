<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/11/2017
 * Time: 10:22 PM
 */

namespace AppBundle\Service;


use AppBundle\Domain\View\BeneficiaryViewModel;
use AppBundle\Model\AccountDao;
use AppBundle\Model\BeneficiaryDao;
use AppBundle\Model\ProfileDao;
use Psr\Log\LoggerInterface;

class BeneficiaryService extends BaseService
{
    private $logger;
    private $beneficiaryDao;
    private $accountDao;
    private $profileDao;

    public function __construct(LoggerInterface $logger,
                                BeneficiaryDao $beneficiaryDao,
                                AccountDao $accountDao,
                                ProfileDao $profileDao) {
        $this->logger = $logger;
        $this->beneficiaryDao = $beneficiaryDao;
        $this->accountDao = $accountDao;
        $this->profileDao = $profileDao;
    }

    public function getBeneficiariesFor($profileId) {
        try {
            $beneficiaries = $this->beneficiaryDao->getBeneficiariesFor($profileId);

            return $this->returnValue($beneficiaries);
        } catch (\Exception $ex) {
            $this->logger->error("can't get beneficiaries: ", [$ex]);
            return $this->returnError($ex->getMessage());
        }
    }

    public function addBeneficiary(BeneficiaryViewModel $model, $profileId) {
        try {
            $account = $this->accountDao->getAccount($model->account);

            if (!isset($account)) {
                return $this->returnError("account not found: " . $model->account);
            }

            if ($account->profileId == $profileId) {
                return $this->returnError("cannot add your own account as beneficiary");
            }

            $profile = $this->profileDao->getProfile($account->profileId);

            if ($profile->active == 0) {
                return $this->returnError("selected profile is not active");
            }

            $this->beneficiaryDao->createBeneficiary($account->id, $model->alias, $model->maxAmount, $model->transferQuota);

            $result = array('account' => $account, 'profile' => $profile);

            return $this->returnValue($result);

        } catch (\Exception $ex) {
            $this->logger->error("can't add beneficiary: ", [$ex]);
            return $this->returnError($ex->getMessage());
        }
    }
}