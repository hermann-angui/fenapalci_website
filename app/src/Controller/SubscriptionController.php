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

    #[Route('/payment', name: 'subscription_payment', methods: ['GET'])]
    public function payment(Request $request
                            ): Response
    {

        $dql = "SELECT SUM(p.amount) AS balance, p.payment_status, p.payment_for FROM App\Entity\Subscription p  WHERE p.payer = ?1 GROUP BY p.payment_for, p.payment_status";
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
            if($paymentStat["payment_status"] ==="SUCCEEDED") $stats["total"]+=$paymentStat["balance"];
            $stats[$paymentStat["payment_for"]][$paymentStat["payment_status"]] =  $paymentStat["balance"];
        }

        $payments = $this->subscriptionRepository->findBy(['payer' => $this->getUser()]);

        return $this->render('member/payment.html.twig', [
            'user' => $this->getUser(),
            'active' => 'order',
            'payments' => $payments,
            'paymentStats' => $stats,
        ]);
    }

    #[Route(path: '/payment/summary', name: 'subscrption_payment_summary')]
    public function summaryPayment(Request $request): Response
    {
        $user = $this->getUser();
        $amount = $request->get('amount', 0);

        $payment_redirect_url = $this->payForSubscription($amount, $user);

        return $this->render('subscription_payment/summary.html.twig', [
            "amount" => $amount,
            "message" => "Frais unique d’adhésion",
            "payment_redirect_url" => $payment_redirect_url
        ]);
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
