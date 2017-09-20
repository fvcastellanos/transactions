<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/17/2017
 * Time: 2:23 PM
 */

namespace AppBundle\Security;


use AppBundle\Domain\LoggedUser;
use AppBundle\Model\ProfileDao;
use AppBundle\Model\UserDao;
use AppBundle\Service\ShaUtils;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{

    private $userDao;
    private $profileDao;

    public function __construct(UserDao $userDao, ProfileDao $profileDao)
    {
        $this->userDao = $userDao;
        $this->profileDao = $profileDao;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        // TODO: Implement loadUserByUsername() method.
        $user = $this->userDao->findByUserName($username);

        if (isset($user)) {

            $profile = $this->profileDao->findProfileByUserName($user->user);

            if ($profile->active == 0) {
                throw new UsernameNotFoundException(
                    sprintf('Username "%s" does not exist.', $username)
                );
            }

            $loggedUser = new LoggedUser();
            $loggedUser->user = $user->user;
            $loggedUser->email = $profile->email;
            $loggedUser->name = $profile->name;
            $loggedUser->profileId = $profile->id;
            $loggedUser->role = $user->role;

            $_SESSION['loggedUser'] = $loggedUser;

            return new AppUser($username, $user->password, null, array($user->role));
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }

    /**
     * Refreshes the user.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the user is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        // TODO: Implement refreshUser() method.
        if (!$user instanceof AppUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        // TODO: Implement supportsClass() method.
        return AppUser::class === $class;
    }
}