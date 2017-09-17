<?php
/**
 * Created by PhpStorm.
 * User: fvcg
 * Date: 9/3/2017
 * Time: 1:58 AM
 */

namespace AppBundle\Domain\View;

use Symfony\Component\Validator\Constraints as Assert;

class SignUpViewModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min = 4, max = 12)
     */
    public $account;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min = 10, max = 150)
     */
    public $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min = 8, max = 30)
     */
    public $phone;

    /**
     * @Assert\NotBlank()
     * @Assert\Email
     * @Assert\Length(max = 150)
     */
    public $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min = 4, max = 50)
     */
    public $user;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min = 6, max = 50)
     */
    public $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min = 6, max = 50)
     */
    public $confirmPassword;


//    /**
//     *  @Assert\IsTrue(message = "The passwords doesn't match")
//     */
//    public function isValidPassword() {
//        return $this->password == $this->confirmPassword;
//    }

}