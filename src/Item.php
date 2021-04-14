<?php

namespace Omnipay\LatitudePay;

use Omnipay\Common\Item as BaseItem;
use Omnipay\LatitudePay\ItemInterface;

class Item extends BaseItem implements ItemInterface
{
    /**
     * @inheritDoc
     */
    public function getSku()
    {
        return $this->getParameter('sku');
    }

    /**
     * Set the item sku
     */
    public function setSku($value)
    {
        return $this->setParameter('sku', $value);
    }

    /**
     * @inheritDoc
     */
    public function getTaxIncluded()
    {
        return $this->getParameter('taxIncluded');
    }

    /**
     * Set if tax is included.
     */
    public function setTaxIncluded($value)
    {
        return $this->setParameter('taxIncluded', $value);
    }
}