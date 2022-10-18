<?php

namespace App\Controller;

use App\Entity\PaymentTransaction;
use App\Entity\User;
use App\Repository\PaymentTransactionRepository;
use App\Service\Payment\Wave\WaveCheckoutRequest;
use App\Service\Payment\Wave\WaveService;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Uid\Uuid;

#[Route('/payment')]
class PaymentController extends AbstractController
{
    #[Route(path: '/howto', name: 'app_payment_howto')]
    public function howToPayment(Request $request): Response
    {
        $user = $this->getUser();
        return $this->render('payment/howtopay.html.twig', ['user' => $user]);
    }

    #[Route(path: '/summary', name: 'app_payment_summary')]
    public function summaryPayment(Request $request,
                                 WaveService $waveService,
                                 PaymentTransactionRepository $paymentTransactionRepository): Response
    {
       $payment_redirect_url = $this->payToWave($this->getUser(), $waveService, $paymentTransactionRepository);
       // $payment_redirect_url = "http://wave.com/pay";
        return $this->render('payment/summary.html.twig', ["payment_redirect_url" => $payment_redirect_url]);
    }

    #[Route(path: '/wave/checkout/{status}', name: 'app_wave_payment_callback')]
    public function wavePaymentCheckoutStatusCallback($status): Response
    {
        $match = match ($status) {
            "success" => "<h1>SUCCESSFUL</h1>",
            "error" => "<h1>ERROR</h1>",
            default => "<h1>Unknow status</h1>",
        };

        return new Response($match);
    }

    #[Route(path: '/wave', name: 'app_wave_payment_checkout_webhook')]
    public function callbackWavePayment(Request $request): Response
    {
        $checkoutSessionPayload = json_decode($request->getContent(), true);
        if(!file_exists("/var/www/html/var/log/wave_checkout/")) mkdir("/var/www/html/var/log/wave_checkout");
        file_put_contents("/var/www/html/var/log/wave_checkout/checkoutSessionPayload_" . time() . ".json", $request->getContent());
        return $this->json($checkoutSessionPayload);
    }


    public function payToWave(?User $user, WaveService $waveService,PaymentTransactionRepository $paymentTransactionRepository)
    {
        $waveCheckoutRequest = new WaveCheckoutRequest();
        $waveCheckoutRequest->setCurrency("XOF")
            ->setAmount("1000")
            ->setClientReference(Uuid::v4()->toRfc4122())
            ->setErrorUrl($this->generateUrl('app_wave_payment_callback', ["status" => "error"], UrlGenerator::ABSOLUTE_URL))
            ->setSuccessUrl($this->generateUrl('app_wave_payment_callback', ["status" => "success"], UrlGenerator::ABSOLUTE_URL));

        $waveResponse = $waveService->checkOutRequest($waveCheckoutRequest);

        if($waveResponse){
            $payment = new PaymentTransaction();
            $payment->setAmount($waveResponse->getAmount())
                ->setCurrency($waveResponse->getCurrency())
                ->setPaymentReference($waveResponse->getClientReference())
                ->setCheckoutSessionId($waveResponse->getCheckoutSessionId())
                ->setPayer($user)
                ->setPaymentFor("FRAIS_ADHESION")
                ->setBeneficiary($user->getId())
                ->setPaymentMode("WEBSITE")
                ->setPaymentType("MOBILE_MONEY")
                ->setPaymentDate($waveResponse->getWhenCreated())
                ->setCreatedAt(new \DateTime())
                ->setModifiedAt(new \DateTime())
                ->setPaymentStatus(strtoupper($waveResponse->getPaymentStatus()));

            $paymentTransactionRepository->add($payment,true);

            return $waveResponse->getWaveLaunchUrl();
        }


    }
}
