<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/6/2017
 * Time: 1:18 AM
 */

namespace AppBundle\Domain\View;

use Symfony\Component\Validator\Constraints as Assert;

class LoginViewModel
{
    /**
     * @Assert\NotBlank()
     */
    public $username;

    /**
     * @Assert\NotBlank()
     */
    public $password;
}