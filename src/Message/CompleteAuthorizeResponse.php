<?php

namespace Omnipay\LatitudePay\Message;

use Omnipay\LatitudePay\Message\Response;

class CompleteAuthorizeResponse extends Response
{
    const RESULT_COMPLETE = 'COMPLETED';

    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return ($this->getData()['result'] ?? null) === static::RESULT_COMPLETE;
    }

    /**
     * @inheritDoc
     */
    public function getTransactionId()
    {
        return ($this->getData()['reference'] ?? null);
    }

    /**
     * @inheritDoc
     */
    public function getTransactionReference()
    {
        return ($this->getData()['token'] ?? null);
    }

    /**
     * @inheritDoc
     */
    public function getMessage()
    {
        return $this->getData()['message'] ?? null;
    }
}
