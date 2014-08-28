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
     * @param string $sellerPurse
     */
    public function __construct ( $sellerPurse ) {
        $this->setSellerPurse( $sellerPurse );
    }

    /**
     * @param string $sellerPurse
     *
     * @return $this
     */
    public function setSellerPurse ( $sellerPurse ) {
        $this->sellerPurse = trim( $sellerPurse );
        return $this;
    }

    /**
     * @return string
     */
    public function getSellerPurse () {
        return $this->sellerPurse;
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
        return( strtolower( $this->getOrderState() ) === 'accepted' );
    }


    /*
    public function getPaymentId () {
        return $this->getResponseValue( 'LMI_PAYMENT_NO' );
    }

    public function getPaymentInvoiseId () {
        return $this->getResponseValue( 'LMI_SYS_INVS_NO' );
    }

    public function getPaymentTransferId () {
        return $this->getResponseValue( 'LMI_SYS_TRANS_NO' );
    }

    public function getTransferDate () {
        return $this->getResponseValue( 'LMI_SYS_TRANS_DATE' );
    }

    public function getTransferTimestamp () {
        if ( $this->getTransferDate() ) {
            return strtotime( $this->getTransferDate() );
        }
        return NULL;
    }

    public function getPayerPurse () {
        return $this->getResponseValue( 'LMI_PAYER_PURSE' );
    }

    public function getPayerWM () {
        return $this->getResponseValue( 'LMI_PAYER_WM' );
    }

    public function getPaymentAmount () {
        return $this->getResponseValue( 'LMI_PAYMENT_AMOUNT' );
    }

    public function buildSignatureString () {
        return new \Exception(); # TODO Нужно реализовать создание подписи
    }

    public function buildControlSignature () {
        return strtoupper( md5( $this->buildSignatureString() ) );
    }

    public function verifyResponseSignature () {
        return ( $this->getResponseValue( 'WMI_SIGNATURE' ) === $this->buildControlSignature() );
    }
    */

}