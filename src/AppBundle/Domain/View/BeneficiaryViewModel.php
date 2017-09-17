<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/12/2017
 * Time: 9:52 PM
 */

namespace AppBundle\Domain\View;

use Symfony\Component\Validator\Constraints as Assert;

class BeneficiaryViewModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min = 4, max = 12)
     */
    public $account;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min = 3, max = 50)
     */
    public $alias;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type = "double", message = "The value {{ value }} is not a valid {{ type }}")
     */
    public $maxAmount;

    /**
     * @Assert\Type(type = "double", message = "The value {{ value }} is not a valid {{ type }}")
     * @Assert\LessThan(value = 100)
     */
    public $transferQuota;

}