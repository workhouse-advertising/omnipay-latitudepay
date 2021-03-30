<?php

namespace Omnipay\LatitudePay\Message;

use Omnipay\LatitudePay\Message\RefundResponse;

class RefundRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://api.latitudepay.com';
    protected $testEndpoint = 'https://api.uat.latitudepay.com';

    /**
     * @inheritDoc
     */
    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        $this->validate(
            'amount',
            'transactionReference',
        );

        
        return $data;
    }

    // /**
    //  * @inheritDoc
    //  */
    // public function sendData($data)
    // {
    //     $headers = [
    //         'Content-Type' => 'application/json',
    //     ];
    //     $httpResponse = $this->httpClient->request('POST', $this->getEndpoint(), $headers, json_encode($data));
    //     $responseData = json_decode($httpResponse->getBody(), true);

    //     $response = new RefundResponse($this, $responseData);
    //     $response->setHttpResponse($httpResponse);

    //     return $response;
    // }
}
