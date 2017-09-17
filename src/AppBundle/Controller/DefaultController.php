<?php

namespace AppBundle\Controller;

use AppBundle\Service\LoginService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends BaseController
{
    /**
     * DefaultController constructor.
     */
    public function __construct(LoginService $loginService)
    {
        parent::__construct($loginService);
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        if ($this->isUserLogged()) {
            return $this->redirectToRoute('welcome');
        }

        return $this->renderWithMenu('default/index.html.twig', []);
    }
}
