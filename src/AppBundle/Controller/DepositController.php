<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/15/2017
 * Time: 5:34 PM
 */

namespace AppBundle\Controller;

use AppBundle\Domain\View\RequirementViewModel;
use AppBundle\Service\DepositService;
use AppBundle\Service\LoginService;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Deposit controller.
 *
 * @Route("deposit")
 */
class DepositController extends BaseController
{
    private $logger;
    private $service;

    /**
     * DepositController constructor.
     * @param $service
     */
    public function __construct(LoggerInterface $logger,
                                LoginService $loginService,
                                DepositService $service)
    {
        parent::__construct($loginService);

        $this->service = $service;
        $this->logger = $logger;
    }


    /**
     *
     * @Route("/requirement", name="requirement")
     * @Security("has_role('USER')")
     */
    public function requirementAction(Request $request) {
        $account = $this->getAccountFromSignedUser();

        if (!isset($account)) {
            return $this->renderError("can't get account associated with signed user");
        }

        $model = new RequirementViewModel();
        $model->account = $account->number;

        $form = $this->buildDepositRequirementForm($model);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->service->newDepositRequirement($model->account, $model->amount);

            if ($result->hasErrors()) {
                return $this->renderAppErrors('deposit/requirement.html.twig', $form, $result->getErrors());
            }

            return $this->renderWithMenu('deposit/confirm.html.twig', []);
        }

        return $this->renderWithMenu('deposit/requirement.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * @Route("/review", name="deposit-review")
     * @Method("GET")
     * @Security("has_role('ADMIN')")
     */
    public function reviewAction(Request $request) {
        $result = $this->service->getDepositRequirements();

        if ($result->hasErrors()) {
            return $this->renderError($result->getErrors());
        }

        return $this->renderWithMenu('deposit/review-list.html.twig', array('requirements' => $result->getObject()));
    }

    /**
     * @Route("/details/{id}", name="deposit-details")
     * @Method("GET")
     * @Security("has_role('ADMIN')")
     */
    public function detailsAction(Request $request, $id) {
        if (!isset($id)) {
            return $this->renderError("deposit requirement not found");
        }

        $result = $this->service->getDepositRequirement($id);

        if ($result->hasErrors()) {
            return $this->renderError($result->getErrors());
        }

        return $this->renderWithMenu("deposit/details.html.twig", array('requirement' => $result->getObject()));
    }

    /**
     * @Route("/details", name="deposit-update")
     * @Method("POST")
     * @Security("has_role('ADMIN')")
     */
    public function updateAction(Request $request) {

        $id = $request->get('id');
        $action = $request->get('action');

        if (!isset($id)) {
            return $this->renderError("deposit requirement not found");
        }

        if (!isset($action)) {
            return $this->renderError("need to define an action");
        }

        $result = $this->service->resolveDepositRequirement($id, $action);

        if ($result->hasErrors()) {
            return $this->renderError($result->getErrors());
        }

        return $this->redirectToRoute("deposit-review");
    }

    private function getAccountFromSignedUser() {
        $user = $this->getLoggedUser();
        $result = $this->service->getAccount($user->user);

        if ($result->hasErrors()) {
            $this->logger->error("can't get account for logger user");
            return null;
        }

        return $result->getObject();
    }

    private function buildDepositRequirementForm($model) {

        return $this->createFormBuilder($model)
            ->add('account', TextType::class)
            ->add('amount', NumberType::class)
            ->add('request', SubmitType::class, ['label' => 'Request'])
            ->getForm();
    }
}