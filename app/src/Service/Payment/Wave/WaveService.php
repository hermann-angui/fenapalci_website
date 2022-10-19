<?php

namespace App\Service\Payment\Wave;


use Symfony\Contracts\HttpClient\HttpClientInterface;

class WaveService
{
    const API_KEY = 'wave_ci_prod_4XND3J1y63CypaAeQqqWSMkK8foUdvw8mMbDZEyH0gmi5KfzERABL8RZaTgjaG-mH3K9-whXTQWE7f-vyk3AqPV04dq1JTPGdw';
    const BASE_URL = "https://api.wave.com/";
    const CHECKOUT_URL = "https://api.wave.com/v1/checkout/sessions";


    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function checkOutRequest(WaveCheckoutRequest $request)
    {
        try {

            $encodedPayload = json_encode([
                'amount' => $request->getAmount(),
                'currency' => $request->getCurrency(),
                'client_reference' => $request->getClientReference(),
                'success_url' => 'https://fenapalci.org/payment/wave/checkout/success',   //$request->getSuccessUrl(),
                'error_url' => 'https://fenapalci.org/payment/wave/checkout/error'        //$request->getSuccessUrl(),
            ]);

            $curlOptions = [
                CURLOPT_URL => self::CHECKOUT_URL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $encodedPayload,
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer " . self::API_KEY,
                    "Content-Type: application/json"
                ],
            ];

            # Execute the request and get a response
            $curl = curl_init();
            curl_setopt_array($curl, $curlOptions);
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                return null;
            } else {
                # You can now decode the response and use the checkout session. Happy coding ;)
                $checkout_session = json_decode($response, true);
                $waveResponse = new WaveCheckoutResponse();
                $waveResponse->setAmount($checkout_session["amount"])
                            ->setPaymentStatus($checkout_session["payment_status"])
                            ->setCurrency($checkout_session["currency"])
                            ->setSuccessUrl($checkout_session["success_url"])
                            ->setErrorUrl($checkout_session["error_url"])
                            ->setClientReference($checkout_session["client_reference"])
                            ->setCheckoutSessionId($checkout_session["id"])
                            ->setCheckoutStatus($checkout_session["checkout_status"])
                            ->setWhenCreated(new \DateTime($checkout_session["when_created"]))
                            ->setWhenCompleted(new \DateTime($checkout_session["when_completed"]))
                            ->setWhenExpires(new \DateTime($checkout_session["when_expires"]))
                            ->setWaveLaunchUrl($checkout_session["wave_launch_url"]);

                return $waveResponse;
           }

        }catch(\Exception $e){
            return null;
        }

    }

}