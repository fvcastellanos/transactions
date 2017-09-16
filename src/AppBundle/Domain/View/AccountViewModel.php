<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/15/2017
 * Time: 2:58 PM
 */

namespace AppBundle\Domain\View;

use Symfony\Component\Validator\Constraints as Assert;

class AccountViewModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min = 4, max = 12)
     */
    public $account;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type = "double", message = "The value {{ value }} is not a valid {{ type }}")
     * @Assert\GreaterThan(value = 0)
     */
    public $balance;

}