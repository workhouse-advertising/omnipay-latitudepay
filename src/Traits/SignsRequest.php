<?php

namespace Omnipay\LatitudePay\Traits;

trait SignsRequest
{
    protected $stupidGluedString = '';

    /**
     * NOTE: Adapted from Magento 2 extension.
     *       See https://github.com/Latitude-Financial/magento2-latitudepay-genoapay/blob/295d67345a24eba7d3ac769a38c0d111adacc685/Helper/Curl.php
     *
     * @param [type] $payload
     * @return void
     */
    public function getPayloadSignature($payload)
    {

        // NOTE: This is stupid and doesn't work. Neither the 

        // $salesStringStripped = $this->stripJsonFromSalesString(json_encode($payload, JSON_UNESCAPED_SLASHES));

        // $salesStringStripped = str_replace('"', '', $salesStringStripped);

        // $salesStringStrippedBase64encoded = $this->base64EncodeSalesString(trim($salesStringStripped));
        // dd(
        //     $payload,
        //     $salesStringStripped,
        //     $this->recursiveImplode($payload, '', true),
        //     $salesStringStrippedBase64encoded,
        //     base64_encode($this->recursiveImplode($payload, '', true)),
        //     hash_hmac('sha256', str_replace(' ', '', $salesStringStrippedBase64encoded), $this->getClientSecret()), 
        //     hash_hmac('sha256', base64_encode($this->recursiveImplode($payload, '', true)), $this->getClientSecret())
        // );

        $this->stupidGluedString = '';
        // dd($this->recursiveImplode($payload, '', true));
        return hash_hmac('sha256', base64_encode($this->recursiveImplode($payload, '', true)), $this->getClientSecret());


        // // TODO: Find documentation on how to generate a signature, as both of these methods appear to return different results...
        // // Magento way. 
        // $salesStringStripped = $this->stripJsonFromSalesString(json_encode($payload, JSON_UNESCAPED_SLASHES));
        // // TODO: Report bug? Seems that the "Magento" way may not be stripping everything out.
        // // $salesStringStripped = str_replace('"', '', $salesStringStripped);
        // $salesStringStrippedBase64encoded = $this->base64EncodeSalesString(trim($salesStringStripped));
        // // dd($salesStringStrippedBase64encoded, hash_hmac('sha256', str_replace(' ', '', $salesStringStrippedBase64encoded), $this->getClientSecret()));
        // return hash_hmac('sha256', str_replace(' ', '', $salesStringStrippedBase64encoded), $this->getClientSecret());
        // // WooCommerce way.
        // // $this->stupidGluedString = '';
        // // return hash_hmac('sha256', base64_encode($this->recursiveImplode((array) $payload, '', true)), $this->getClientSecret());

        // // return hash_hmac('sha256', base64_encode($this->recursiveImplode((array) $payload, '', true)), $this->getClientSecret());
        // // return hash_hmac('sha256', base64_encode($this->recursiveImplode((array) $payload, '', true)), $this->getAuthToken());
        // // return hash_hmac('sha256', base64_encode($this->recursiveImplode((array) $payload, '', true)), $this->getAuthorisationBasicPassword());
    }

    /**
     * NOTE: From their prestashop module.
     *       See https://github.com/Latitude-Financial/prestashop1.7-latitudepay-genoapay/blob/52ffacf68285f8a4562eb88c29e738f6cfd3177c/includes/libs/Base.php
     * 
     * Recursively implodes an array with optional key inclusion
     *
     * Example of $include_keys output: key, value, key, value, key, value
     *
     * @access  public
     * @param   array   $array         multi-dimensional array to recursively implode
     * @param   string  $glue          value that glues elements together
     * @param   bool    $include_keys  include keys before their values
     * @param   bool    $trim_all      trim ALL whitespace from string
     * @return  string  imploded array
     */
    public function recursiveImplode(array $array, $glue = ',', $include_keys = false, $trim_all = true)
    {
        foreach ($array as $key => $value) {
            if (is_string($key)) {
                $this->stupidGluedString .= $key . $glue;
            }
            is_array($value) ? $this->recursiveImplode($value, $glue, $include_keys, $trim_all) : $this->stupidGluedString .= trim(json_encode($value, JSON_UNESCAPED_SLASHES), '"') . $glue;
        }
        // Removes last $glue from string
        strlen($glue) > 0 and $this->stupidGluedString = substr($this->stupidGluedString, 0, -strlen($glue));
        // Trim ALL whitespace
        $trim_all and $this->stupidGluedString = preg_replace("/(\s)/ixsm", '', $this->stupidGluedString);
        return (string)$this->stupidGluedString;
    }

    // /**
    //  * NOTE: Adapted from https://github.com/Latitude-Financial/woocommerce-latitudepay-genoapay/blob/54f70516abf83272fcdf02c085e51f29e93a3d98/includes/Libs/Base.php
    //  * TODO: This doesn't appear to work.
    //  * 
    //  * 
    //  * Recursively implodes an array with optional key inclusion
    //  *
    //  * Example of $include_keys output: key, value, key, value, key, value
    //  *
    //  * @access  public
    //  * @param   array   $array         multi-dimensional array to recursively implode
    //  * @param   string  $glue          value that glues elements together
    //  * @param   bool    $include_keys  include keys before their values
    //  * @param   bool    $trim_all      trim ALL whitespace from string
    //  * @return  string  imploded array
    //  */
    // public function recursiveImplode(array $array, $glue = ',', $include_keys = false, $trim_all = true)
    // {
    //     // $this->stupidGluedString = '';
    //     foreach ($array as $key => $value) {
    //         if (is_string($key)) {
    //             $this->stupidGluedString .= $key . $glue;
    //         }
    //         is_array($value) ? $this->recursiveImplode($value, $glue, $include_keys, $trim_all) : $this->stupidGluedString .= trim(json_encode($value, JSON_UNESCAPED_SLASHES), '"') . $glue;
    //     }
    //     // Removes last $glue from string
    //     strlen($glue) > 0 and $this->stupidGluedString = substr($this->stupidGluedString, 0, -strlen($glue));
    //     // Trim ALL whitespace
    //     $trim_all and $this->stupidGluedString = preg_replace("/(\s)/ixsm", '', $this->stupidGluedString);
    //     return (string) $this->stupidGluedString;
    // }

    // /**
    //  * Strip out the json formatting, leaving only the name and values
    //  *
    //  * @param array $requestBody
    //  * @return mixed
    //  */
    // public function stripJsonFromSalesString($requestBody)
    // {
    //     $pattern = '/{"|":{"|","|":"|"},"|}],"|":|\[{"|"}}],"|}}|"}]"|},|,"|"}}|"}/';
    //     $replacement = '';

    //     $removeJsonFormatting = preg_replace($pattern, $replacement, $requestBody);
    //     $removeAllSpace = str_replace(' ', '', $removeJsonFormatting);
    //     $JSONStringWithoutFormatting = $removeAllSpace;

    //     return $JSONStringWithoutFormatting;
    // }

    // /**
    //  * Return a base 64 encoded string
    //  *
    //  * @param string $salesStringStripped
    //  * @return string
    //  */
    // public function base64EncodeSalesString($salesStringStripped)
    // {
    //     return base64_encode(str_replace(' ', '', $salesStringStripped));
    // }

    // /**
    //  * Get the signatiure hash for sending to latitude/GenoaPay
    //  *
    //  * @param string $salesStringStrippedBase64encoded
    //  * @param int $storeId
    //  * @param string $methodCode
    //  * @return string
    //  */
    // public function getSignatureHash($salesStringStrippedBase64encoded,$storeId= null,$methodCode = null)
    // {
    //     try {
    //         $clientSecret = $this->encryptor->decrypt($this->configHelper->getConfigData('client_secret',$storeId,$methodCode));
    //         return hash_hmac('sha256', str_replace(' ', '', $salesStringStrippedBase64encoded), $clientSecret);
    //     } catch (\Exception $e) {
    //         $this->customLogger->critical($e->getMessage());
    //     }
    //     return  null;
    // }
}
