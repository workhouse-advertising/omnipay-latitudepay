<?php

namespace Omnipay\LatitudePay\Traits;

trait GatewayParameters
{
    // public function getMerchantId()
    // {
    //     return $this->getParameter('merchantId');
    // }

    // public function setMerchantId($value)
    // {
    //     return $this->setParameter('merchantId', $value);
    // }

    // public function getSecretKey()
    // {
    //     return $this->getParameter('secretKey');
    // }

    // public function setSecretKey($value)
    // {
    //     return $this->setParameter('secretKey', $value);
    // }

    public function getClientToken()
    {
        return $this->getParameter('clientToken');
    }

    public function setClientToken($value)
    {
        return $this->setParameter('clientToken', $value);
    }

    public function getClientSecret()
    {
        return $this->getParameter('clientSecret');
    }

    public function setClientSecret($value)
    {
        return $this->setParameter('clientSecret', $value);
    }

    public function getAuthToken()
    {
        return $this->getParameter('authToken');
    }

    public function setAuthToken($value)
    {
        return $this->setParameter('authToken', $value);
    }

    public function getAuthTokenExpires()
    {
        return $this->getParameter('authTokenExpires');
    }

    public function setAuthTokenExpires($value)
    {
        return $this->setParameter('authTokenExpires', $value);
    }

    // public function getReference()
    // {
    //     return $this->getParameter('reference');
    // }

    // public function setReference($value)
    // {
    //     return $this->setParameter('reference', $value);
    // }

    protected function getAuthorisationBasicPassword()
    {
        $clientToken = $this->getClientToken();
        $clientSecret = $this->getClientSecret();
        return base64_encode("{$clientToken}:{$clientSecret}");
    }
}
