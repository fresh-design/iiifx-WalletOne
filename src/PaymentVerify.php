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
        'WMI_MERCHANT_ID'    => 'string',
        'WMI_PAYMENT_AMOUNT' => 'string',
        'WMI_CURRENCY_ID'    => 'string',
        'WMI_TO_USER_ID'     => 'string',
        'WMI_PAYMENT_NO'     => 'string',
        'WMI_ORDER_ID'       => 'string',
        'WMI_DESCRIPTION'    => 'string',
        'WMI_SUCCESS_URL'    => 'string',
        'WMI_FAIL_URL'       => 'string',
        'WMI_EXPIRED_DATE'   => 'string',
        'WMI_CREATE_DATE'    => 'string',
        'WMI_UPDATE_DATE'    => 'string',
        'WMI_ORDER_STATE'    => 'string', # Accepted
        'WMI_SIGNATURE'      => 'string'
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
    public function __construct () {
    }

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
        return ( strtolower( $this->getOrderState() ) === 'accepted' );
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