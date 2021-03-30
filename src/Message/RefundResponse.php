<?php

namespace Omnipay\LatitudePay\Message;

use Omnipay\LatitudePay\Message\Response;

class RefundResponse extends Response
{
    protected $httpResponse;

    public function isSuccessful()
    {
        return $this->getHttpResponse() && $this->getHttpResponse()->getStatusCode() == 204;
    }

    public function getHttpResponse()
    {
        return $this->httpResponse;
    }

    public function setHttpResponse($httpResponse)
    {
        $this->httpResponse = $httpResponse;
        return $this;
    }
}
