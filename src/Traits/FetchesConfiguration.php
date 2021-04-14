<?php

namespace Omnipay\LatitudePay\Traits;

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

        // try {
        //     $httpResponse = $this->httpClient->request('POST', $this->getEndpoint(), $headers, json_encode($data));
        // } catch (\Exception $e) {
        //     dd($e, $e->getRequest(), (string) $e->getRequest()->getBody());
        // }

        $httpResponse = $this->httpClient->request('GET', $this->getEndpoint() . '/v3/configuration?' . http_build_query($data), $headers);
        $responseData = json_decode($httpResponse->getBody(), true);
        if ($httpResponse->getStatusCode() != 200) {

            // dd($httpResponse, (string) $httpResponse->getBody(), $responseData);

            // TODO: Consider filtering the response body in case it may have sensitive information in there.
            //       Although that _should_ never occur.
            // TODO: Consider adding support for accessing the errors in the body. Perhaps return an AuthorizeResponse with errors?
            //       Or maybe add a "debug" mode to this package?
            throw new InvalidRequestException("Invalid authorisation request to the LatitudePay API. Received status code '{$httpResponse->getStatusCode()}'.");
            // throw new InvalidRequestException("Invalid authorisation request to the LatitudePay API. Received status code '{$httpResponse->getStatusCode()}' and body: '{$httpResponse->getBody()}'.");
        }

        return $responseData;
    }
}
