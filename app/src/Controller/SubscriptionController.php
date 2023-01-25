<?php

namespace App\Controller;

use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use App\Service\Wave\WaveService;
use App\Traits\UserTrait;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/payment/category', name: 'subscription_payment_category', methods: ['GET'])]
    public function paymentSelectCategory(Request $request, EntityManagerInterface $em): Response
    {
        return $this->render('subscription_payment/select_category.html.twig');
    }

    #[Route(path: '/payment/redirect', name: 'subscrption_payment_redirect_to_payment_service')]
    public function redirectToPaymentService(Request $request): Response
    {
        $user = $this->getUser();
        $category = $request->get('company_category');
        $amount = $this->getSubscriptionFee($category);
        $payment_redirect_url = $this->payForSubscription($amount, $user);

        if($payment_redirect_url) return $this->redirect($payment_redirect_url);
        else return $this->redirectToRoute('subscription_payment_category');

    }

    #[Route(path: '/wave/checkout/{status}', name: 'wave_payment_callback')]
    public function wavePaymentCheckoutStatusCallback($status, Request $request): Response
    {
        return $this->render('subscription/checkout_result.html.twig', [
            'status' => $status]);
    }

    #[Route(path: '/wave', name: 'wave_payment_checkout_webhook')]
    public function callbackWavePayment(Request $request): Response
    {
        $payload =  json_decode($request->getContent(), true);
        $this->saveSubscriptionPaymentStatus($payload["data"]);
        return $this->json($payload);
    }

    #[Route(path: '/agreement', name: 'subscription_agreement')]
    public function howToPayment(Request $request): Response
    {
        $user = $this->getUser();
        return $this->render('pages/conditions_adhesion.html.twig', [
                'user' => $user]
        );
    }


    private function getSubscriptionFee (int $index) : ?int
    {
        $fees =     [
            5000, // "GLACIER" => 5000,
            5000, // "BAR"  => 5000,
            2000, // "MAQUIS : 1-50 Places" => 2000,
            3000, // "MAQUIS : 50-100 Places"=> 3000,
            5000, // "MAQUIS : 101 Places et Plus" => 5000,
            2000, // "RESTAURANT : 1-50 Places" => 2000,
            3000, // "RESTAURANT : 51 Places et Plus"=> 3000,
            5000, // "MAQUIS/RESTAURANT"  => 5000,
            5000, // "RESTAURANT VIP" => 5000,
            5000, // "DEPOT DE BOISSON"  => 5000,
            5000, // "HOTEL"   => 5000,
            5000, // "NIGHT CLUB" => 5000,
            5000, // "EVENEMENTIEL" => 5000,
            5000, // "PATISSERIE" => 5000,
            2000, // "CAVE : 1-50 Places"  => 2000,
            3000, // "CAVE : 51 Places et Plus" => 3000
        ];
        return $fees[$index];
    }

}
