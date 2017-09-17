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
    public function loginAction(Request $request) {
        $model = new LoginViewModel();
        $form = $this->buildLoginForm($model);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $validationErrors = $this->validateFormModel($formData);

            if ($this->hasErrors($validationErrors)) {
                return $this->renderValidationErrors($this->loginView, $form, $validationErrors);
            }

            $result = $this->loginService->validateUser($model->user, $model->password);

            if ($result->hasErrors()) {
                return $this->renderAppErrors($this->loginView, $form, $result->getErrors());
            }

            $this->storeLoggedUser($result->getObject());

            return $this->redirectToRoute("welcome");
        }

        return $this->renderWithMenu($this->loginView, [ 'form' => $form->createView()]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(Request $request) {
        $this->logoutUser();

        return $this->redirectToRoute("homepage");
    }

    /**
     * @Route("/welcome", name="welcome")
     */
    public function welcomeAction(Request $request) {

        return $this->renderWithMenu("login/welcome.html.twig", ['user' => $this->getLoggedUser()]);
    }

    private function buildLoginForm($model) {

        return $this->createFormBuilder($model)
            ->add('user', TextType::class)
            ->add('password', PasswordType::class)
            ->add('login', SubmitType::class, ['label' => 'Login'])
            ->getForm();
    }


}
