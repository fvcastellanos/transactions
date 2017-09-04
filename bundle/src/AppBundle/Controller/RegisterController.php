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

    private $logger;
    private $service;

    /**
     * RegisterController constructor.
     * @param $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->service = new RegistrationService($this->getDoctrine());
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

            $validator = $this->get('validator');
            $this->errors = $validator->validate($model);
            $this->errors[] = $model->isValidPassword();


            $this->logger->info("validation errors: " . (string) $this->errors);
            if (count($this->errors) == 0) {
                $this->logger->info('getting the model, name: ' . $model->name);
                $this->service->registerUser($model);
            }

            $this->logger->info("errors: " . (string) $this->errors);
        }

        return $this->renderWithMenu("register/sign-up.html.twig", [
            'form' => $form->createView(),
            'errors' => $this->errors
        ]);
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