<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/6/2017
 * Time: 1:11 AM
 */

namespace AppBundle\Controller;

use AppBundle\Domain\View\LoginViewModel;
use AppBundle\Service\LoginService;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class LoginController extends BaseController
{
    private $logger;
    private $loginView = "login/login.html.twig";

    /**
     * LoginController constructor.
     * @param $loginService
     */
    public function __construct(LoginService $loginService,
                                LoggerInterface $logger)
    {
        parent::__construct($loginService);
        $this->logger = $logger;
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils) {
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->renderWithMenu($this->loginView, [ 'error' => $error,
            'lastUserName' => $lastUsername]);
    }

    /**
     * @Route("/exit", name="exit")
     */
    public function logoutAction(Request $request) {
        $this->logoutUser();

        return $this->redirectToRoute("logout");
    }

    /**
     * @Route("/welcome", name="welcome")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function welcomeAction(Request $request) {

        return $this->renderWithMenu("login/welcome.html.twig", ['user' => $this->getLoggedUser()]);
    }

    private function buildLoginForm($model) {

        return $this->createFormBuilder($model)
            ->add('username', TextType::class)
            ->add('password', PasswordType::class)
            ->add('login', SubmitType::class, ['label' => 'Login'])
            ->getForm();
    }


}
