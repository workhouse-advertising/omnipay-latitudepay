<?php

namespace Omnipay\LatitudePay\Message;

use Omnipay\LatitudePay\Message\Response;

class CompleteAuthorizeResponse extends Response
{
    const RESULT_COMPLETE = 'COMPLETED';

    public function isSuccessful()
    {
        return ($this->getData()['result'] ?? null) === static::RESULT_COMPLETE;
    }

    // TODO: See about adding message handling.

    // TODO: Check available statuses and add checks here.

    // public function isPending()
    // {
    //     return false;
    // }

    // public function isCancelled()
    // {
    //     return false;
    // }

    public function getTransactionId()
    {
        return ($this->getData()['reference'] ?? null);
    }

    public function getTransactionReference()
    {
        return ($this->getData()['token'] ?? null);
    }
}
