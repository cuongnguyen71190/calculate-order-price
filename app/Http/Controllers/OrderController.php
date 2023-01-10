<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ShippingFee ;

class OrderController extends Controller
{
    const PRODUCT_TYPE_SPECIAL = 'SPECIAL';
    const PRODUCT_TYPE_NORMAL = 'NORMAL';

    private $shippingFee;

    public function __construct(ShippingFee $shippingFee)
    {
        $this->shippingFee = $shippingFee;
    }

    public function index()
    {
        $orderItems = $this->getNumberOfOrderItem();

        return view('index', compact('orderItems'));
    }

    public function calculate()
    {
        $grossPrice = 0;
        $orderItems = $this->getNumberOfOrderItem();
        if (!empty($orderItems))
        {
            foreach($orderItems as $orderItem)
            {
                $shippingFees = $this->shippingFee
                    ->setPrice($orderItem['amazon_price'])
                    ->setWeight($orderItem['product_weight'])
                    ->setWidth($orderItem['width'])
                    ->setHeight($orderItem['height'])
                    ->setDepth($orderItem['depth'])
                    ->setAdditionalAttribute('productType', $orderItem['product_type'])
                    ->calculateGrossPrice();

                $grossPrice += $shippingFees;
            }
        }

        return response()->json(['success' => 'true', 'data' => $grossPrice]);
    }

    private function getNumberOfOrderItem()
    {
        return [
            [
                'amazon_price' => 123,
                'product_weight' => 100,
                'width' => 10,
                'height' => 12,
                'depth' => 14,
                'product_type' => self::PRODUCT_TYPE_NORMAL
            ],
            [
                'amazon_price' => 456,
                'product_weight' => 200,
                'width' => 9,
                'height' => 10,
                'depth' => 20,
                'product_type' => self::PRODUCT_TYPE_NORMAL
            ],
            [
                'amazon_price' => 100,
                'product_weight' => 10,
                'width' => 1,
                'height' => 1,
                'depth' => 1,
                'product_type' => self::PRODUCT_TYPE_SPECIAL
            ],
        ];
    }
}
