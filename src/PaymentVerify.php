<?php

namespace iiifx\Component\Payment\WalletOne;

/**
 * Class PaymentVerify
 *
 * @package iiifx\Component\Payment\WalletOne
 */
class PaymentVerify {

    use Helper\ResponseDataLoader;
    use Helper\ResponseCustomerFields;

    /**
     * @var array
     */
    private $fieldList = array (
        'WMI_MERCHANT_ID',
        'WMI_PAYMENT_AMOUNT',
        'WMI_CURRENCY_ID',
        'WMI_TO_USER_ID',
        'WMI_PAYMENT_NO',
        'WMI_ORDER_ID',
        'WMI_DESCRIPTION',
        'WMI_SUCCESS_URL',
        'WMI_FAIL_URL',
        'WMI_EXPIRED_DATE',
        'WMI_CREATE_DATE',
        'WMI_UPDATE_DATE',
        'WMI_ORDER_STATE', # Accepted
        'WMI_SIGNATURE'
    );

    /**
     * @var array
     */
    private $responseData;
    /**
     * @var string
     */
    private $sellerPurse;

    /**
     *
     */
    public function __construct ( ) {}

    /**
     * @return string
     */
    public function getTransactionId () {
        return $this->getResponseValue( 'WMI_PAYMENT_NO' );
    }

    /**
     * @return string
     */
    public function getOrderState () {
        return $this->getResponseValue( 'WMI_ORDER_STATE' );
    }

    /**
     * @return bool
     */
    public function isPaymentAccepted () {
        return( strtolower( $this->getOrderState() ) === 'accepted' );
    }

    /**
     * @return string
     */
    public function getPaymentAmount () {
        return (float) $this->getResponseValue( 'WMI_PAYMENT_AMOUNT' );
    }

    /**
     * @return string
     */
    public function getSellerPurse () {
        return $this->getResponseValue( 'WMI_MERCHANT_ID' );
    }

}