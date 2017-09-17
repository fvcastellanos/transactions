<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/16/2017
 * Time: 8:42 PM
 */

namespace AppBundle\Service;


use AppBundle\Domain\Enum\TransactionTypeEnum;
use AppBundle\Model\AccountDao;
use AppBundle\Model\BeneficiaryDao;
use AppBundle\Model\TransactionDao;
use Psr\Log\LoggerInterface;

class TransferService extends BaseService
{
    private $logger;
    private $accountDao;
    private $beneficiaryDao;
    private $transactionDao;

    public function __construct(LoggerInterface $logger,
                                AccountDao $accountDao,
                                BeneficiaryDao $beneficiaryDao,
                                TransactionDao $transactionDao)
    {
        $this->logger = $logger;
        $this->accountDao = $accountDao;
        $this->beneficiaryDao = $beneficiaryDao;
        $this->transactionDao = $transactionDao;
    }

    public function transfer($profileId, $beneficiaryId, $amount) {
        try {
            $this->transactionDao->beginTransaction();

            $beneficiary = $this->beneficiaryDao->getBeneficiary($beneficiaryId);
            $account = $this->accountDao->getAccountByProfile($profileId);

            if (!isset($beneficiary)) {
                $this->transactionDao->rollback();
                return $this->returnError("beneficiary not found");
            }

            $type = TransactionTypeEnum::transferWithdraw();
            $transferCount = $this->transactionDao->getTransfersCount($account->id, $beneficiary->accountId, $type->value);

            if ($transferCount->transfers > $beneficiary->transactionsQuota) {
                $this->transactionDao->rollback();
                return $this->returnError("monthly transfer quota exceeded");
            }

            if ($amount > $beneficiary->maxAmountTransfer) {
                $this->transactionDao->rollback();
                return $this->returnError("maximum amount per transfer exceeded");
            }

            $balance = $this->transactionDao->getBalance($account->id);

            if ($amount > $balance->balance) {
                $this->transactionDao->rollback();
                return $this->returnError("not enough funds to complete the operation");
            }

            $this->transactionDao->createDebitTransaction(TransactionTypeEnum::transferWithdraw(), $account->id,
                "Transfer withdraw", $account->currency, $amount, $beneficiary->accountId);

            $this->transactionDao->createCreditTransaction(TransactionTypeEnum::transferDeposit(), $beneficiary->accountId,
                "Transfer deposit", $account->currency, $amount, $account->id);

            $this->transactionDao->commit();

            return $this->returnValue($amount);
        } catch (\Exception $ex) {
            $this->beneficiaryDao->rollback();
            return $this->returnError($ex->getMessage());
        }
    }
}