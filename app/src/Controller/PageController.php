<?php

namespace App\Controller;

use App\Traits\UserTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class PageController extends AbstractController
{
    use UserTrait;

    #[Route(path: '/', name: 'home')]
    public function home(Request $request): Response
    {
        return $this->render('pages/accueil.html.twig');
    }

    #[Route(path: '/aboutus', name: 'about_us')]
    public function aboutUs(Request $request): Response
    {
        return $this->render('pages/aboutus.html.twig');
    }

    #[Route(path: '/contactus', name: 'contact_us')]
    public function contactUs(Request $request): Response
    {
        return $this->render('pages/contactus.html.twig');
    }



}
