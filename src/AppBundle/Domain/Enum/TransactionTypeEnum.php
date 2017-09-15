<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/15/2017
 * Time: 1:10 PM
 */

namespace AppBundle\Domain\Enum;


class TransactionTypeEnum
{
    public $value;

    /**
     * TransactionType constructor.
     * @param $value
     */
    private function __construct($value)
    {
        $this->value = $value;
    }


    public static function transfer() {
        return new TransactionTypeEnum("Transfer");
    }

    public static function withdraw() {
        return new TransactionTypeEnum("Withdraw");
    }

    public static function deposit() {
        return new TransactionTypeEnum("Deposit");
    }

    public static function initialDeposit() {
        return new TransactionTypeEnum("Initial Deposit");
    }

}