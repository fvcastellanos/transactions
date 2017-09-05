<?php

namespace AppBundle\Controller;

use AppBundle\Domain\View\SignUpViewModel;
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

    private $view = "register/sign-up.html.twig";

    private $logger;
    private $service;

    /**
     * RegisterController constructor.
     * @param $logger
     */
    public function __construct(LoggerInterface $logger,
                                RegistrationService $service)
    {
        $this->logger = $logger;
        $this->service = $service;
    }

    /**
     * @Route("/sign-up", name="register")
     */
    public function signUpAction(Request $request) {
        $model = new SignUpViewModel();
        $form = $this->buildForm($model);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $model = $form->getData();

            $validationErrors = $this->validateFormModel($model);

            if ($this->hasErrors($validationErrors)) {
                $this->logger->info("validation errors: " . (string) $validationErrors);

                return $this->renderValidationErrors($this->view, $form, $validationErrors);
            }

            if ($model->password != $model->confirmPassword) {
                return $this->renderAppErrors($this->view, $form, "passwords should match");
            }

            $this->logger->info("everything is right, proceeding to register the user: ", $model->user);
            $appErrors = $this->service->registerUser($model);

            if ($this->hasErrors($appErrors)) {
                $this->logger->info("application errors: ");
                return $this->renderAppErrors($this->view, $form, $appErrors);
            }

            return $this->redirectToRoute("activate-user");
        }

        return $this->renderWithMenu($this->view, [ "form" => $form->createView() ]);
    }

    /**
     * @Route("/activate-user", name="activate-user")
     */
    public function activateAction() {

    }

    private function buildForm($model) {

        return $this->createFormBuilder($model)
            ->add('name', TextType::class)
            ->add('phone', TextType::class)
            ->add('email', EmailType::class)
            ->add('user', TextType::class)
            ->add('password', PasswordType::class)
            ->add('confirmPassword', PasswordType::class)
            ->add('create', SubmitType::class, ['label' => 'Sign Up'])
            ->getForm();
    }
}