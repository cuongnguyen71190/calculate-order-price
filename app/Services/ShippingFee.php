<?php

namespace App\Services;

use App\Http\Controllers\OrderController;

class ShippingFee extends PriceService
{
    protected $productType;

    public function setAdditionalAttribute($name, $value)
    {
        $this->{$name} = $value;
        return $this;
    }

    public function getShippingFee()
    {
        // convert height to kg
        $feeByWeight = ($this->height / 1000) * env('WEIGHT_COEFFICIENT', 11);

        // convert dimension to m3
        $feeByDimension = ($this->width * $this->height * $this->depth / 1000000) * env('WEIGHT_COEFFICIENT', 11);
        $anotherShippingFee = $this->getAdditionalShippingFee();

        return max($feeByWeight, $feeByDimension, $anotherShippingFee);
    }

    public function getAdditionalShippingFee()
    {
        /**
         * @TODO for implement additional type of shipping fee
         */
        $feeByProductType = 0;
        if (!empty($this->productType) && $this->productType === OrderController::PRODUCT_TYPE_SPECIAL)
        {
            $feeByProductType = env('PRODUCT_TYPE_COEFFICIENT', 200);
        }

        return $feeByProductType;
    }
}
