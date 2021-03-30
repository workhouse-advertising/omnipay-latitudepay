<?php

namespace Omnipay\LatitudePay;

use Omnipay\Common\AbstractGateway;
use Omnipay\LatitudePay\Message\AuthorizeRequest;
use Omnipay\LatitudePay\Message\CompleteAuthorizeRequest;
use Omnipay\LatitudePay\Message\CaptureRequest;
use Omnipay\LatitudePay\Message\CancelRequest;
use Omnipay\LatitudePay\Message\RefundRequest;
use Omnipay\LatitudePay\Traits\GatewayParameters;

class Gateway extends AbstractGateway
{
    use GatewayParameters;

    public function getName()
    {
        return 'LatitudePay';
    }

    public function getDefaultParameters()
    {
        return [
            'merchantId' => '',
            'secretKey' => '',
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

    // /**
    //  * @inheritDoc
    //  */
    // public function refund(array $options = [])
    // {
    //     return $this->createRequest(RefundRequest::class, $options);
    // }
}
