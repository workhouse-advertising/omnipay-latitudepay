<?php

namespace Omnipay\LatitudePay\Message;

use Omnipay\LatitudePay\Message\Response;

class CompleteAuthorizeResponse extends Response
{
    public function isSuccessful()
    {
        return false;
    }

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
        return null;
    }

    public function getTransactionReference()
    {
        return null;
    }
}
