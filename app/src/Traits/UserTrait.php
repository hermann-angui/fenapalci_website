<?php
namespace App\Traits;

use App\Entity\PaymentTransaction;
use App\Entity\User;
use App\Repository\PaymentTransactionRepository;
use App\Service\Payment\Wave\WaveCheckoutRequest;
use App\Service\Payment\Wave\WaveService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Uid\Uuid;

trait UserTrait
{
    function redirectIfNotAllow() : ?RedirectResponse
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if($this->getUser()->getStatus() === "WAITING_FOR_PAYMENT") {
            return $this->redirectToRoute('app_home');
        }
        return null;
    }

    public function payToWave(string $amount,
                              string $payingFor,
                              int $beneficiaryId,
                              string $beneficiaryType,
                              ?User                        $user,
                              WaveService                  $waveService,
                              PaymentTransactionRepository $paymentTransactionRepository)
    {
        $waveCheckoutRequest = new WaveCheckoutRequest();
        $waveCheckoutRequest->setCurrency("XOF")
            ->setAmount($amount)
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
                ->setPaymentFor($payingFor)
                ->setBeneficiaryId($beneficiaryId)
                ->setBeneficiaryType($beneficiaryType)
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

}
