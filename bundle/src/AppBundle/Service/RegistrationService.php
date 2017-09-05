<?php

namespace AppBundle\Service;

use AppBundle\Domain\View\SignUpViewModel;
use AppBundle\Model\RoleDao;
use AppBundle\Model\UserDao;

class RegistrationService
{
    private $errors;
    private $userDao;
    private $roleDao;

    /**
     * RegistrationService constructor.
     */
    public function __construct(UserDao $userDao, RoleDao $roleDao)
    {
        $this->errors = array();
        $this->userDao = $userDao;
        $this->roleDao = $roleDao;
    }

    public function registerUser(SignUpViewModel $model) {
        $user = $this->userDao->findByUserName($model->user);

        if (isset($user)) {
            $this->addError("user: " . $user->getUser() . " already exists");
            return $this->errors;
        }

        $role = $this->roleDao->getUserRole();
        $this->userDao->createUser($model->user, $model->password, $role);

        return $this->errors;
    }

    private function addError($error) {
        $this->errors[] = $error;
    }
}