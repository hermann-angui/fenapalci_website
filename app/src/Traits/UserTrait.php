<?php
namespace App\Traits;

use App\Entity\PaymentTransaction;
use App\Entity\User;
use App\Repository\CompanyRepository;
use App\Repository\PaymentTransactionRepository;
use App\Repository\StaffRepository;
use App\Repository\UserRepository;
use App\Service\Payment\Wave\WaveCheckoutRequest;
use App\Service\Payment\Wave\WaveService;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

    /**
     * @param CompanyRepository $companyRepository
     * @param int|null $beneficiaryId
     * @param StaffRepository $staffRepository
     * @param \DateTime $now
     * @return void
     */
    private function updateCompanyStatus(CompanyRepository $companyRepository, ?int $beneficiaryId, StaffRepository $staffRepository, \DateTime $now): void
    {
        $company = $companyRepository->find($beneficiaryId);
        $staffList = $staffRepository->findBy(['company' => $company, 'status' => "WAITING_FOR_PAYMENT"]);
        foreach ($staffList as $staff) {
            $staff->setStatus("VALID_MEMBER");
            $staff->setSubscriptionStartDate($now);
            $staff->setSubscriptionExpireDate($now->add(new \DateInterval('P1Y')));
            $staffRepository->add($staff);
        }
        $company->setStatus("VALID_MEMBER");
        $company->setSubscriptionStartDate($now);
        $company->setSubscriptionExpireDate($now->add(new \DateInterval('P1Y')));
        $companyRepository->add($company);
    }

    /**
     * @param StaffRepository $staffRepository
     * @param int|null $beneficiaryId
     * @param \DateTime $now
     * @return void
     */
    private function updateStaffStatus(StaffRepository $staffRepository, ?int $beneficiaryId, \DateTime $now): void
    {
        $staff = $staffRepository->find($beneficiaryId);
        $staff->setStatus("VALID_MEMBER");
        $staff->setSubscriptionStartDate($now);
        $staff->setSubscriptionExpireDate($now->add(new \DateInterval('P1Y')));
        $staffRepository->add($staff);
    }

    /**
     * @param User|null $user
     * @param \DateTime $now
     * @param UserRepository $userRepository
     * @return void
     */
    private function updateUserStatus(?User $user, \DateTime $now, UserRepository $userRepository): void
    {
        $user->setStatus("VALID_MEMBER");
        $user->setSubscriptionStartDate($now);
        $user->setSubscriptionExpireDate($now->add(new \DateInterval('P1Y')));
        $userRepository->add($user, true);
    }

}
