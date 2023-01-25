<?php

namespace App\Controller;

use App\Traits\UserTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    use UserTrait;

    #[Route(path: '/', name: 'home')]
    public function home(Request $request): Response
    {
        return $this->render('pages/accueil.html.twig');
    }

    #[Route(path: '/a-propos-de-nous', name: 'about_us')]
    public function aboutUs(Request $request): Response
    {
        return $this->render('pages/aboutus.html.twig');
    }

    #[Route(path: '/nous-contacter', name: 'contact_us')]
    public function contactUs(Request $request): Response
    {
        return $this->render('pages/contactus.html.twig');
    }

    #[Route(path: '/assurance-maladie', name: 'insurance')]
    public function insurance(Request $request): Response
    {
       return $this->render('pages/contactus.html.twig');
    }

    #[Route(path: '/conseil-juridique', name: 'lawyer_protection')]
    public function lawyerProtection(Request $request): Response
    {
        return $this->render('pages/lawyer_protection.html.twig');
    }

    #[Route(path: '/formation-aux-membres', name: 'members_training')]
    public function memberTraining(Request $request): Response
    {
        return $this->render('pages/lawyer_protection.html.twig');
    }

    #[Route(path: '/nos-missions', name: 'missions')]
    public function missions(Request $request): Response
    {
        return $this->render('pages/lawyer_protection.html.twig');
    }

    #[Route(path: '/nos-activites', name: 'activities')]
    public function activities(Request $request): Response
    {
        return $this->render('pages/lawyer_protection.html.twig');
    }

    #[Route(path: '/avantages-aux-membres', name: 'avantages')]
    public function avantages(Request $request): Response
    {
        return $this->render('pages/lawyer_protection.html.twig');
    }

    #[Route(path: '/cotisations-sociales', name: 'social_care')]
    public function socialCare(Request $request): Response
    {
        return $this->render('pages/social_care.html.twig');
    }
}
