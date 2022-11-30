<?php

namespace Omnipay\LatitudePay\Traits;

use Omnipay\Common\Exception\InvalidRequestException;

trait RefreshesAuthToken
{
    public function checkAndRefreshToken()
    {
        $authToken = $this->getAuthToken();
        $authTokenExpires = $this->getAuthTokenExpires();
        // Refresh the auth token if it has expired or is due to expire within the next five minutes.
        if (!$authToken || !$authTokenExpires || ((int) strtotime($authTokenExpires)) <= strtotime('-5 minutes')) {
            $this->refreshAuthToken();
        }
    }

    public function refreshAuthToken()
    {
        $headers = [
            'Accept' => 'application/com.latitudepay.ecom-v3.1+json',
            'Authorization' => "Basic {$this->getAuthorisationBasicPassword()}",
        ];

        $data = [];

        $httpResponse = $this->httpClient->request('POST', $this->getEndpoint() . '/v3/token', $headers, json_encode($data));
        $responseData = json_decode($httpResponse->getBody(), true);
        if ($httpResponse->getStatusCode() != 200) {
            // TODO: Consider filtering the response body in case it may have sensitive information in there.
            //       Although that _should_ never occur.
            // TODO: Consider adding support for accessing the errors in the body. Perhaps return an AuthorizeResponse with errors?
            //       Or maybe add a "debug" mode to this package?
            throw new InvalidRequestException("Unable to fetch an auth token from the LatitudePay API. Received status code '{$httpResponse->getStatusCode()}'.");
        }

        // TODO: Consider throwing an Exception if the auth token wasn't returned.
        $this->setAuthToken($responseData['authToken'] ?? null);
        $this->setAuthTokenExpires($responseData['expiryDate'] ?? null);
    }
}
