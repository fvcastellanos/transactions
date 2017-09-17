<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/15/2017
 * Time: 1:28 PM
 */

namespace AppBundle\Controller;


use AppBundle\Domain\View\AccountViewModel;
use AppBundle\Service\AccountService;
use AppBundle\Service\LoginService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Account controller.
 *
 * @Route("account")
 */
class AccountController extends BaseController
{

    private $logger;
    private $service;

    public function __construct(LoggerInterface $logger,
                                LoginService $loginService,
                                AccountService $accountService)
    {
        parent::__construct($loginService);
        $this->logger = $logger;
        $this->service = $accountService;
    }

    /**
     * @Route("/", name="accounts")
     */
    public function indexAction(Request $request) {
        $result = $this->service->getAccounts();

        if ($result->hasErrors()) {
            return $this->renderError($result->getErrors());
        }

        return $this->renderWithMenu('account/index.html.twig', ['accounts' => $result->getObject()]);
    }

    /**
     * @Route("/new", name="new-account")
     */
    public function newAction(Request $request) {
        $model = new AccountViewModel();
        $form = $this->buildNewAccountForm($model);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->service->createAccount($model->account, $model->balance);

            if ($result->hasErrors()) {
                return $this->renderAppErrors('account/new.html.twig', $form, $result->getErrors());
            }

            return $this->redirectToRoute("accounts");
        }

        return $this->renderWithMenu('account/new.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/details", name="account-details")
     */
    public function detailsAction(Request $request) {
        $user = $this->getLoggedUser();

        $info = $this->service->getTransactionDetails($user->profileId);

        if ($info->hasErrors()) {
            return $this->renderError($info->getErrors());
        }

        $details = $info->getObject();
        return $this->renderWithMenu('account/details.html.twig', $details);
    }

    private function buildNewAccountForm($model) {

        return $this->createFormBuilder($model)
            ->add('account', TextType::class)
            ->add('balance', NumberType::class)
            ->add('create', SubmitType::class, ['label' => 'Create'])
            ->getForm();
    }

}