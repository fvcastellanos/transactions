<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/16/2017
 * Time: 11:15 AM
 */

namespace AppBundle\Service;


use AppBundle\Domain\Enum\TransactionTypeEnum;
use AppBundle\Domain\Result;
use AppBundle\Model\AccountDao;
use AppBundle\Model\DepositDao;
use AppBundle\Model\TransactionDao;
use Psr\Log\LoggerInterface;

class DepositService extends BaseService
{
    private $accountDao;
    private $depositDao;
    private $transactionDao;
    private $logger;

    /**
     * DepositService constructor.
     */
    public function __construct(LoggerInterface $logger,
                                AccountDao $accountDao,
                                DepositDao $depositDao,
                                TransactionDao $transactionDao)
    {
        $this->logger = $logger;
        $this->accountDao = $accountDao;
        $this->depositDao = $depositDao;
        $this->transactionDao = $transactionDao;
    }

    public function getAccount($userName) : Result {
        try {
            $account = $this->accountDao->getAccountForUser($userName);

            if (!isset($account)) {
                return $this->returnError("no account associated to user: " . $userName);
            }

            return $this->returnValue($account);

        } catch (\Exception $ex) {
            $this->logger->error("can't get account info: ", array($ex));
            return $this->returnError($ex->getMessage());
        }
    }

    public function newDepositRequirement($accountNumber, $amount) : Result {
        try {
            $account = $this->accountDao->getAccount($accountNumber);

            if (!isset($account)) {
                return $this->returnError("account number not found: " . $accountNumber);
            }

            $requirement = $this->depositDao->createDepositRequirement($account->id, $account->currency, $amount);

            if (!isset($requirement)) {
                return $this->returnError("couldn't create deposit requirement");
            }

            return $this->returnValue($requirement);
        } catch (\Exception $ex) {
            return $this->returnError("can't create deposit requirement: " . $ex->getMessage());
        }
    }

    public function getDepositRequirements() {
        try {
            $requirements = $this->depositDao->getDepositRequirements();

            return $this->returnValue($requirements);
        } catch (\Exception $ex) {
            return $this->returnError("can't get deposit requirements: " . $ex->getMessage());
        }
    }

    public function getDepositRequirement($id) {
        try{
            $requirement = $this->depositDao->getDepositRequirement($id);

            if (!isset($requirement)) {
                return $this->returnError("deposit requirement not found");
            }

            return $this->returnValue($requirement);

        } catch (\Exception $exception) {
            return $this->returnError("can't get despoit requirement: " . $exception->getMessage());
        }
    }

    public function resolveDepositRequirement($id, $action) {
        try {
            $reason = $this->buildReason($action);

            // if accepted then create the deposit transaction
            if ($action == 'A') {
                $requirement = $this->depositDao->getDepositRequirement($id);
                $this->transactionDao->createCreditTransaction(TransactionTypeEnum::deposit(), $requirement->accountId,
                    $reason, $requirement->currency, $requirement->amount);
            }

            $this->depositDao->updateDepositRequirement($id, $reason, $action);

            return $this->returnValue($id);
        } catch (\Exception $ex) {
            return $this->returnError("can't update deposit requirement");
        }
    }

    private function buildReason($action) {
        switch ($action) {
            case "A":
                return "Requirement accepted";
            break;
            case "N":
                return "Requirement denied";
            break;
            default:
                return "Something happened";
            break;
        }
    }
}