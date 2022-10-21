<?php

namespace App\Controller;

use App\Entity\PaymentTransaction;
use App\Entity\User;
use App\Repository\PaymentTransactionRepository;
use App\Repository\UserRepository;
use App\Service\Payment\Wave\WaveCheckoutRequest;
use App\Service\Payment\Wave\WaveService;
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
        return $this->render('payment/howtopay.html.twig', [
            'user' => $user]
        );
    }

    #[Route(path: '/summary', name: 'app_payment_summary')]
    public function summaryPayment(Request                      $request,
                                   WaveService                  $waveService,
                                   PaymentTransactionRepository $paymentTransactionRepository): Response
    {
        $payment_redirect_url = $this->payToWave(
            $this->getUser(),
            $waveService,
            $paymentTransactionRepository
        );

        return $this->render('payment/summary.html.twig', [
            "payment_redirect_url" => $payment_redirect_url
        ]);
    }

    public function payToWave(?User                        $user,
                              WaveService                  $waveService,
                              PaymentTransactionRepository $paymentTransactionRepository)
    {
        $waveCheckoutRequest = new WaveCheckoutRequest();
        $waveCheckoutRequest->setCurrency("XOF")
            ->setAmount("100")
            ->setClientReference(Uuid::v4()->toRfc4122())
            ->setErrorUrl($this->generateUrl('app_wave_payment_callback', ["status" => "error"], UrlGenerator::ABSOLUTE_URL))
            ->setSuccessUrl($this->generateUrl('app_wave_payment_callback', ["status" => "success"], UrlGenerator::ABSOLUTE_URL));

        $waveResponse = $waveService->checkOutRequest($waveCheckoutRequest);

        if ($waveResponse) {
            $payment = new PaymentTransaction();
            $payment->setAmount($waveResponse->getAmount())
                ->setCurrency($waveResponse->getCurrency())
                ->setPaymentReference($waveResponse->getClientReference())
                ->setCheckoutSessionId($waveResponse->getCheckoutSessionId())
                ->setPayer($user)
                ->setPaymentFor("FRAIS_ADHESION")
                ->setBeneficiary($user->getId())
                ->setOperator("WAVE")
                ->setPaymentMode("WEBSITE")
                ->setPaymentType("MOBILE_MONEY")
                ->setPaymentDate($waveResponse->getWhenCreated())
                ->setCreatedAt(new \DateTime())
                ->setModifiedAt(new \DateTime())
                ->setPaymentStatus(strtoupper($waveResponse->getPaymentStatus()));

            $paymentTransactionRepository->add($payment, true);

            return $waveResponse->getWaveLaunchUrl();
        }
    }

    #[Route(path: '/wave/checkout/{status}', name: 'app_wave_payment_callback')]
    public function wavePaymentCheckoutStatusCallback($status,
                                                      Request $request,
                                                      PaymentTransactionRepository $paymentTransactionRepository,
                                                      UserRepository               $userRepository): Response
    {
        return $this->render('payment/checkout_result.html.twig', ['status' => $status]);
    }

    #[Route(path: '/wave', name: 'app_wave_payment_checkout_webhook')]
    public function callbackWavePayment(Request                      $request,
                                        PaymentTransactionRepository $paymentTransactionRepository,
                                        UserRepository               $userRepository): Response
    {
        $payload =  json_decode($request->getContent(), true);
        $this->savePaymentStatus($payload["data"], $paymentTransactionRepository, $userRepository);

        return $this->json($payload);
    }

    /**
     * @param array $payload
     * @param PaymentTransactionRepository $paymentTransactionRepository
     * @param UserRepository $userRepository
     * @return void
     */
    private function savePaymentStatus(array $payload,
                                        PaymentTransactionRepository $paymentTransactionRepository,
                                        UserRepository $userRepository): void
    {
        if (!empty($payload) && array_key_exists("client_reference", $payload)) {
            $payment = $paymentTransactionRepository->findOneBy([
                "payment_reference" => $payload["client_reference"],
                "checkout_session_id" => $payload["id"]
            ]);

            if ($payment) {
                $now = new \DateTime();
                $user = $payment->getPayer();
                $user->setStatus("VALID_MEMBER");
                $user->setSubscriptionStartDate($now);
                $user->setSubscriptionExpireDate($now->add(new \DateInterval('P1Y')));
                $userRepository->add($user);

                $payment->setPaymentStatus($payload["payment_status"]);
                $payment->setModifiedAt(new \DateTime());
                $paymentTransactionRepository->add($payment);
            }
        }
    }
}
