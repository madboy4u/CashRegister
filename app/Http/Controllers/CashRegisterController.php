<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CashRegisterController extends Controller
{
    private $rowSales = [];
    private $priceList;

    public function __construct($type = 1) {

        // get price list
        if($type)
            $this->priceList = config('pricelist');

    }

    public function addItem($itemCode, $itemQuantity) {

        if ( !in_array($itemCode, array_keys($this->rowSales)) )
            $this->rowSales[$itemCode] = 0;

        // add Qta
        $this->rowSales[$itemCode] += $itemQuantity;

    }

    public function getRowSales() {
        return $this->rowSales;
    }

    public function printReceipt() {

        $total = 0;
        $discount_total = 0;
        $receipt = '';

        foreach ($this->rowSales as $itemCode => $itemQuantity) {

            $itemPrice = $this->priceList[$itemCode]['price'];

            $row_total = $this->priceList[$itemCode]['price'] * $itemQuantity;
            $row_discount = $this->getDiscountPrice($itemCode, $itemQuantity);
            $total += $row_total;
            $discount_total += $row_discount;

            $receipt .= $this->priceList[$itemCode]['name'] . str_repeat(' ', (30 - strlen($this->priceList[$itemCode]['name']))) .
                $itemQuantity . " " . $this->priceList[$itemCode]['unit'] . str_repeat(' ', (10 - strlen(strval($itemQuantity)))) .
                '€ ' . $row_total . str_repeat(' ', (10 - strlen(strval($row_total)))) .
                '€ ' . $row_discount . PHP_EOL;

        }
        $receipt .= PHP_EOL;
        $receipt .= 'Totale: ' . $total . PHP_EOL;
        $receipt .= 'Totale Sconto: ' . $discount_total . PHP_EOL;
        $receipt .= 'Totale a pagare: ' . $total - $discount_total . PHP_EOL;

        return $receipt;
    }

    public function getDiscountPrice($itemCode, $itemQuantity) {

        $itemTwinCode = $this->priceList[$itemCode]['discountItenTwin'];
        if( !strlen($itemTwinCode) )
            return 0;

        // if equal codes ==> discount for n couple of items
        if($itemCode == $itemTwinCode) {
            return floor($itemQuantity / 2) * ($this->priceList[$itemCode]['price'] - $this->priceList[$itemCode]['discountedPrice']);
        }
        else{
            return min($itemQuantity, $this->rowSales[$itemTwinCode]) * ($this->priceList[$itemCode]['price'] - $this->priceList[$itemCode]['discountedPrice']);
        }

        return 0;
    }

}
