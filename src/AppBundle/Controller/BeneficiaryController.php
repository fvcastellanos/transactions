<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Beneficiary;
use AppBundle\Service\BeneficiaryService;
use AppBundle\Service\LoginService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Beneficiary controller.
 *
 * @Route("beneficiary")
 */
class BeneficiaryController extends BaseController
{
    private $service;

    public function __construct(LoginService $loginService,
                                BeneficiaryService $service)
    {
        parent::__construct($loginService);
        $this->service = $service;
    }

    /**
     * Lists all beneficiary entities.
     *
     * @Route("/", name="beneficiaries")
     * @Method("GET")
     */
    public function indexAction()
    {
        $profileId = $this->getLoggedUser()->profileId;

        $result = $this->service->getBeneficiariesFor($profileId);

        if ($result->hasErrors()) {
            return $this->renderError($result->getErrors());
        }

        $beneficiaries = $result->getObject();

        return $this->renderWithMenu('beneficiary/index.html.twig', array(
            'beneficiaries' => $beneficiaries,
        ));
    }

    /**
     * Creates a new beneficiary entity.
     *
     * @Route("/new", name="new-beneficiary")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $beneficiary = new Beneficiary();
        $form = $this->createForm('AppBundle\Form\BeneficiaryType', $beneficiary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($beneficiary);
            $em->flush();

            return $this->redirectToRoute('beneficiaries', array('id' => $beneficiary->getId()));
        }

        return $this->render('beneficiary/new.html.twig', array(
            'beneficiary' => $beneficiary,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a beneficiary entity.
     *
     * @Route("/{id}", name="show-beneficiary")
     * @Method("GET")
     */
    public function showAction(Beneficiary $beneficiary)
    {
        $deleteForm = $this->createDeleteForm($beneficiary);

        return $this->render('beneficiary/show.html.twig', array(
            'beneficiary' => $beneficiary,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing beneficiary entity.
     *
     * @Route("/{id}/edit", name="edit-beneficiary")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Beneficiary $beneficiary)
    {
        $deleteForm = $this->createDeleteForm($beneficiary);
        $editForm = $this->createForm('AppBundle\Form\BeneficiaryType', $beneficiary);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('beneficiary_edit', array('id' => $beneficiary->getId()));
        }

        return $this->render('beneficiary/edit.html.twig', array(
            'beneficiary' => $beneficiary,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a beneficiary entity.
     *
     * @Route("/{id}", name="delete-beneficiary")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Beneficiary $beneficiary)
    {
        $form = $this->createDeleteForm($beneficiary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($beneficiary);
            $em->flush();
        }

        return $this->redirectToRoute('beneficiary_index');
    }

    /**
     * Creates a form to delete a beneficiary entity.
     *
     * @param Beneficiary $beneficiary The beneficiary entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Beneficiary $beneficiary)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('delete-beneficiary', array('id' => $beneficiary->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}