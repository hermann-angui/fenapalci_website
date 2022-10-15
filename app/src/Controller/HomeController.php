<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route(path: '/', name: 'app_home')]
    public function home(Request $request): Response
    {
        return $this->render('home/index.html.twig');
    }

/*
    #[Route(path: '/', name: 'app_home')]
    public function home(Request $request): Response
    {
        return $this->render('home/index.html.twig');
    }
 */

    #[Route(path: '/aboutus', name: 'app_about_us')]
    public function aboutUs(Request $request): Response
    {
        return $this->render('pages/index.html.twig');
    }
    #[Route(path: '/adhesion', name: 'app_adhesion')]
    public function adhesion(Request $request): Response
    {
        $session = $request->getSession();
        return $this->render('home/adhesion.html.twig',
            [
                "registrationNumber" => $session->get('registrationNumber')
            ]
        );
    }

    #[Route(path: '/payment', name: 'app_payment')]
    public function paymment(Request $request): Response
    {
        $type= $request->get('type');
        if($type=='adhesion'){
            return $this->render('home/payment.html.twig');
        }
    }

    #[Route(path: '/language/{lang}', name: 'app_change_language')]
    public function changeLanguage($lang): Response
    {
        if($lang)
        return $this->redirectToRoute('app_home');
    }
}
