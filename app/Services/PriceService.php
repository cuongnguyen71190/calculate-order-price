<?php

namespace App\Services;

abstract class PriceService
{
    protected $price = 0;
    protected $weight = 0;
    protected $width = 0;
    protected $height = 0;
    protected $depth = 0;

    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;
        return $this;
    }

    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    public function setDepth($depth)
    {
        $this->depth = $depth;
        return $this;
    }

    public function calculateGrossPrice()
    {
        $shippingFee = $this->getShippingFee();

        return $this->price + $shippingFee;
    }

    abstract protected function getShippingFee();

    abstract protected function getAdditionalShippingFee();
}
