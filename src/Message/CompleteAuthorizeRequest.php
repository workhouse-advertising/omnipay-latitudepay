<?php
namespace Omnipay\LatitudePay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\LatitudePay\Message\CompleteAuthorizeResponse;

class CompleteAuthorizeRequest extends AbstractRequest
{
    /**
     * @inheritDoc
     */
    public function getData()
    {
        return $this->httpRequest->query->all();
    }

    /**
     * @inheritDoc
     */
    public function sendData($data)
    {
        // Ensure that the supplied data is an array.
        $data = (array) $data;

        // Get the supplied signature and remove it from the array.
        $signature = $data['signature'] ?? null;
        if (isset($data['signature'])) unset($data['signature']);

        // NOTE: Cannot use http_build_query(...) as we need to remove only the query parameter delimiters.
        //       The documentation doesn't say anything about whether or not those characters need to be removed
        //       from any values though...
        $gluedString = '';
        foreach ($data as $key => $item) {
            $gluedString .= "{$key}{$item}";
        }

        // Strip white space.
        $gluedString = preg_replace('/\s*/', '', $gluedString);

        // Calculate the signature for this request.
        $calculatedSignature = hash_hmac('sha256', base64_encode($gluedString), $this->getClientSecret());
        
        // Ensure that the signature is valid.
        if (!$signature || $signature != $calculatedSignature) {
            throw new InvalidRequestException("Invalid signature in the response from LatitudePay.");
        }

        return new CompleteAuthorizeResponse($this, $data);
    }
}
