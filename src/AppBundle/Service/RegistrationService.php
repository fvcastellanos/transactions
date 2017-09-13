<?php

namespace AppBundle\Service;

use AppBundle\Domain\Result;
use AppBundle\Domain\View\SignUpViewModel;
use AppBundle\Model\AccountDao;
use AppBundle\Model\ProfileDao;
use AppBundle\Model\RoleDao;
use AppBundle\Model\UserDao;
use MongoDB\Driver\Exception\ExecutionTimeoutException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class RegistrationService extends BaseService
{
    private $errors;
    private $userDao;
    private $roleDao;
    private $profileDao;
    private $accountDao;
    private $logger;

    /**
     * RegistrationService constructor.
     */
    public function __construct(LoggerInterface $logger,
                                UserDao $userDao,
                                RoleDao $roleDao,
                                AccountDao $accountDao,
                                ProfileDao $profileDao)
    {
        $this->errors = array();
        $this->userDao = $userDao;
        $this->roleDao = $roleDao;
        $this->profileDao = $profileDao;
        $this->accountDao = $accountDao;
        $this->logger = $logger;
    }

    public function registerUser(SignUpViewModel $model) : Result {

        try {
            $user = $this->userDao->findByUserName($model->user);

            if (isset($user)) {
                $this->logger->error("user already exists: ", [$user]);
                return $this->returnError("user: " . $user->getUser() . " already exists");
            }

            $account = $this->accountDao->findByAccountNumber($model->account);

            if (!isset($account)) {
                return $this->returnError("account: " . $model->account . " doesn't exists");
            }

            $profile = $account->getProfile();

            if (isset($profile)) {
                return $this->returnError("account: " . $model->account . " already assigned to another user");
            }

            $role = $this->roleDao->getUserRole();
            $user = $this->userDao->createUser($model->user, $model->password, $role);

            $profile = $this->profileDao->createUserProfile($model->name, $model->phone, $model->email, $user);

            $this->accountDao->associateAccountToProfile($account, $profile);

            return $this->returnValue($user);
        } catch (Exception $ex) {
            $this->logger->error("can't register user: ", $ex);
            return $this->returnError($ex->getMessage());
        }
    }

    public function getProfileByUserName($userName) {
        try {
            $profile = $this->profileDao->findProfileByUserName($userName);

            if (!isset($profile)) {
                return $this->returnError("user profile " . $userName . " not found");
            }

            return $this->returnValue($profile);
        } catch (\Exception $ex) {
            $this->logger->error("can't get profile", $ex);
            return $this->returnError($ex->getMessage());
        }
    }

    public function updateUserStatus($userName, $status) : Result {
        try {
            $user = $this->userDao->findByUserName($userName);

            if (!isset($user)) {
                return $this->returnError("user not found");
            }

            $profile = $this->profileDao->findProfileByUserName($userName);

            if (!isset($profile)) {
                return $this->returnError("user profile " . $userName . " not found");
            }

            $this->profileDao->updateProfileStatus($profile, $status);

            return $this->returnValue($profile);

        } catch (Exception $ex) {
            $this->logger->error("can't register user: ", $ex);
            return $this->returnError("can't register user: " . $ex->getMessage());
        }
    }

    public function getAccountList() : Result {
        try {
            $this->logger->info('getting account assigned');
            $accounts = $this->accountDao->findAccountsWithProfile();
            $this->logger->info('accounts: ', [$accounts]);

            return $this->returnValue($accounts);
        } catch (\Exception $ex) {
            $this->logger->error("can't get user list: ", [$ex]);
            return $this->returnError("can't get user list: " . $ex->getMessage());
        }
    }
}