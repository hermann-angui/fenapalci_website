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

    #[Route(path: '/aboutus', name: 'app_about_us')]
    public function aboutUs(Request $request): Response
    {
        return $this->render('pages/index.html.twig');
    }


    #[Route(path: '/test', name: 'app_test')]
    public function test(Request $request): Response
    {
        return $this->render('pages/test.html.twig');
    }

}
