<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/15/2017
 * Time: 2:03 PM
 */

namespace AppBundle\Service;


use AppBundle\Domain\Enum\TransactionDescription;
use AppBundle\Domain\Result;
use AppBundle\Domain\Enum\TransactionTypeEnum;
use AppBundle\Model\AccountDao;
use AppBundle\Model\TransactionDao;
use Psr\Log\LoggerInterface;

class AccountService extends BaseService
{

    private $logger;
    private $accountDao;
    private $transactionDao;
    /**
     * AccountService constructor.
     */
    public function __construct(LoggerInterface $logger,
                                AccountDao $accountDao,
                                TransactionDao $transactionDao)
    {
        $this->logger = $logger;
        $this->accountDao = $accountDao;
        $this->transactionDao = $transactionDao;
    }

    public function getAccounts() : Result {
        try {
            $this->logger->info("getting accounts");
            $accounts = $this->accountDao->getAccounts();

            return $this->returnValue($accounts);

        } catch (\Exception $ex) {
            $this->logger->error("can't get accounts: ", [ $ex ]);
            $this->returnError($ex->getMessage());
        }
    }

    public function createAccount($number, $balance) : Result {
        try {
            $account = $this->accountDao->findByAccountNumber($number);

            if (isset($account)) {
                return $this->returnError("account already exists");
            }

            $this->accountDao->newAccount($number, null, "GTQ");
            $account = $this->accountDao->getAccount($number);
            $this->transactionDao->createCreditTransaction(TransactionTypeEnum::initialDeposit(),
                $account->id, TransactionDescription::initialDeposit(), "GTQ", $balance);

            return $this->returnValue($account);
        } catch (\Exception $ex) {
            $this->logger->error("can't create accounts: ", [ $ex ]);
            return $this->returnError($ex->getMessage());
        }
    }
}