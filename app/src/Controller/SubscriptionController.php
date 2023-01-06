<?php

namespace App\Controller;

use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use App\Service\Wave\WaveService;
use App\Traits\UserTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/subscription')]
class SubscriptionController extends AbstractController
{
    use UserTrait;

    protected WaveService $waveService;
    protected SubscriptionRepository $subscriptionRepository;
    protected UserRepository $userRepository;

    public function __construct(WaveService $waveService, SubscriptionRepository $subscriptionRepository, UserRepository $userRepository)
    {
        $this->waveService = $waveService;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->userRepository = $userRepository;
    }

    #[Route('/payment/categorie', name: 'subscription_payment', methods: ['GET'])]
    public function paymentSelectCategory(Request $request, ObjectManager $em): Response
    {
        return $this->render('subscription_payment/select_category.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route(path: '/payment/redirect', name: 'subscrption_payment_redirect_to_payment_service')]
    public function redirectToPaymentService(Request $request): Response
    {
        $user = $this->getUser();
        $categorie = $request->get('categorie');
        $payment_redirect_url = $this->payForSubscription(0, $user);

        return $this->redirect($payment_redirect_url);
    }

    #[Route(path: '/wave/checkout/{status}', name: 'wave_payment_callback')]
    public function wavePaymentCheckoutStatusCallback($status, Request $request): Response
    {
        return $this->render('subscription/checkout_result.html.twig', ['status' => $status]);
    }

    #[Route(path: '/wave', name: 'wave_payment_checkout_webhook')]
    public function callbackWavePayment(Request $request): Response
    {
        $payload =  json_decode($request->getContent(), true);
        $this->saveSubscriptionPaymentStatus($payload["data"]);
        return $this->json($payload);
    }

    #[Route(path: '/agrement', name: 'subscription_agrement')]
    public function howToPayment(Request $request): Response
    {
        $user = $this->getUser();
        return $this->render('pages/conditions_adhesion.html.twig', [
                'user' => $user]
        );
    }



}
