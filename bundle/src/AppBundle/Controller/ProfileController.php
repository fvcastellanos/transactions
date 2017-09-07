<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Profile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Profile controller.
 *
 * @Route("profile")
 */
class ProfileController extends BaseController
{
    /**
     * Lists all profile entities.
     *
     * @Route("/", name="profile_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $profiles = $em->getRepository('AppBundle:Profile')->findAll();

        return $this->renderWithMenu('profile/index.html.twig', array(
            'profiles' => $profiles,
        ));
    }

    /**
     * Finds and displays a profile entity.
     *
     * @Route("/{id}", name="profile_show")
     * @Method("GET")
     */
    public function showAction(Profile $profile)
    {

        return $this->renderWithMenu('profile/show.html.twig', array(
            'profile' => $profile,
        ));
    }
}
