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
}