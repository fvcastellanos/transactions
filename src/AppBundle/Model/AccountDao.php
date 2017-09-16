<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/6/2017
 * Time: 11:34 PM
 */

namespace AppBundle\Model;

use AppBundle\Domain\Account;
use AppBundle\Domain\AccountProfile;
use DB;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AccountDao extends BaseDBDao
{

    private $logger;

    public function __construct(ContainerInterface $container,
                                LoggerInterface $logger,
                                Registry $registry)
    {
        parent::__construct($container, $logger, $registry);
        $this->logger = $logger;
    }

    public function newAccount($number, $profileId, $currency) {
        try {
            DB::insert('account', array(
                'profile_id' => $profileId,
                'number' => $number,
                'currency' => $currency
            ));

            return $this->getLastInsertedId();

        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function associateAccountToProfile($accountId, $profileId) {
        try {
            DB::update('account', array(
                'profile_id' => $profileId
            ), "id=%s", $accountId);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function findAccountsWithProfile() {
        try {
            $query = "select p.name, u.user, a.number, p.active, a.currency " .
                " from account a " .
                "   inner join profile p on a.profile_id = p.id " .
                "   inner join user u on p.user_id = u.id";

            $rows = DB::query($query);

            if (isset($rows)) {
                $accounts = array();
                foreach ($rows as $row) {
                    $accounts[] = new AccountProfile($row['name'], $row['user'], $row['number'], $row['currency'], $row['active']);
                }

                return $accounts;
            }

            return null;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function getAccount($accountNumber) {
        try {
            $row = DB::queryFirstRow("select * from account where number = %s", $accountNumber);

            if (isset($row)) {
                return new \AppBundle\Domain\Account($row['id'], $row['profile_id'], null, $row['number'], $row['currency']);
            }

            return null;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function getAccounts() {
        try {
            $rows = DB::query("select a.id, a.number, a.currency, p.name " .
                " from account a " .
                " left join profile p on a.profile_id = p.id");
            $this->logger->info("rows: ", [ $rows ]);
            $accounts = array();
            if (isset($rows)) {
                foreach ($rows as $row) {
                    $accounts[] = new \AppBundle\Domain\Account($row['id'], null, $row['name'], $row['number'], $row['currency']);
                }

                return $accounts;
            }

            return null;

        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function getAccountForUser($userName) {
        try {
            $query = "select a.*, p.name " .
                " from account a " .
                "  inner join profile p on p.id = a.profile_id " .
                "  inner join user u on p.user_id = u.id " .
                " where u.user = %s";

            $row = DB::queryFirstRow($query, $userName);

            if (isset($row)) {
                return new Account($row['id'], $row['profile_id'], $row['name'], $row['number'], $row['currency']);
            }

            return null;

        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}