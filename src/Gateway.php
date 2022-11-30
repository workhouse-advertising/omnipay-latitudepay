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
}
