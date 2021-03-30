<?php
namespace Omnipay\LatitudePay\Message;

use Omnipay\LatitudePay\ItemInterface;
use Omnipay\LatitudePay\ItemTypeInterface;
use Omnipay\LatitudePay\Message\AuthorizeResponse;

/**
 * Authorize Request
 *
 * @method Response send()
 */
class AuthorizeRequest extends AbstractRequest
{
    /**
     * @inheritDoc
     */
    public function getEndpoint($service = null)
    {
        // No endpoint as the response is a redirect.
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        $this->validate(
            'amount',
            'currency',
        );

        $data = $this->getBaseData();

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function sendData($data)
    {
        // The response is a redirect.
        return new AuthorizeResponse($this, $data);
    }
}
