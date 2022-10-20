<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/edit_account', name: 'app_user_edit_account', methods: ['GET', 'POST'])]
    public function profile(Request $request, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $user->setPlainPassword($user->getPassword());
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);
        }

        return $this->render('user/edit_account.html.twig', [
            'user' => $user,
            'userForm' => $form->createView(),
        ]);
    }

    #[Route('/payment', name: 'app_user_payment', methods: ['GET'])]
    public function payment(Request $request): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        return $this->render('user/payment.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/order', name: 'app_user_order', methods: ['GET'])]
    public function order(Request $request): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        return $this->render('user/order.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/company', name: 'app_user_company', methods: ['GET'])]
    public function company(Request $request): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        return $this->render('user/company.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/employee', name: 'app_user_employee', methods: ['GET'])]
    public function employee(Request $request): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        return $this->render('user/employee.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/dashboard', name: 'app_user_dashboard', methods: ['GET'])]
    public function dashboard(Request $request): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        return $this->render('user/dashboard.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/configuration', name: 'app_user_configuration', methods: ['GET'])]
    public function configuration(Request $request): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        return $this->render('user/configuration.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(Request $request, User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
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
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }


}
