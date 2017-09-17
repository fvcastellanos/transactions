<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/15/2017
 * Time: 3:20 PM
 */

namespace AppBundle\Model;


use AppBundle\Domain\Balance;
use AppBundle\Domain\Enum\TransactionTypeEnum;
use AppBundle\Domain\TransactionDetails;
use AppBundle\Domain\TransferCount;
use DB;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TransactionDao extends BaseDBDao
{
    private $logger;

    public function __construct(ContainerInterface $container,
                                LoggerInterface $logger,
                                Registry $registry)
    {
        parent::__construct($container, $logger, $registry);
        $this->logger = $logger;
    }

    public function createCreditTransaction(TransactionTypeEnum $typeEnum, $accountId, $description, $currency, $amount, $sourceAccountId) {
        try {
            DB::insert('transaction', array(
                'transaction_type' => $typeEnum->value,
                'account_id' => $accountId,
                'other_account_id' => $sourceAccountId,
                'credit' => 1,
                'description' => $description,
                'currency' => $currency,
                'amount' => $amount
            ));

            return $this->getLastInsertedId();

        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function createDebitTransaction(TransactionTypeEnum $typeEnum, $accountId, $description, $currency, $amount, $targetAccountId) {
        try {
            DB::insert('transaction', array(
                'transaction_type' => $typeEnum->value,
                'account_id' => $accountId,
                'other_account_id' => $targetAccountId,
                'credit' => 0,
                'description' => $description,
                'currency' => $currency,
                'amount' => $amount
            ));

            return $this->getLastInsertedId();

        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function getBalance($accountId) {
        try {
            $query = " select account_id, sum(ifnull(credits, 0)) credits, sum(ifnull(debits, 0)) debits, sum((ifnull(credits, 0) - ifnull(debits, 0))) balance" .
                " from " .
                " (select op.account_id, " .
                "    case when op.credit = 1 then ifnull(sum(op.total), 0) end credits, " .
                "    case when op.credit = 0 then ifnull(sum(op.total), 0) end debits " .
                "   from ( select  ifnull(sum(amount), 0) total, credit, account_id " .
                "          from transaction where account_id = %i group by account_id, credit " .
                "        ) op " .
                " 	group by op.account_id, op.credit) g group by account_id " ;

            $row = DB::queryFirstRow($query, $accountId);

            if (isset($row)) {
                return new Balance($row['account_id'], $row['debits'], $row['credits'], $row['balance']);
            }

            return null;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function getTransfersCount($accountId, $targetAccountId, $type) {
        try {
            $query = "select ifnull(count(transaction_type), 0) transfers, month(date) month, " .
                " year(date) year, account_id, other_account_id target " .
                " from transaction " .
                " where account_id = %i and other_account_id = %i " .
                " and	month(date) = month(now()) " .
                " and year(date) = year(now()) " .
                " and transaction_type = %s " .
                " group by month(date), year(date), account_id, other_account_id ";

            $row = DB::queryFirstRow($query, $accountId, $targetAccountId, $type);

            if (isset($row)) {
                return new TransferCount($row['transfers'], $row['month'], $row['year'], $row['account_id'],
                    $row['target']);
            }

            return new TransferCount(0, 0, 0, $accountId, $targetAccountId);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function getTransactionDetails($accountId) {
        try {
            $query = " select t.*, a.number, oa.number other, p.name " .
                " from transaction t " .
                "  inner join account a on t.account_id = a.id " .
                "  left join account oa on t.other_account_id = oa.id " .
                "  left join profile p on oa.profile_id = p.id " .
                " where account_id = %i " .
                " order by date ";

            $rows = DB::query($query, $accountId);

            if (isset($rows)) {
                $list = array();

                foreach ($rows as $row) {
                    $list[] = $this->buildTransactionDetails($row);
                }

                return $list;
            }

            return array();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    private function buildTransactionDetails($row) {
        $td = new TransactionDetails();

        $td->id = $row['id'];
        $td->accountId = $row['account_id'];
        $td->account = $row['number'];
        $td->otherAccountId = $row['other_account_id'];
        $td->otherAccount = $row['other'];
        $td->otherName = $row['name'];
        $td->type = $row['transaction_type'];
        $td->credit = $row['credit'];
        $td->date = $row['date'];
        $td->description = $row['description'];
        $td->amount = $row['amount'];
        $td->currency = $row['currency'];

        return $td;
    }
}