<?php

namespace Omnipay\LatitudePay\Message;

/**
 * Send the user to the Hosted Payment Page to authorize their payment.
 */

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class AuthorizeResponse extends AbstractResponse implements RedirectResponseInterface
{
    protected $liveEndpoint = 'https://api.latitudepay.com';
    protected $testEndpoint = 'https://api.uat.latitudepay.com';

    /**
     * @inheritDoc
     */
    public function getRedirectUrl()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    // /**
    //  * Get the required redirect method (either GET or POST).
    //  *
    //  * @return string
    //  */
    // public function getRedirectMethod()
    // {
    //     return 'POST';
    // }

    /**
     * Does the response require a redirect?
     *
     * @return boolean
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * Gets the redirect form data array, if the redirect method is POST.
     *
     * @return array
     */
    public function getRedirectData()
    {
        return $this->getData();
    }

    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return false;
    }
}
