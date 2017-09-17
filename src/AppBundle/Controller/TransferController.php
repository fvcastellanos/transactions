<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/16/2017
 * Time: 7:17 PM
 */

namespace AppBundle\Controller;

use AppBundle\Domain\View\TransferViewModel;
use AppBundle\Service\BeneficiaryService;
use AppBundle\Service\LoginService;
use AppBundle\Service\TransferService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Transfer controller.
 *
 * @Route("transfer")
 */
class TransferController extends BaseController
{
    private $beneficiaryService;
    private $transferService;

    /**
     * TransferController constructor.
     */
    public function __construct(LoginService $loginService,
                                TransferService $transferService,
                                BeneficiaryService $beneficiaryService)
    {
        parent::__construct($loginService);
        $this->beneficiaryService = $beneficiaryService;
        $this->transferService = $transferService;
    }

    /**
     *
     * @Route("/", name="transfer")
     */
    public function indexAction(Request $request) {
        $model = new TransferViewModel();

        $form = $this->buildTransferForm($model);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $loggedUser = $this->getLoggedUser();
            $result = $this->transferService->transfer($loggedUser->profileId, $model->beneficiary, $model->amount);

            if ($result->hasErrors()) {
                return $this->renderAppErrors('transfer/transfer.html.twig', $form, $result->getErrors());
            }

            return $this->renderWithMenu('transfer/confirm.html.twig', array());
        }

        return $this->renderWithMenu('transfer/transfer.html.twig', array('form' => $form->createView()));
    }

    private function buildTransferForm($model) {

        return $this->createFormBuilder($model)
            ->add('beneficiary', ChoiceType::class, array(
                    'choices' => $this->buildBeneficiariesChoices()
                  ))
            ->add('amount', NumberType::class)
            ->add('transfer', SubmitType::class, ['label' => 'Transfer'])
            ->getForm();
    }

    private function buildBeneficiariesChoices() {
        $loggedUser = $this->getLoggedUser();
        $result = $this->beneficiaryService->getBeneficiariesFor($loggedUser->profileId);

        if ($result->hasErrors()) {
            return array();
        }

        $options = array();
        foreach ($result->getObject() as $b) {
            $op = array($b->alias => $b->id);
            $options = array_merge($options, $op);
        }

        return $options;
    }
}