<?php

namespace Omnipay\LatitudePay;

use Omnipay\Common\ItemInterface as BaseItemInterface;

interface ItemInterface extends BaseItemInterface
{
    public function getSku();

    public function getTaxIncluded();
}
