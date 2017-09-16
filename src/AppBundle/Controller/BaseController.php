<?php
/**
 * Created by PhpStorm.
 * User: fvcg
 * Date: 9/3/2017
 * Time: 1:13 AM
 */

namespace AppBundle\Controller;


use AppBundle\Domain\LoggedUser;
use AppBundle\Service\LoginService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    protected $loginService;

    /**
     * BaseController constructor.
     */
    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    protected function getMenuOptions()
    {
        $loggedUser = $this->getLoggedUser();
        $userName = null;
        if ($this->isUserLogged()) {
            $userName = $loggedUser->user;
        }

        return $this->loginService->getMenuOptions($userName);
    }

    protected function isUserLogged() {
        if (isset($this->getLoggedUser()->user)) {
            return true;
        }

        return false;
    }

    protected function storeLoggedUser($loggedUser) {
        $_SESSION['loggedUser'] = $loggedUser;
    }

    protected function getLoggedUser() : LoggedUser {
        if (isset($_SESSION['loggedUser'])) {
            return $_SESSION['loggedUser'];
        }

        return new LoggedUser();
    }

    protected function logoutUser() {
        $_SESSION['loggedUser'] = null;
    }

    protected function renderWithMenu($view, $model) {
        $parameters = array_merge(["menu" => $this->getMenuOptions()], $model);
        return $this->render($view, $parameters);
    }

    protected function renderValidationErrors($view, $form, $validationErrors) {
        return $this->renderWithMenu($view, [ "form" => $form, "errors" => $validationErrors]);
    }

    protected function renderAppErrors($view, $form, $errors) {
        return $this->renderWithMenu($view, [ "form" => $form->createView(), "app_errors" => $errors]);
    }

    protected function validateFormModel($model) {
        $validator = $this->get('validator');
        return $validator->validate($model);
    }

    protected function hasErrors($errors) {
        return count($errors) > 0;
    }

    protected function renderError($error) {
        return $this->renderWithMenu("error.html.twig", ['error' => $error]);
    }

}