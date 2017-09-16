<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/15/2017
 * Time: 3:20 PM
 */

namespace AppBundle\Model;


use AppBundle\Domain\Enum\TransactionTypeEnum;
use AppBundle\Domain\TransactionType;
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

    public function createCreditTransaction(TransactionTypeEnum $typeEnum, $accountId, $description, $currency, $amount) {
        try {
            DB::insert('transaction', array(
                'transaction_type' => $typeEnum->value,
                'account_id' => $accountId,
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
}