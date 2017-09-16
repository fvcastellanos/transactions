<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/15/2017
 * Time: 5:40 PM
 */

namespace AppBundle\Model;


use AppBundle\Domain\DepositRequirement;
use DB;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DepositDao extends BaseDBDao
{
    public function __construct(ContainerInterface $container,
                                LoggerInterface $logger,
                                Registry $registry)
    {
        parent::__construct($container, $logger, $registry);
    }

    public function createDepositRequirement($accountId, $currency, $amount) {
        try {

            DB::insert('deposit_requirement', array(
                'account_id' => $accountId,
                'currency' => $currency,
                'amount' => $amount,
                'status' => 'R'
            ));

            return DB::insertId();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function getDepositRequirements() {
        try {
            $query = "select dr.*, a.number, p.name " .
                " from deposit_requirement dr " .
                "   inner join account a on dr.account_id = a.id " .
                "   inner join profile p on a.profile_id = p.id " .
                " where status = 'R' " .
                " order by requested_date ";

            $rows = DB::query($query);

            if (isset($rows)) {
                $list = array();
                foreach ($rows as $row) {
                    $list[] = $this->buildDespositRequirement($row);
                }

                return $list;
            }

            return null;

        } catch(\Exception $ex) {
            throw $ex;
        }
    }

    public function getDepositRequirement($id) {
        try {
            $query = "select dr.*, a.number, p.name " .
                " from deposit_requirement dr " .
                "   inner join account a on dr.account_id = a.id " .
                "   inner join profile p on a.profile_id = p.id " .
                " where dr.id = %i " .
                " order by requested_date ";

            $row = DB::queryFirstRow($query, $id);

            if (isset($row)) {
                return $this->buildDespositRequirement($row);
            }

            return null;

        } catch(\Exception $ex) {
            throw $ex;
        }
    }

    public function updateDepositRequirement($id, $reason, $status) {
        try {
            DB::update('deposit_requirement', array(
                'resolution_date' => DB::sqlEval('NOW()'),
                'resolution_reason' => $reason,
                'status' => $status
            ), 'id=%i', $id);

        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    private function buildDespositRequirement($row) {
        $dr = new DepositRequirement();

        $dr->id = $row['id'];
        $dr->accountId = $row['account_id'];
        $dr->requestedDate = $row['requested_date'];
        $dr->amount = $row['amount'];
        $dr->currency = $row['currency'];
        $dr->status = $row['status'];
        $dr->resolution = $row['resolution_reason'];
        $dr->resolutionDate = $row['resolution_date'];
        $dr->account = $row['number'];
        $dr->name = $row['name'];

        return $dr;
    }

}