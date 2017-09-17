<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/16/2017
 * Time: 7:43 PM
 */

namespace AppBundle\Domain\View;

use Symfony\Component\Validator\Constraints as Assert;

class TransferViewModel
{
    /**
     * @Assert\NotBlank()
     */
    public $beneficiary;

    /**
     * @Assert\NotBlank()
     * @Assert\GreaterThan(value = 0)
     * @Assert\Type(type = "double", message = "The value {{ value }} is not a valid {{ type }}")
     */
    public $amount;
}