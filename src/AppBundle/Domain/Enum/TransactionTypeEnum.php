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


    public static function transferWithdraw() {
        return new TransactionTypeEnum("Transfer withdraw");
    }

    public static function transferDeposit() {
        return new TransactionTypeEnum("Transfer deposit");
    }

    public static function initialDeposit() {
        return new TransactionTypeEnum("Initial Deposit");
    }

    public static function deposit() {
        return new TransactionTypeEnum("Deposit");
    }

}