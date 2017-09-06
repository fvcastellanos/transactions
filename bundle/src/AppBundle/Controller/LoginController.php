<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/6/2017
 * Time: 1:11 AM
 */

namespace AppBundle\Controller;

use AppBundle\Domain\View\LoginViewModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends BaseController
{
    private $loginView = "login/login.html.twig";

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
        }

        return $this->renderWithMenu($this->loginView, [ 'form' => $form->createView()]);
    }

    private function buildLoginForm($model) {

        return $this->createFormBuilder($model)
            ->add('user', TextType::class)
            ->add('password', PasswordType::class)
            ->add('login', SubmitType::class, ['label' => 'Login'])
            ->getForm();
    }


}
