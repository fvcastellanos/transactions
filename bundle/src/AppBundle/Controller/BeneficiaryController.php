<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Beneficiary;
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
    /**
     * Lists all beneficiary entities.
     *
     * @Route("/", name="beneficiary_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $beneficiaries = $em->getRepository('AppBundle:Beneficiary')->findAll();

        return $this->render('beneficiary/index.html.twig', array(
            'beneficiaries' => $beneficiaries,
        ));
    }

    /**
     * Creates a new beneficiary entity.
     *
     * @Route("/new", name="beneficiary_new")
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

            return $this->redirectToRoute('beneficiary_show', array('id' => $beneficiary->getId()));
        }

        return $this->render('beneficiary/new.html.twig', array(
            'beneficiary' => $beneficiary,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a beneficiary entity.
     *
     * @Route("/{id}", name="beneficiary_show")
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
     * @Route("/{id}/edit", name="beneficiary_edit")
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
     * @Route("/{id}", name="beneficiary_delete")
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
            ->setAction($this->generateUrl('beneficiary_delete', array('id' => $beneficiary->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
