<?php

namespace AppBundle\Service;

use AppBundle\Domain\View\SignUpViewModel;
use AppBundle\Model\ProfileDao;
use AppBundle\Model\RoleDao;
use AppBundle\Model\UserDao;
use Symfony\Component\Config\Definition\Exception\Exception;
use Psr\Log\LoggerInterface;

class RegistrationService
{
    private $errors;
    private $userDao;
    private $roleDao;
    private $profileDao;
    private $logger;

    /**
     * RegistrationService constructor.
     */
    public function __construct(LoggerInterface $logger,
                                UserDao $userDao,
                                RoleDao $roleDao,
                                ProfileDao $profileDao)
    {
        $this->errors = array();
        $this->userDao = $userDao;
        $this->roleDao = $roleDao;
        $this->profileDao = $profileDao;
        $this->logger = $logger;

    }

    public function registerUser(SignUpViewModel $model) {

        try {
            $user = $this->userDao->findByUserName($model->user);

            if (isset($user)) {
                $this->addError("user: " . $user->getUser() . " already exists");
                return $this->errors;
            }

            $role = $this->roleDao->getUserRole();
            $user = $this->userDao->createUser($model->user, $model->password, $role);

            $this->profileDao->createUserProfile($model->name, $model->phone, $model->email, $user);

        } catch (Exception $ex) {
            $this->logger->error("can't register user: ", $ex);
            $this->addError($ex->getMessage());
        }

        return $this->errors;
    }

    private function addError($error) {
        $this->errors[] = $error;
    }
}