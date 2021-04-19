<?php

namespace Omnipay\LatitudePay\Message;

/**
 * Send the user to the Hosted Payment Page to authorize their payment.
 */

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class AuthorizeResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function isRedirect()
    {
        $data = $this->getData();
        return isset($data['paymentUrl']);
    }

    /**
     * @inheritDoc
     */
    public function getRedirectUrl()
    {
        $data = $this->getData();
        return $data['paymentUrl'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getTransactionReference()
    {
        $data = $this->getData();
        return $data['reference'] ?? null;
    }
}
