<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Helper\UserHelper;
use App\Repository\CompanyRepository;
use App\Repository\OrderPaymentRepository;
use App\Repository\OrderRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\EmployeeRepository;
use App\Repository\UserRepository;
use App\Traits\UserTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/member')]
class MemberController extends AbstractController
{
    use UserTrait;

    #[Route('/profile', name: 'member_edit_profile', methods: ['GET', 'POST'])]
    public function profile(Request $request, UserHelper $userHelper, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        $session = $request->getSession();
        $session->set('previous_photo', $this->getUser()->getPhoto());

        $user->setPlainPassword($user->getPassword());
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            if(!$user->getPlainPassword()) {
                $user->setPassword($this->getUser()->getPassword());
            }else{
                $user->setPassword($userPasswordHasher->hashPassword($user, $user->getPlainPassword()));
            }

            $photo = $form->get('photo')->getData();
            $fileName = $userHelper->uploadAsset($photo, $user);
            if($fileName){
                $user->setPhoto($fileName);
            }else{
                if($session->get('previous_photo')) $user->setPhoto($session->get('previous_photo'));
            }

            $userRepository->add($user, true);
        }

        return $this->render('member/edit_account.html.twig', [
            'user' => $this->getUser(),
            'userForm' => $form->createView(),
        ]);
    }


    #[Route('/dashboard', name: 'member_dashboard', methods: ['GET'])]
    public function dashboard(Request $request): Response
    {
        return $this->render('member/dashboard.html.twig', [
            'user' => $this->getUser()
        ]);
    }


    #[Route('/payment', name: 'member_payment', methods: ['GET'])]
    public function payment(Request $request, OrderPaymentRepository $orderPaymentRepository): Response
    {
        return $this->render('member/payment.html.twig', [
            'user' => $this->getUser(),
            'payments' => $orderPaymentRepository->findAll()
        ]);
    }

    #[Route('/order', name: 'member_order', methods: ['GET'])]
    public function order(Request $request, OrderRepository $orderRepository): Response
    {
        return $this->render('member/order.html.twig', [
            'user' => $this->getUser(),
            'orders' => $orderRepository->findAll()
        ]);
    }

    #[Route('/company', name: 'member_company', methods: ['GET'])]
    public function company(Request $request, CompanyRepository $companyRepository): Response
    {
        return $this->render('member/company.html.twig', [
            'user' => $this->getUser(),
            'companies' => $companyRepository->findAll()
        ]);
    }

    #[Route('/employee', name: 'member_employee', methods: ['GET'])]
    public function employee(Request $request, EmployeeRepository $staffRepository): Response
    {
        $user = $this->getUser();
        $companies = $user->getCompanies();
        $staffList = [];
        foreach ($companies as $company){
            $staffList = array_merge($staffList, $staffRepository->findBy(['company' => $company->getId()]));
        }
        return $this->render('member/employee.html.twig', [
            'user' => $this->getUser(),
            'employees' => $staffList
        ]);
    }


    #[Route('/configuration', name: 'member_configuration', methods: ['GET'])]
    public function configuration(Request $request): Response
    {
        return $this->render('member/configuration.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    #[Route('/', name: 'member_index', methods: ['GET'])]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        return $this->render('member/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'member_show', methods: ['GET'])]
    public function show(Request $request, User $user): Response
    {
        return $this->render('member/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'member_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('member_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('member/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'member_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('member_index', [], Response::HTTP_SEE_OTHER);
    }

}
