<?php

namespace AppBundle\Service;

use AppBundle\Domain\View\SignUpViewModel;
use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;

class RegistrationService
{
    private $repository;
    private $errors;

    /**
     * RegistrationService constructor.
     */
    public function __construct(Registry $registry)
    {
        $this->repository = $registry;
        $this->errors = array();
    }

    public function registerUser(SignUpViewModel $model) {
        $user = $this->repository
            ->getRepository(User::class)
            ->findOneBy(['user' => $model->user]);

        if (!isset($user)) {
            $usr = new User();


        }

        $this->addError("User: " . $user->user . " already exists");

        return
    }

    private function addError($error) {
        $this->errors[] = $error;
    }



}