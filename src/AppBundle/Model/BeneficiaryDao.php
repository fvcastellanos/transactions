<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/11/2017
 * Time: 10:27 PM
 */

namespace AppBundle\Model;


use AppBundle\Domain\Beneficiary;
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
        try {
            $query = " select b.*, a.number, p.name, a.profile_id " .
                " from beneficiary b " .
                "  inner join account a on a.id = b.account_id " .
                "  inner join profile p on p.id = a.profile_id " .
                " where b.profile_id = %i";

            $rows = DB::query($query, $profileId);

            if (isset($rows)) {
                $list = array();
                foreach ($rows as $row) {
                    $list[] = $this->buildBeneficiary($row);
                }

                return $list;
            }

            return null;

        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function createBeneficiary($profileId, $accountId, $alias, $maxAmount, $quota) {
        try {
            DB::insert('beneficiary', array(
                'account_id' => $accountId,
                'alias' => $alias,
                'max_amount_transfer' => $maxAmount,
                'transactions_quota' => $quota,
                'profile_id' => $profileId
            ));

            return DB::insertId();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function getBeneficiaryBy($profileId, $accountId) {
        try {
            $query = " select b.*, a.number, p.name " .
                " from beneficiary b " .
                "  inner join account a on a.id = b.account_id " .
                "  inner join profile p on p.id = a.profile_id " .
                " where b.profile_id = %i and a.id = %i";

            $row = DB::queryFirstRow($query, $profileId, $accountId);

            if (isset($row)) {
                return $this->buildBeneficiary($row);
            }

            return null;

        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function getBeneficiary($id) {
        try {
            $query = " select b.*, a.number, p.name " .
                " from beneficiary b " .
                "  inner join account a on a.id = b.account_id " .
                "  inner join profile p on p.id = a.profile_id " .
                " where b.id = %i";

            $row = DB::queryFirstRow($query, $id);

            if (isset($row)) {
                return $this->buildBeneficiary($row);
            }

            return null;

        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function deleteBeneficiary($id) {
        try {
            DB::delete('beneficiary', "id=%i", $id);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    private function buildBeneficiary($row) {
        $b = new Beneficiary();

        $b->id = $row['id'];
        $b->accountId = $row['account_id'];
        $b->account = $row['number'];
        $b->alias = $row['alias'];
        $b->maxAmountTransfer = $row['max_amount_transfer'];
        $b->transactionsQuota = $row['transactions_quota'];
        $b->profileId = $row['profile_id'];
        $b->name = $row['name'];

        return $b;
    }
}