<?php

namespace AppBundle\Controller;

use AppBundle\Domain\View\ProfileViewModel;
use AppBundle\Domain\View\SignUpViewModel;
use AppBundle\Service\LoginService;
use AppBundle\Service\RegistrationService;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class RegisterController extends BaseController
{

    private $signUpView = "register/sign-up.html.twig";
    private $activateView = "register/activate.html.twig";

    private $logger;
    private $service;

    /**
     * RegisterController constructor.
     * @param $logger
     */
    public function __construct(LoggerInterface $logger,
                                RegistrationService $service,
                                LoginService $loginService)
    {
        parent::__construct($loginService);
        $this->logger = $logger;
        $this->service = $service;
    }

    /**
     * @Route("/sign-up", name="register")
     */
    public function signUpAction(Request $request) {
        $model = new SignUpViewModel();
        $form = $this->buildSingUpForm($model);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $model = $form->getData();

            $validationErrors = $this->validateFormModel($model);

            if ($this->hasErrors($validationErrors)) {
                $this->logger->info("validation errors: " . (string) $validationErrors);

                return $this->renderValidationErrors($this->signUpView, $form, $validationErrors);
            }

            if ($model->password != $model->confirmPassword) {
                return $this->renderAppErrors($this->signUpView, $form, ["passwords should match"]);
            }

            $this->logger->info("everything is right, proceeding to register the user: ", [ $model->user ]);
            $result = $this->service->registerUser($model);

            if ($result->hasErrors()) {
                $this->logger->info("application errors: ", $result->getErrors());
                return $this->renderAppErrors($this->signUpView, $form, $result->getErrors());
            }

            $userName = $result->getObject()->getUser();
            return $this->redirectToRoute("activate-user", [ "user" => $userName ]);
        }

        return $this->renderWithMenu($this->signUpView, [ "form" => $form->createView() ]);
    }

    /**
     * @Route("/list-users", name="list-users", methods="GET")
     */
    public function listUsers() {
        $result = $this->service->getAccountList();

        if ($result->hasErrors()) {
            return $this->render('error.html.twig', ['error' => $result->getErrors()]);
        }

        return $this->renderWithMenu("register/user-list.html.twig", ['accounts' => $result->getObject()]);
    }

    /**
     * @Route("/activate-user/{user}", name="activate-user", methods="GET")
     */
    public function activateAction($user) {
        $result = $this->service->getProfileByUserName($user);

        if ($result->hasErrors()) {
            return $this->renderError($result->getErrors());
        }

        $profile = $result->getObject();
        $profileView = new ProfileViewModel($profile->getName(), $profile->getPhone(), $profile->getEmail(), $profile->getActive());

        return $this->renderWithMenu($this->activateView, ["profile" => $profileView, "user" => $user]);
    }

    /**
     * @Route("/confirm-user/{user}", name="confirm-user", methods="GET")
     */
    public function confirmAction($user) {
        $result = $this->service->getProfileByUserName($user);

        if ($result->hasErrors()) {
            return $this->renderError($result->getErrors());
        }

        $profile = $result->getObject();
        $profileView = new ProfileViewModel($profile->getName(), $profile->getPhone(), $profile->getEmail(), $profile->getActive());

        return $this->renderWithMenu('register/details.html.twig', ["profile" => $profileView, "user" => $user]);
    }

    /**
     * @Route("/confirm-user", name="update-user", methods="POST")
     */
    public function updateAction(Request $request) {
        $userName = $_POST['user'];
        $status = $_POST['activate'];

        $result = $this->service->updateUserStatus($userName, $status);

        if ($result->hasErrors()) {
            return $this->renderError($result->getErrors());
        }

        return $this->redirectToRoute("homepage");

    }

    private function buildSingUpForm($model) {

        return $this->createFormBuilder($model)
            ->add('account', TextType::class)
            ->add('name', TextType::class)
            ->add('phone', TextType::class)
            ->add('email', EmailType::class)
            ->add('user', TextType::class)
            ->add('password', PasswordType::class)
            ->add('confirmPassword', PasswordType::class)
            ->add('create', SubmitType::class, ['label' => 'Sign Up'])
            ->getForm();
    }

    private function buildActivateForm($model) {
        $this->createFormBuilder()
            ->add('name', TextType::class, ['enabled' => false])
            ->add('activate', SubmitType::class, ['label' => 'Activate']);
    }
}