<?php

namespace AppBundle\Controller;

use AppBundle\Domain\View\BeneficiaryViewModel;
use AppBundle\Service\BeneficiaryService;
use AppBundle\Service\LoginService;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


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
        $stepI = new BeneficiaryViewModel();
        $form = $this->buildNewBeneficiaryStepI($stepI);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profileId = $this->getLoggedUser()->profileId;
            $result = $this->service->addBeneficiary($stepI, $profileId);

            if ($result->hasErrors()) {
                return $this->renderAppErrors('beneficiary/new.html.twig', $form, $result->getErrors());
            }

            return $this->redirectToRoute("beneficiaries");
        }

        return $this->renderWithMenu('beneficiary/new.html.twig', array(
            'stepI' => $stepI,
            'form' => $form->createView()));
    }

    /**
     *
     * @Route("/details/{id}", name="beneficiary-details")
     * @Method("GET")
     */
    public function detailsAction(Request $request, $id) {
        if (!isset($id)) {
            $this->renderError("beneficiary not found");
        }

        $result = $this->service->getBeneficiary($id);

        if ($result->hasErrors()) {
            return $this->renderError("can't get beneficiary info");
        }

        return $this->renderWithMenu('beneficiary/show.html.twig', array('b' => $result->getObject()));
    }

    /**
     *
     * @Route("/details", name="beneficiary-remove")
     * @Method("POST")
     */
    public function removeAction(Request $request) {
        $id = $request->get('id');

        if (!isset($id)) {
            return $this->renderError('no beneficiary found');
        }

        $result = $this->service->deleteBeneficiary($id);

        if ($result->hasErrors()) {
            return $this->renderError($result->getErrors());
        }

        return $this->redirectToRoute('beneficiaries');

    }

    private function buildNewBeneficiaryStepI($model) {

        return $this->createFormBuilder($model)
            ->add('account', TextType::class)
            ->add('alias', TextType::class)
            ->add('maxAmount', NumberType::class)
            ->add('transferQuota', NumberType::class)
            ->add('check', SubmitType::class, ['label' => 'Create'])
            ->getForm();
    }

}
