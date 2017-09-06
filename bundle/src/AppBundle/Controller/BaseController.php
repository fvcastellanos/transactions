<?php
/**
 * Created by PhpStorm.
 * User: fvcg
 * Date: 9/3/2017
 * Time: 1:13 AM
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    protected $errors;

    protected function getMenuOptions()
    {
        return [
            ["name" => "Home", "link" => "/"],
            ["name" => "Sign Up", "link" => "/sign-up"],
            ["name" => "Login", "link" => "/login"],
        ];
    }

    protected function renderWithMenu($view, $model) {
        $parameters = array_merge(["menu" => $this->getMenuOptions()], $model);
        return $this->render($view, $parameters);
    }

    protected function renderValidationErrors($view, $form, $validationErrors) {
        $this->renderWithMenu($view, [ "form" => $form, "errors" => $validationErrors]);
    }

    protected function renderAppErrors($view, $form, $errors) {
        $this->renderWithMenu($view, [ "form" => $form, "app_errors" => $errors]);
    }

    protected function validateFormModel($model) {
        $validator = $this->get('validator');
        return $validator->validate($model);
    }

    protected function hasErrors($errors) {
        return count($errors) > 0;
    }

    protected function renderError($error) {
        $this->renderWithMenu("error.html.twig", ['error' => $error]);
    }

}