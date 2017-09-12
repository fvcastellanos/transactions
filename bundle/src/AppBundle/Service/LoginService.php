<?php
/**
 * Created by PhpStorm.
 * User: fcastellanos
 * Date: 9/6/17
 * Time: 17:44
 */

namespace AppBundle\Service;


use AppBundle\Domain\LoggedUser;
use AppBundle\Domain\Result;
use AppBundle\Model\ProfileDao;
use AppBundle\Model\UserDao;
use Psr\Log\LoggerInterface;

class LoginService extends BaseService
{
    private $userDao;
    private $profileDao;
    private $logger;

    private static $defaultError = "Unable to login, invalid user / password";

    /**
     * LoginService constructor.
     * @param $userDao
     * @param $profileDao
     */
    public function __construct(LoggerInterface $logger,
                                UserDao $userDao,
                                ProfileDao $profileDao)
    {
        $this->userDao = $userDao;
        $this->profileDao = $profileDao;
        $this->logger = $logger;
    }

    public function validateUser($userName, $password) : Result {
        $convertedPassword = ShaUtils::sha512($password);

        $user = $this->userDao->findByUserName($userName);

        if (!isset($user)) {
            $this->logger->error("user not found: ",[ $userName ]);
            return $this->returnError(LoginService::$defaultError);
        }

        $profile = $this->profileDao->findProfileByUserName($userName);

        if (!isset($profile)) {
            $this->logger->error("profile not found for user: ", [$userName]);
            return $this->returnError(LoginService::$defaultError);
        }

        if (!$profile->getActive()) {
            $this->logger->error("profile it not active for user: ", [$userName]);
            return $this->returnError(LoginService::$defaultError);
        }

        if ($user->getPassword() != $convertedPassword) {
            $this->logger->error("invalid password for user: ",  [ $userName ]);
            return $this->returnError(LoginService::$defaultError);
        }

        $this->logger->info("user authenticated: ", [ $userName ]);

        $loggedUser = new LoggedUser();
        $loggedUser->user = $user->getUser();
        $loggedUser->email = $profile->getName();
        $loggedUser->profileId = $profile->getId();
        $loggedUser->roles = $user->getRole();

        return $this->returnValue($loggedUser);
    }

    public function getMenuOptions($userName) {

        $this->logger->info("getting menu options for user: ", [ $userName ]);
        if (!isset($userName)) {
            $this->logger->info("user not defined: ", [ $userName ]);
            return [
                ["name" => "Home", "route" => "homepage"],
                ["name" => "Sign Up", "route" => "register"],
                ["name" => "Login", "route" => "login"],
            ];
        }

        $roles = $this->getUserRoles($userName);
        $options = array();

        $this->logger->info("information found for user: ", [ $userName ]);

        foreach ($roles as  $role) {
            if ($role->getName() == 'ADMIN') {
                $this->logger->info("pulling admin options for user: ", [ $userName ]);
                $options = array_merge($options, [
//                    ["name" => "Accounts", "route" => "accounts"],
                    ["name" => "Users", "route" => "list-users"],
//                    ["name" => "Deposit", "route" => "deposit-inquiry"],
                ]);
            }

            if ($role->getName() == 'USER') {
                $this->logger->info("pulling user options for user: ", [ $userName ]);
                $options = array_merge($options, [
//                    ["name" => "Beneficiaries", "route" => "beneficiaries"],
//                    ["name" => "Transfers", "route" => "transfers"],
//                    ["name" => "Deposit", "route" => "deposit"],
//                    ["name" => "Transactions", "route" => "transactions"],
                ]);
            }
        }

        $options[] = ["name" => "Logout", "route" => "logout"];

        return $options;
    }

    private function getUserRoles($userName) {
        $user = $this->userDao->findByUserName($userName);

        return $user->getRole();
    }

}