<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Helper\UserHelper;
use App\Repository\PaymentTransactionRepository;
use App\Repository\UserRepository;
use App\Traits\UserTrait;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{

    use UserTrait;

    #[Route('/edit_account', name: 'app_user_edit_account', methods: ['GET', 'POST'])]
    public function profile(Request $request,
                            UserHelper $userHelper,
                            UserRepository $userRepository): Response
    {

        $user = $this->getUser();

        $this->redirectIfNotAllow();

        $session = $request->getSession();
        $session->set('previous_photo', $this->getUser()->getPhoto());

        $user->setPlainPassword($user->getPassword());
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $photo = $form->get('photo')->getData();
            if($photo){
                $fileName = $userHelper->uploadAsset($photo, $user);
                if($fileName) $user->setPhoto($fileName);
            }else{
                if($session->get('previous_photo')) $user->setPhoto($session->get('previous_photo'));
            }

            $userRepository->add($user, true);
        }

        return $this->render('user/edit_account.html.twig', [
            'user' => $user,
            'userForm' => $form->createView(),
        ]);
    }

    #[Route('/payment', name: 'app_user_payment', methods: ['GET'])]
    public function payment(Request $request,
                            EntityManagerInterface  $em,
                            PaymentTransactionRepository $paymentTransactionRepository): Response
    {

        $this->redirectIfNotAllow();

        $dql = "SELECT SUM(p.amount) AS balance, p.payment_status, p.payment_for FROM App\Entity\PaymentTransaction p  WHERE p.payer = ?1 GROUP BY p.payment_for, p.payment_status";
        $paymentStats = $em->createQuery($dql)
                    ->setParameter(1, $this->getUser())
                    ->getResult();

        $stats = [
            "FRAIS_ADHESION" => null,
            "REMBOURSEMENT" => null,
            "PENALITE" => null,
        ];

        $stats["total"] = 0;
        foreach ($paymentStats as $paymentStat){
             if($paymentStat["payment_status"] ==="PROCESSING") $stats["total"]+=$paymentStat["balance"];
             $stats[$paymentStat["payment_for"]][$paymentStat["payment_status"]] =  $paymentStat["balance"];
        }


     //   $paymentStats = $paymentTransactionRepository->findTotalAmountPayByUser($this->getUser());

        $payments = $paymentTransactionRepository->findBy(['payer' => $this->getUser()]);
        return $this->render('user/payment.html.twig', [
            'user' => $this->getUser(),
            'payments' => $payments,
            'paymentStats' => $stats,
        ]);
    }

    #[Route('/order', name: 'app_user_order', methods: ['GET'])]
    public function order(Request $request): Response
    {
        $this->redirectIfNotAllow();

        return $this->render('user/order.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/company', name: 'app_user_company', methods: ['GET'])]
    public function company(Request $request): Response
    {
        $this->redirectIfNotAllow();

        return $this->render('user/company.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/employee', name: 'app_user_employee', methods: ['GET'])]
    public function employee(Request $request): Response
    {
        $this->redirectIfNotAllow();

        return $this->render('user/employee.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/dashboard', name: 'app_user_dashboard', methods: ['GET'])]
    public function dashboard(Request $request): Response
    {
        $this->redirectIfNotAllow();

        return $this->render('user/dashboard.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/configuration', name: 'app_user_configuration', methods: ['GET'])]
    public function configuration(Request $request): Response
    {
        $this->redirectIfNotAllow();

        return $this->render('user/configuration.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $this->redirectIfNotAllow();

        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }


    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(Request $request, User $user): Response
    {
        $this->redirectIfNotAllow();

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $this->redirectIfNotAllow();

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        $this->redirectIfNotAllow();

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }


}
