<?php

namespace AppBundle\Controller;

use AppBundle\Model\View\SignUpViewModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;


class RegisterController extends BaseController
{
    /**
     * @Route("/sign-up", name="register")
     */
    public function signUpAction(Request $request) {
        $form = $this->buildForm();
        return $this->renderWithMenu("register/sign-up.html.twig", [
            'form' => $form->createView()
        ]);
    }

    private function buildForm() {
        return $this->createFormBuilder(new SignUpViewModel())
            ->add('name', TextType::class)
            ->add('phone', TextType::class)
            ->add('email', EmailType::class)
            ->add('create', SubmitType::class, ['label' => 'Sign Up'])
            ->getForm();
    }
}