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

        if ($user->getPassword() != $convertedPassword) {
            $this->logger->error("invalid password for user: ",  [ $userName ]);
            return $this->returnError(LoginService::$defaultError);
        }

        $this->logger->info("user authenticated: ", [ $userName ]);

        $profile = $this->profileDao->findProfileByUserName($userName);

        $loggedUser = new LoggedUser();
        $loggedUser->user = $user->getUser();
        $loggedUser->email = $profile->getName();
        $loggedUser->profileId = $profile->getId();
        $loggedUser->roles = $user->getRole();

        return $this->returnValue($loggedUser);
    }

}