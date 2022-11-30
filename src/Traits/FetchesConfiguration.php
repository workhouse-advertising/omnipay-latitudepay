<?php

namespace Omnipay\LatitudePay\Traits;

use Omnipay\Common\Exception\InvalidRequestException;

trait FetchesConfiguration
{
    use RefreshesAuthToken;

    /**
     * TODO: See if we actually need to use this at all...
     *
     * @param [type] $totalAmount
     * @param boolean $displayInModal
     * @return void
     */
    public function getConfiguration($totalAmount, $displayInModal = false)
    {
        $this->checkAndRefreshToken();

        $headers = [
            'Accept' => 'application/com.latitudepay.ecom-v3.1+json',
            'Authorization' => "Bearer {$this->getAuthToken()}",
        ];

        $data = [
            'totalAmount' => $totalAmount,
            // TODO: Add support for displaying in a modal.
            'displayInModal' => $displayInModal,
        ];

        $httpResponse = $this->httpClient->request('GET', $this->getEndpoint() . '/v3/configuration?' . http_build_query($data), $headers);
        $responseData = json_decode($httpResponse->getBody(), true);
        if ($httpResponse->getStatusCode() != 200) {
            // TODO: Consider filtering the response body in case it may have sensitive information in there.
            //       Although that _should_ never occur.
            // TODO: Consider adding support for accessing the errors in the body. Perhaps return an AuthorizeResponse with errors?
            //       Or maybe add a "debug" mode to this package?
            throw new InvalidRequestException("Invalid authorisation request to the LatitudePay API. Received status code '{$httpResponse->getStatusCode()}'.");
        }

        return $responseData;
    }
}
