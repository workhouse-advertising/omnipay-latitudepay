<?php

namespace Omnipay\LatitudePay\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Omnipay\LatitudePay\Traits\GatewayParameters;
use Omnipay\LatitudePay\Traits\SignsRequest;

abstract class AbstractRequest extends BaseAbstractRequest
{
    use GatewayParameters;
    use SignsRequest;

    protected $liveEndpoint = 'https://api.latitudepay.com';
    protected $testEndpoint = 'https://api.uat.latitudepay.com';

    public function getHttpMethod()
    {
        return 'POST';
    }

    protected function getBaseData()
    {
        return [
            // TODO: Fill this.
        ];
    }

    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    
}
