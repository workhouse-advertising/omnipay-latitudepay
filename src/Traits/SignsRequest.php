<?php

namespace Omnipay\LatitudePay\Traits;

trait SignsRequest
{
    protected $latitudePaySignatureGluedString = '';

    /**
     * NOTE: Adapted from PrestaShop module.
     *       See https://github.com/Latitude-Financial/prestashop1.7-latitudepay-genoapay/blob/52ffacf68285f8a4562eb88c29e738f6cfd3177c/includes/libs/Base.php
     *
     * @param [type] $payload
     * @return void
     */
    public function getPayloadSignature($payload)
    {
        $this->latitudePaySignatureGluedString = '';
        return hash_hmac('sha256', base64_encode($this->recursiveImplode($payload, '', true)), $this->getClientSecret());
    }

    /**
     * NOTE: From their PrestaShop module.
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
                $this->latitudePaySignatureGluedString .= $key . $glue;
            }
            // NOTE: Do _not_ use `JSON_UNESCAPED_SLASHES` as we _need_ to retain slashes if they are also present in the request.
            // TODO: Check if there's a default configuration that could break this and implement handling for that.
            // is_array($value) ? $this->recursiveImplode($value, $glue, $include_keys, $trim_all) : $this->latitudePaySignatureGluedString .= trim(json_encode($value, JSON_UNESCAPED_SLASHES), '"') . $glue;
            is_array($value) ? $this->recursiveImplode($value, $glue, $include_keys, $trim_all) : $this->latitudePaySignatureGluedString .= trim(json_encode($value), '"') . $glue;
        }
        // Removes last $glue from string
        strlen($glue) > 0 and $this->latitudePaySignatureGluedString = substr($this->latitudePaySignatureGluedString, 0, -strlen($glue));
        // Trim ALL whitespace
        $trim_all and $this->latitudePaySignatureGluedString = preg_replace("/(\s)/ixsm", '', $this->latitudePaySignatureGluedString);
        return (string)$this->latitudePaySignatureGluedString;
    }
}
