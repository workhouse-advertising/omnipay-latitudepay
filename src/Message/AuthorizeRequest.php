<?php
namespace Omnipay\LatitudePay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\LatitudePay\ItemInterface;
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
    // public function getEndpoint()
    // {
    //     return parent::getEndpoint() . '/v3/token';
    // }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        $this->validate(
            'amount',
            'currency',
            'authToken',
        );

        $data = [
            'customer' => $this->getCustomer(),
            'shippingAddress' => $this->getShippingAddress(),
            'billingAddress' => $this->getBillingAddress(),
            'products' => $this->getProducts(),
            // TODO: Add support for shipping lines.
            // 'shippingLines' => $this->getShippingLines(),
            // TODO: Add support for `taxAmount`.
            // 'taxAmount' => [
            //     'amount' => 0,
            //     'currency' => $this->getCurrency(),
            // ],
            // TODO: Consider if we need a custom reference or if the transaction ID is fine to use.
            //       Alternatively, maybe check if a reference has been set and fall back to the transaction 
            //       ID if it hasn't.
            // 'reference' => $this->getReference(), 
            'reference' => $this->getTransactionId(),
            'totalAmount' => [
                'amount' => $this->getAmount(),
                'currency' => $this->getCurrency(),
            ],
            'returnUrls' => [
                'successUrl' => $this->getReturnUrl(),
                // TODO: Consider if the cancel URL is suitable for the fail URL.
                'failUrl' => $this->getCancelUrl(),
                // 'failUrl' => $this->getReturnUrl(),
                'callbackUrl' => $this->getNotifyUrl(),
            ], 
            // // NOTE: Have to cast to a float/double otherwise we receive an empty response from the gateway.
            // // TODO: Report a bug to Latitude Checkout and ensure that float casting won't cause rounding errors.
            // 'amount' => (float) $this->getAmount(),
            // 'currency' => $this->getCurrency(), 
            // 'promotionReference' => $this->getPromotionReference(),
            // 'merchantUrls' => [
            //     'cancel' => $this->getCancelUrl(), 
            //     'complete' => $this->getReturnUrl(),
            // ], 
            // // TODO: Add support for shipping amounts.
            // // 'totalShippingAmount' => 50.00,
            // // TODO: Add support for platformType and platformVersion changes.
            // //       These are just metadata references for the system that's integrating with Latitude Checkout.
            // // 'platformType' => 'omnipay', 
            // // 'platformVersion' => '1.0.0',
        ];

        return $data;
    }

    /**
     * Get the customer data.
     *
     * @return array
     */
    protected function getCustomer() : array
    {
        $customer = [];
        $card = $this->getCard();
        if ($card) {
            $customer = [
                'firstName' => $card->getFirstName(), 
                'surname' => $card->getLastName(), 
                // TODO: Ensure this is a mobile number or add another field perhaps?
                'mobileNumber' => $card->getPhone(), 
                'email' => $card->getEmail(),
                // TODO: Consider if we should use shipping or billing address here or make it configurable.
                'address' => $this->getShippingAddress(),
                // TODO: Add support for date of birth.
                'dateOfBirth' => null,
            ];
        }
        return $customer;
    }

    /**
     * Get the billing address data.
     *
     * @return array
     */
    protected function getBillingAddress()
    {
        $billingAddress = [];
        $card = $this->getCard();
        if ($card) {
            $billingAddress = [
                // 'name' => $card->getBillingName(), 
                'addressLine1' => $card->getBillingAddress1(), 
                'addressLine2' => $card->getBillingAddress2(), 
                'suburb' => $card->getBillingCity(), 
                // TODO: Consider if `cityTown` can just be the same as `suburb`.
                // 'cityTown' => null,
                'postcode' => $card->getBillingPostCode(), 
                'state' => $card->getBillingState(), 
                'countryCode' => $card->getBillingCountry(), 
                // 'phone' => $card->getBillingPhone(),
            ];
        }
        return $billingAddress;
    }

    /**
     * Get the shipping address data.
     *
     * @return array
     */
    protected function getShippingAddress()
    {
        $shippingAddress = [];
        $card = $this->getCard();
        if ($card) {
            $shippingAddress = [
                // 'name' => $card->getShippingName(), 
                'addressLine1' => $card->getShippingAddress1(), 
                'addressLine2' => $card->getShippingAddress2(), 
                'suburb' => $card->getShippingCity(), 
                // TODO: Consider if `cityTown` can just be the same as `suburb`.
                // 'cityTown' => null,
                'postcode' => $card->getShippingPostCode(), 
                'state' => $card->getShippingState(), 
                'countryCode' => $card->getShippingCountry(), 
                // 'phone' => $card->getShippingPhone(),
            ];
        }
        return $shippingAddress;
    }

    /**
     * Get the products to add to the request.
     *
     * @return array
     */
    protected function getProducts() : array
    {
        $orderLines = [];
        foreach ($this->getItems() as $item) {
            $orderLines[] = [
                'name' => $item->getName(), 
                'price' => [
                    'amount' => $item->getPrice(),
                    // TODO: Is it safe to assume that the currency is always the same for all products?
                    //       Or to LatitudePay potentially support mixed currencies?
                    //       I'm thinking it's safe to assume they are all the same currency, but need to confirm.
                    'currency' => $this->getCurrency(),
                ],
                'sku' => $item instanceof ItemInterface ? $item->getSku() : null, 
                'quantity' => $item->getQuantity(),
                'taxIncluded' => $item instanceof ItemInterface ? $item->getTaxIncluded() : false,
            ];
        }
        return $orderLines;
    }

    /**
     * @inheritDoc
     */
    public function sendData($data)
    {
        $headers = [
            'Content-Type' => "application/com.latitudepay.ecom-v3.1+json",
            'Accept' => 'application/com.latitudepay.ecom-v3.1+json',
            'Authorization' => "Bearer {$this->getAuthToken()}",
            // TODO: Add support for `X-Idempotency-Key`. Is this a per-request key? Or is it a transaction key?
            // 'X-Idempotency-Key' => $this->getTransactionId(),
        ];

        // try {
        //     $httpResponse = $this->httpClient->request('POST', $this->getEndpoint(), $headers, json_encode($data));
        // } catch (\Exception $e) {
        //     dd($e, $e->getRequest(), (string) $e->getRequest()->getBody());
        // }

        $queryParameters = [
            // TODO: Report an issue with the docs because it says that `Signature` starts with an upper case character.
            // 'Signature' => $this->getPayloadSignature($data),
            'signature' => $this->getPayloadSignature($data),
        ];

        $httpResponse = $this->httpClient->request('POST', $this->getEndpoint() . '/v3/sale/online?' . http_build_query($queryParameters), $headers, json_encode($data));
        $responseData = json_decode($httpResponse->getBody(), true);
        if ($httpResponse->getStatusCode() != 200) {

            // dd($httpResponse, (string) $httpResponse->getBody(), $responseData, $queryParameters, $this->getEndpoint() . '/v3/sale/online?' . http_build_query($queryParameters));

            // TODO: Consider filtering the response body in case it may have sensitive information in there.
            //       Although that _should_ never occur.
            // TODO: Consider adding support for accessing the errors in the body. Perhaps return an AuthorizeResponse with errors?
            //       Or maybe add a "debug" mode to this package?
            throw new InvalidRequestException("Invalid authorisation request to the LatitudePay API. Received status code '{$httpResponse->getStatusCode()}'.");
            // throw new InvalidRequestException("Invalid authorisation request to the LatitudePay API. Received status code '{$httpResponse->getStatusCode()}' and body: '{$httpResponse->getBody()}'.");
        }

        // dd($httpResponse, (string) $httpResponse->getBody(), $responseData);

        return new AuthorizeResponse($this, $responseData);
    }
}
