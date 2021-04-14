<?php

namespace Omnipay\LatitudePay;

use Omnipay\Common\AbstractGateway;
use Omnipay\LatitudePay\Message\AuthorizeRequest;
use Omnipay\LatitudePay\Message\CompleteAuthorizeRequest;
use Omnipay\LatitudePay\Message\CaptureRequest;
use Omnipay\LatitudePay\Message\CancelRequest;
use Omnipay\LatitudePay\Message\RefundRequest;
use Omnipay\LatitudePay\Traits\FetchesConfiguration;
use Omnipay\LatitudePay\Traits\GatewayParameters;
use Omnipay\LatitudePay\Traits\RefreshesAuthToken;

class Gateway extends AbstractGateway
{
    use FetchesConfiguration;
    use GatewayParameters;
    use RefreshesAuthToken;

    protected $liveEndpoint = 'https://api.latitudepay.com';
    protected $testEndpoint = 'https://api.uat.latitudepay.com';

    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    public function getName()
    {
        return 'LatitudePay';
    }

    public function getDefaultParameters()
    {
        return [
            'clientToken' => '',
            'clientSecret' => '',
            'authToken' => null,
            'authTokenExpires' => null,
            'testMode' => false,
        ];
    }

    /**
     * @inheritDoc
     */
    public function authorize(array $parameters = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $parameters);
    }

    /**
     * @inheritDoc
     */
    public function completeAuthorize(array $options = [])
    {
        return $this->createRequest(CompleteAuthorizeRequest::class, $options);
    }

    // public function checkAndRefreshToken()
    // {
    //     $authToken = $this->getAuthToken();
    //     $authTokenExpires = $this->getAuthTokenExpires();
    //     // Refresh the auth token if it has expired or is due to expire within the next five minutes.
    //     if (!$authToken || !$authTokenExpires || ((int) strtotime($authTokenExpires)) <= strtotime('-5 minutes')) {
    //         $this->refreshAuthToken();
    //     }
    // }

    // public function refreshAuthToken()
    // {
    //     $headers = [
    //         'Accept' => 'application/com.latitudepay.ecom-v3.1+json',
    //         'Authorization' => "Basic {$this->getAuthorisationBasicPassword()}",
    //     ];

    //     $data = [];

    //     $httpResponse = $this->httpClient->request('POST', $this->getEndpoint() . '/v3/token', $headers, json_encode($data));
    //     $responseData = json_decode($httpResponse->getBody(), true);
    //     if ($httpResponse->getStatusCode() != 200) {

    //         // dd($httpResponse, (string) $httpResponse->getBody(), $responseData);

    //         // TODO: Consider filtering the response body in case it may have sensitive information in there.
    //         //       Although that _should_ never occur.
    //         // TODO: Consider adding support for accessing the errors in the body. Perhaps return an AuthorizeResponse with errors?
    //         //       Or maybe add a "debug" mode to this package?
    //         throw new InvalidRequestException("Unable to fetch an auth token from the LatitudePay API. Received status code '{$httpResponse->getStatusCode()}'.");
    //         // throw new InvalidRequestException("Unable to fetch an auth token from the LatitudePay API. Received status code '{$httpResponse->getStatusCode()}' and body: '{$httpResponse->getBody()}'.");
    //     }

    //     // dd($httpResponse, (string) $httpResponse->getBody(), $responseData);
    //     // TODO: Consider throwing an Exception if the auth token wasn't returned.
    //     $this->setAuthToken($responseData['authToken'] ?? null);
    //     $this->setAuthTokenExpires($responseData['expiryDate'] ?? null);
    // }

    // /**
    //  * @inheritDoc
    //  */
    // public function refund(array $options = [])
    // {
    //     return $this->createRequest(RefundRequest::class, $options);
    // }
}
