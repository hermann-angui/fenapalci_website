<?php
namespace App\Traits;

use App\Entity\Subscription;
use App\Entity\User;
use App\Repository\CompanyRepository;
use App\Repository\EmployeeRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use App\Service\Wave\WaveCheckoutRequest;
use App\Service\Wave\WaveService;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Uid\Uuid;

trait UserTrait
{

    /**
     * @param string $amount
     * @param User|null $user
     * @return string|void
     */
    public function payForSubscription(string $amount, ?User $user)
    {
        $waveCheckoutRequest = new WaveCheckoutRequest();
        $waveCheckoutRequest->setCurrency("XOF")
                            ->setAmount($amount)
                            ->setClientReference(Uuid::v4()->toRfc4122())
                            ->setErrorUrl($this->generateUrl('wave_payment_callback', ["status" => "error"], UrlGenerator::ABSOLUTE_URL))
                            ->setSuccessUrl($this->generateUrl('wave_payment_callback', ["status" => "success"], UrlGenerator::ABSOLUTE_URL));

        $waveResponse = $this->waveService->checkOutRequest($waveCheckoutRequest);

        if ($waveResponse) {
            $subscription = new Subscription();
            $subscription->setAmount($waveResponse->getAmount())
                        ->setCurrency($waveResponse->getCurrency())
                        ->setPaymentReference($waveResponse->getClientReference())
                        ->setCheckoutSessionId($waveResponse->getCheckoutSessionId())
                        ->setSubscriber($user)
                        ->setOperator("WAVE")
                        ->setPaymentMode("WEBSITE")
                        ->setPaymentType("MOBILE_MONEY")
                        ->setPaymentDate($waveResponse->getWhenCreated())
                        ->setCreatedAt(new \DateTime())
                        ->setModifiedAt(new \DateTime())
                        ->setPaymentStatus(strtoupper($waveResponse->getPaymentStatus()));

            $this->subscriptionRepository->add($subscription, true);

            return $waveResponse->getWaveLaunchUrl();
        }
    }

    /**
     * @param array $payload
     * @param SubscriptionRepository $subscriptionRepository
     * @param UserRepository $userRepository
     * @return void
     */
    private function saveSubscriptionPaymentStatus(array $payload): void
    {
        if (!empty($payload) && array_key_exists("client_reference", $payload)) {
            $subscription = $this->subscriptionRepository->findOneBy(["payment_reference" => $payload["client_reference"], "checkout_session_id" => $payload["id"]]);
            if ($subscription) {
                $now = new \DateTime();
                $user = $subscription->getSubscriber();
                $this->updateUserStatus($user, $now, $this->userRepository);
                $subscription->setPaymentStatus(strtoupper($payload["payment_status"]));
                $subscription->setOperatorTransactionId($payload["transaction_id"]);
                $subscription->setSubscriptionStartDate($now);
                $subscription->setSubscriptionExpireDate($now->add(new \DateInterval('P1Y')));
                $subscription->setModifiedAt(new \DateTime());
                $this->subscriptionRepository->add($subscription, true);
            }
        }
    }

    /**
     * @param User|null $user
     * @param \DateTime $now
     * @param UserRepository $userRepository
     * @return void
     */
    private function updateUserStatus(?User $user, \DateTime $now, UserRepository $userRepository): void
    {
        $user->setStatus("SUBSCRIPTION_VALID");

        $userRepository->add($user, true);
    }

    /**
     * @param CompanyRepository $companyRepository
     * @param int|null $beneficiaryId
     * @param EmployeeRepository $employeeRepository
     * @param \DateTime $now
     * @return void
     */
    private function updateCompanyStatus(CompanyRepository $companyRepository, ?int $beneficiaryId, EmployeeRepository $employeeRepository, \DateTime $now): void
    {
        $company = $companyRepository->find($beneficiaryId);
        $employeeList = $employeeRepository->findBy(['company' => $company, 'status' => "WAITING_FOR_PAYMENT"]);
        foreach ($employeeList as $employee) {
            $employee->setStatus("VALID_MEMBER");
            $employee->setSubscriptionStartDate($now);
            $employee->setSubscriptionExpireDate($now->add(new \DateInterval('P1Y')));
            $employeeRepository->add($employee);
        }
        $company->setStatus("VALID_MEMBER");
        $company->setSubscriptionStartDate($now);
        $company->setSubscriptionExpireDate($now->add(new \DateInterval('P1Y')));
        $companyRepository->add($company);
    }

    /**
     * @param EmployeeRepository $employeeRepository
     * @param int|null $beneficiaryId
     * @param \DateTime $now
     * @return void
     */
    private function updateemployeeStatus(EmployeeRepository $employeeRepository, ?int $beneficiaryId, \DateTime $now): void
    {
        $employee = $employeeRepository->find($beneficiaryId);
        $employee->setStatus("SUBSCRIPTION_VALID");
        $employee->setSubscriptionStartDate($now);
        $employee->setSubscriptionExpireDate($now->add(new \DateInterval('P1Y')));
        $employeeRepository->add($employee);
    }

}
