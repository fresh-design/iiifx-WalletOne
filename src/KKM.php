<?php
/**
 * Created by PhpStorm.
 * User: ilya@shapkin.org
 * Date: 21/11/2017
 * Time: 18:26
 */

namespace iiifx\Component\Payment\WalletOne;

class KKM
{
    const PRICE_PATTERN =  '/^\d+(?:\.\d{2})?$/';
    const QUANTITY_PATTERN =  '/^\d+(?:\.\d{3})?$/';

    /**
     * tax_ru_1 —без НДС;
     * tax_ru_2 — НДС по ставке 0%;
     * tax_ru_3 — НДС чека по ставке 10%;
     * tax_ru_4 —НДС чека по ставке 18%;
     * tax_ru_5 — НДС чека по расчетной ставке 10/110;
     * tax_ru_6 — НДС чека по расчетной ставке 18/118.
     */
    const TAX1 = 'tax_ru_1';
    const TAX2 = 'tax_ru_2';
    const TAX3 = 'tax_ru_3';
    const TAX4 = 'tax_ru_4';
    const TAX5 = 'tax_ru_5';
    const TAX6 = 'tax_ru_6';

    /**
     * @var string
     */
    private $title;

    /**
     * @var float
     */
    private $quantity;

    /**
     * @var float
     */
    private $unitPrice;

    /**
     * @var float
     */
    private $subTotal;

    /**
     * @var string
     */
    private $taxType;

    /**
     * @var float
     */
    private $tax;

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return KKM
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     * @return KKM
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @param mixed $unitPrice
     * @return KKM
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubTotal()
    {
        return $this->subTotal;
    }

    /**
     * @param mixed $subTotal
     * @return KKM
     */
    public function setSubTotal($subTotal)
    {
        $this->subTotal = $subTotal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTaxType()
    {
        return $this->taxType;
    }

    /**
     * @param mixed $taxType
     * @return KKM
     */
    public function setTaxType($taxType)
    {
        $this->taxType = $taxType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * @param mixed $tax
     * @return KKM
     */
    public function setTax($tax)
    {
        $this->tax = $tax;
        return $this;
    }

    /**
     * @param KKM $kkm
     * @return bool
     */
    public static function validateKKMObject(KKM $kkm) {
        if( !$kkm->getTitle() ) {
            return FALSE;
        }
        if( !$kkm->validateCountData($kkm->getQuantity()) ) {
            return FALSE;
        }
        if( !$kkm->validateDecimalData($kkm->getUnitPrice()) ) {
            return FALSE;
        }
        if( !$kkm->validateDecimalData($kkm->getSubTotal()) ) {
            return FALSE;
        }
        if( !$kkm->validateDecimalData($kkm->getTax()) ) {
            return FALSE;
        }
        if( !$kkm->getTaxType() ) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * @param array $orderItems
     * @return bool|string
     */
    public static function arrayOfObjectsToJson(array $orderItems) {
        $items = [];
        /** @var self $orderItem */
        foreach ($orderItems as $orderItem) {

            if(!$orderItem instanceof KKM) {
                return FALSE;
            }

            if(!self::validateKKMObject($orderItem)) {
                return FALSE;
            }

            $items[] = [
                'Title'     => $orderItem->getTitle(),
                'Quantity'  => $orderItem->getQuantity(),
                'UnitPrice' => $orderItem->getUnitPrice(),
                'SubTotal'  => $orderItem->getSubTotal(),
                'TaxType'   => $orderItem->getTaxType(),
                'Tax'       => $orderItem->getTax()
            ];
        }
        return json_encode($items, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param $decimal
     * @return bool
     */
    protected function validateDecimalData($decimal) {
        return $decimal && preg_match(self::PRICE_PATTERN, $decimal);
    }

    /**
     * @param $decimal
     * @return bool
     */
    protected function validateCountData($decimal) {
        return $decimal && preg_match(self::QUANTITY_PATTERN, $decimal);
    }
}