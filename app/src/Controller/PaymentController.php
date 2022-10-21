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
        $paymentRef = null;
        $errorMessage = null;

        try {
            $checkoutStatus = json_decode($request->getContent(), true);
            $folder = "/var/www/html/var/log/wave_checkout/";
            if (!file_exists($folder)) mkdir($folder);
            file_put_contents($folder . "/checkout_status_" . $status . "_" . date('d-m-Y') . ".json", $request->getContent());

            $this->saveCheckoutStatus($checkoutStatus,
                                      $paymentTransactionRepository,
                                       $userRepository
                                    );

        }catch (\Exception $exception) {
                $errorMessage = $exception->getMessage();
        }

        return $this->render('payment/checkout_result.html.twig', [
            'status' => $status,
            "payment_reference" => $paymentRef,
            "errorMessage" => $errorMessage]
        );
    }

    #[Route(path: '/wave', name: 'app_wave_payment_checkout_webhook')]
    public function callbackWavePayment(Request                      $request,
                                        PaymentTransactionRepository $paymentTransactionRepository,
                                        UserRepository               $userRepository): Response
    {

        $checkoutSessionPayload = json_decode($request->getContent(), true);
        $folder = "/var/www/html/var/log/wave_checkout/";
        if (!file_exists($folder)) mkdir($folder);
        file_put_contents($folder . "checkout_webhook_" . date('d-m-Y') . ".json", $request->getContent());

        $this->saveCheckoutStatus(
                    $checkoutSessionPayload,
                    $paymentTransactionRepository,
                    $userRepository
                );

        return $this->json($checkoutSessionPayload);
    }

    /**
     * @param mixed $checkoutStatus
     * @param PaymentTransactionRepository $paymentTransactionRepository
     * @param UserRepository $userRepository
     * @return void
     */
    private function saveCheckoutStatus(mixed $checkoutStatus,
                                        PaymentTransactionRepository $paymentTransactionRepository,
                                        UserRepository $userRepository): void
    {
        if (!empty($checkoutStatus) && array_key_exists("client_reference", $checkoutStatus["data"])) {
            $payment = $paymentTransactionRepository->findOneBy([
                "payment_reference" => $checkoutStatus["data"]["client_reference"],
                "checkout_session_id" => $checkoutStatus["data"]["id"]
            ]);

            if ($payment) {
                $now = new \DateTime();
                $user = $payment->getPayer();
                $user->setStatus("VALID_MEMBER");
                $user->setSubscriptionStartDate($now);
                $user->setSubscriptionExpireDate($now->add(new \DateInterval('P1Y')));
                $userRepository->add($user);

                $payment->setPaymentStatus($checkoutStatus["data"]["payment_status"]);
                $payment->setModifiedAt(new \DateTime());
                $paymentTransactionRepository->add($payment);
            }
        }
    }
}
