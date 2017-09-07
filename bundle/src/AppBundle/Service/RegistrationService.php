<?php

namespace AppBundle\Service;

use AppBundle\Domain\Result;
use AppBundle\Domain\View\SignUpViewModel;
use AppBundle\Model\ProfileDao;
use AppBundle\Model\RoleDao;
use AppBundle\Model\UserDao;
use Psr\Log\LoggerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class RegistrationService extends BaseService
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

    public function registerUser(SignUpViewModel $model) : Result {

        try {
            $user = $this->userDao->findByUserName($model->user);

            if (isset($user)) {
                return $this->returnError("user: " . $user->getUser() . " already exists");
            }

            $role = $this->roleDao->getUserRole();
            $user = $this->userDao->createUser($model->user, $model->password, $role);

            $this->profileDao->createUserProfile($model->name, $model->phone, $model->email, $user);

            return $this->returnValue($user);
        } catch (Exception $ex) {
            $this->logger->error("can't register user: ", $ex);
            $this->addError($ex->getMessage());
            return $this->returnError($ex->getMessage());
        }
    }

    public function getProfileByUserName($userName) {
        try {
            $profile = $this->profileDao->findProfileByUserName($userName);

            if (!isset($profile)) {
                return $this->returnError("user profile " . $userName . " not found");
            }

            if ($profile->getActive() == 1) {
                return $this->returnError("profile is already active");
            }

            return $this->returnValue($profile);
        } catch (\Exception $ex) {
            $this->logger->error("can't get profile", $ex);
            return $this->returnError($ex->getMessage());
        }
    }

    public function activateUser($userName) : Result {
        try {
            $user = $this->userDao->findByUserName($userName);

            if (!isset($user)) {
                return $this->returnError("user not found");
            }

            $profile = $this->profileDao->findProfileByUserName($userName);

            if (!isset($profile)) {
                return $this->returnError("user profile " . $userName . " not found");
            }

            if ($profile->getActive() == 1) {
                return $this->returnError("user is already active");
            }

            $this->profileDao->activateProfile($profile);

            return $this->returnValue($profile);

        } catch (Exception $ex) {
            $this->logger->error("can't register user: ", $ex);
            return $this->returnError("can't register user: " . $ex->getMessage());
        }
    }

    private function addError($error) {
        $this->errors[] = $error;
    }
}