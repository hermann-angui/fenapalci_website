<?php

namespace App\Controller;

use App\Entity\PaymentTransaction;
use App\Entity\User;
use App\Repository\CompanyRepository;
use App\Repository\PaymentTransactionRepository;
use App\Repository\StaffRepository;
use App\Repository\UserRepository;
use App\Service\Payment\Wave\WaveService;
use App\Traits\UserTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/payment')]
class PaymentController extends AbstractController
{
    use UserTrait;

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

        $user = $this->getUser();
        $amount = $request->get('amount');
        $payingFor =  $request->get('pay_for');
        $beneficiaryId =  $request->get('beneficiary_id');
        $beneficiaryType =  $request->get('beneficiary_type');

        $payment_redirect_url = $this->payToWave(
            $amount,
            $payingFor,
            $beneficiaryId,
            $beneficiaryType,
            $user,
            $waveService,
            $paymentTransactionRepository
        );

        return $this->render('payment/summary.html.twig', [
            "amount" => $amount,
            "message" => "Frais unique d’adhésion",
            "payment_redirect_url" => $payment_redirect_url
        ]);
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
                                        StaffRepository $staffRepository,
                                        CompanyRepository $companyRepository,
                                        UserRepository $userRepository): Response
    {
        $payload =  json_decode($request->getContent(), true);
        $this->savePaymentStatus(
            $payload["data"],
            $paymentTransactionRepository,
            $staffRepository,
            $companyRepository,
            $userRepository
        );

        return $this->json($payload);
    }

    /**
     * @param array $payload
     * @param PaymentTransactionRepository $paymentTransactionRepository
     * @param UserRepository $userRepository
     * @return void
     */
    private function savePaymentStatus( array $payload,
                                        PaymentTransactionRepository $paymentTransactionRepository,
                                        StaffRepository $staffRepository,
                                        CompanyRepository $companyRepository,
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
                $beneficiaryId = $payment->getBeneficiaryId();
                $beneficiary_type = $payment->getBeneficiaryType();
                switch($beneficiary_type){
                    case "MEMBER":
                        $user->setStatus("VALID_MEMBER");
                        $user->setSubscriptionStartDate($now);
                        $user->setSubscriptionExpireDate($now->add(new \DateInterval('P1Y')));
                        $userRepository->add($user, true);
                        break;
                    case "STAFF":
                        $staff = $staffRepository->find($beneficiaryId);
                        $staff->setStatus("VALID_MEMBER");
                        $staff->setSubscriptionStartDate($now);
                        $staff->setSubscriptionExpireDate($now->add(new \DateInterval('P1Y')));
                        $staffRepository->add($staff);
                        break;
                    case "COMPANY":
                        $company = $companyRepository->find($beneficiaryId);
                        $staffList = $staffRepository->findBy(['company' => $company, 'status' => "WAITING_FOR_PAYMENT"]);
                        foreach ($staffList as $staff){
                            $staff->setStatus("VALID_MEMBER");
                            $staff->setSubscriptionStartDate($now);
                            $staff->setSubscriptionExpireDate($now->add(new \DateInterval('P1Y')));
                            $staffRepository->add($staff);
                        }
                        $company->setStatus("VALID_MEMBER");
                        $company->setSubscriptionStartDate($now);
                        $company->setSubscriptionExpireDate($now->add(new \DateInterval('P1Y')));
                        $companyRepository->add($company);
                        break;
                    default:

                }
                $payment->setPaymentStatus(strtoupper($payload["payment_status"]));
                $payment->setOperatorTransactionId($payload["transaction_id"]);
                $payment->setModifiedAt(new \DateTime());
                $paymentTransactionRepository->add($payment, true);
            }
        }
    }
}
