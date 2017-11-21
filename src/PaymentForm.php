<?php

namespace iiifx\Component\Payment\WalletOne;

/**
 * Class PaymentForm
 *
 * @package iiifx\Component\Payment\WalletOne
 */
class PaymentForm {

    /**
     * @var string
     */
    private $gateLink = 'https://www.walletone.com/checkout/default.aspx';

    /**
     * @var string
     */
    private $sellerPurse;
    /**
     * @var string
     */
    private $formTagId;
    /**
     * @var bool
     */
    private $formAutoSubmit;
    /**
     * @var float
     */
    private $paymentAmount;
    /**
     * @var int
     */
    private $paymentId;
    /**
     * @var string
     */
    private $comment;
    /**
     * @var string
     */
    private $successLink;
    /**
     * @var string
     */
    private $failLink;
    /**
     * @var int
     */
    private $currencyCode;
    /**
     * @var string
     */
    private $transactionId;

    /**
     * @var array
     */
    private $customerValueList = array ();

    /**
     * @var array
     */
    private $paymentTypeList = array ();

    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var string
     */
    private $signatureMethod;

    /**
     * @var string|null
     */
    private $customerPhone = null;

    /**
     * @var string|null
     */
    private $customerEmail = null;

    /**
     * @var string
     */
    private $orderItems;

    /**
     * @param string $sellerPurse
     */
    public function __construct ( $sellerPurse ) {
        $this->setSellerPurse( $sellerPurse );
    }

    /**
     * @return string
     */
    public function getCustomerPhone()
    {
        return $this->customerPhone;
    }

    /**
     * @param string $customerPhone
     * @return PaymentForm
     */
    public function setCustomerPhone($customerPhone)
    {
        $this->customerPhone = $customerPhone;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerEmail()
    {
        return $this->customerEmail;
    }

    /**
     * @param string $customerEmail
     * @return PaymentForm
     */
    public function setCustomerEmail($customerEmail)
    {
        $this->customerEmail = $customerEmail;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecretKey() {
        return $this->secretKey;
    }

    /**
     * @param string $key
     *
     * @return $this
     */
    public function setSecretKey($key) {
        $this->secretKey = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getSignatureMethod() {
        return $this->signatureMethod;
    }

    /**
     * @param string $method
     *
     * @return $this
     */
    public function setSignatureMethod($method) {
        $this->signatureMethod = $method;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormActionLink () {
        return $this->gateLink;
    }

    /**
     * @param string $sellerPurse
     *
     * @return $this
     */
    public function setSellerPurse ( $sellerPurse ) {
        $this->sellerPurse = strtoupper( trim( $sellerPurse ) );
        return $this;
    }

    /**
     * @return string
     */
    public function getSellerPurse () {
        return $this->sellerPurse;
    }

    /**
     * @param string $failLink
     *
     * @return $this
     */
    public function setFailLink ( $failLink ) {
        $this->failLink = $failLink;
        return $this;
    }

    /**
     * @return string
     */
    public function getFailLink () {
        return $this->failLink;
    }

    /**
     * @param string $successLink
     *
     * @return $this
     */
    public function setSuccessLink ( $successLink ) {
        $this->successLink = $successLink;
        return $this;
    }

    /**
     * @return string
     */
    public function getSuccessLink () {
        return $this->successLink;
    }

    /**
     * @param  string$comment
     *
     * @return $this
     */
    public function setComment ( $comment ) {
        $this->comment = trim( $comment );
        return $this;
    }

    /**
     * @return string
     */
    public function getComment () {
        return $this->comment;
    }

    /**
     * @param float $paymentAmount
     *
     * @return $this
     */
    public function setPaymentAmount ( $paymentAmount ) {
        $this->paymentAmount = number_format( $paymentAmount, 2, '.', '' );
        return $this;
    }

    /**
     * @return float
     */
    public function getPaymentAmount () {
        return $this->paymentAmount;
    }

    /**
     * @param int $paymentId
     *
     * @return $this
     */
    public function setPaymentId ( $paymentId ) {
        $this->paymentId = $paymentId;
        return $this;
    }

    /**
     * @return int
     */
    public function getPaymentId () {
        return $this->paymentId;
    }

    /**
     * @return string
     */
    public function getTransactionId () {
        if ( is_null( $this->transactionId ) ) {
            $this->transactionId = 'W1-' . $this->paymentId . '-' . date( 'ymdHis' ) . str_replace( '.', '', (float) $this->getPaymentAmount() ) . rand( 100, 999 );
        }
        return $this->transactionId;
    }

    /**
     * @param int $code
     *
     * @return $this
     */
    public function setCurrencyCode ( $code ) {
        $this->currencyCode = (int) $code;
        return $this;
    }

    /**
     * @return int
     */
    public function getCurrencyCode () {
        return $this->currencyCode;
    }

    /**
     * @param string $formTagId
     *
     * @return $this
     */
    public function setFormTagId ( $formTagId ) {
        $this->formTagId = trim( $formTagId );
        return $this;
    }

    /**
     * @return string
     */
    public function getFormTagId () {
        if ( is_null( $this->formTagId ) ) {
            $this->formTagId = 'walletone-form-' . md5( microtime() );
        }
        return $this->formTagId;
    }

    /**
     * @return string
     */
    public function getBase64Comment () {
        if ( !is_null( $this->getComment() ) ) {
            return 'BASE64:' . base64_encode( $this->getComment() );
        }
        return '';
    }

    /**
     * @param string $valueName
     * @param string $valueData
     *
     * @return $this
     */
    public function addCustomerValue ( $valueName, $valueData = NULL ) {
        if ( is_array( $valueName ) ) {
            $this->addCustomerValues( $valueName );
        } else {
            if ( $valueData ) {
                $this->customerValueList[ trim( $valueName ) ] = $valueData;
            }
        }
        return $this;
    }

    /**
     * @param array $valueList
     *
     * @return $this
     */
    public function addCustomerValues ( $valueList ) {
        if ( $valueList && is_array( $valueList ) ) {
            foreach ( $valueList as $valueName => $valueData ) {
                $this->addCustomerValue( $valueName, $valueData );
            }
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderItems()
    {
        return $this->orderItems;
    }

    /**
     * @param $orderItems
     * @return $this
     */
    public function setOrderItems($orderItems)
    {
        $this->orderItems = $orderItems;
        return $this;
    }

    /**
     * @param string $paymentType
     *
     * @return $this
     */
    public function addPaymentType ( $paymentType ) {
        $this->paymentTypeList[] = (string) $paymentType;
        return $this;
    }

    /**
     * @return array
     */
    public function getPaymentTypeList () {
        return $this->paymentTypeList;
    }

    /**
     * @return $this
     */
    public function clearPaymentTypeList () {
        $this->paymentTypeList = array ();
        return $this;
    }

    /**
     * @return $this
     */
    public function clearCustomerValues () {
        $this->customerValueList = array ();
        return $this;
    }

    /**
     * @return array
     */
    public function getCustomerValues () {
        return $this->customerValueList;
    }

    /**
     * @return $this
     */
    public function enableFormAutoSubmit () {
        $this->formAutoSubmit = TRUE;
        return $this;
    }

    /**
     * @return $this
     */
    public function disableFormAutoSubmit () {
        $this->formAutoSubmit = FALSE;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabledFormAutoSubmit () {
        return !!$this->formAutoSubmit;
    }

    /**
     * @return bool
     */
    public function validateData () {
        if ( preg_match( '/^\d{10,16}$/iu', $this->getSellerPurse() ) !== 1 ) {
            return FALSE;
        }
        if ( $this->getPaymentAmount() <= 0  ) {
            return FALSE;
        }
        if ( !$this->getComment()) {
            return FALSE;
        }
        if ( !$this->getCurrencyCode() ) {
            return FALSE;
        }
        if ( $this->getSignatureMethod() and !$this->getSecretKey() ) {
            return FALSE;
        }

        if ( !$this->getCustomerEmail() && !$this->getCustomerPhone() ) {
            return FALSE;
        }

        if ( !$this->getOrderItems() ) {
            return FALSE;
        }
        
        return TRUE;
    }

    /**
     * @param string $encoding
     *
     * @return string
     */
    public function buildFormView ($encoding = 'UTF-8') {
        $formTagId = ( !is_null( $this->getFormTagId() ) ) ? "id=\"{$this->getFormTagId()}\"" : NULL;
        $fields = array();
        $fields['WMI_MERCHANT_ID'] = $this->getSellerPurse();
        $fields['WMI_PAYMENT_AMOUNT'] = $this->getPaymentAmount();
        $fields['WMI_CURRENCY_ID'] = $this->getCurrencyCode();
        $fields['WMI_DESCRIPTION'] = $this->getBase64Comment();

        if ( !is_null( $this->getPaymentId() ) ) {
            $fields['WMI_PAYMENT_NO'] = $this->getTransactionId();
        }

        if ( !is_null( $this->getSuccessLink() ) ) {
            $fields['WMI_SUCCESS_URL'] = $this->getSuccessLink();
        }

        if ( !is_null( $this->getFailLink() ) ) {
            $fields['WMI_FAIL_URL'] = $this->getFailLink();
        }

        if ( $this->getPaymentTypeList() ) {
            $fields['WMI_PTENABLED'] = $this->getPaymentTypeList();
        }

        if ( $this->getCustomerPhone() ) {
            $fields['WMI_CUSTOMER_PHONE'] = $this->getCustomerPhone();
        }

        if ( !$this->getCustomerPhone() && $this->getCustomerEmail() ) {
            $fields['WMI_CUSTOMER_EMAIL'] = $this->getCustomerEmail();
        }

        if ( $items = $this->getOrderItems() ) {
            $fields['WMI_ORDER_ITEMS'] = $items;
        }

        if ( $this->getCustomerValues() ) {
            foreach ( $this->getCustomerValues() as $name => $value ) {
                $fields['CUSTOMER_' . $name] = $value;
            }
        }

        /*
         * Сортировка значений внутри полей и составление сообщения
         */
        foreach($fields as $name => $val) {
            if (is_array($val)) {
                usort($val, "strcasecmp");
                $fields[$name] = $val;
            }
        }

        uksort($fields, "strcasecmp");
        $fieldValues = "";

        foreach($fields as $value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    if ($encoding != 'windows-1251') {
                        $v = iconv($encoding, "windows-1251", $v);
                    }
                    $fieldValues .= $v;
                }
            }
            else {
                if ($encoding != 'windows-1251') {
                    $value = iconv($encoding, "windows-1251", $value);
                }

                $fieldValues .= $value;
            }
        }

        if ($this->getSignatureMethod() AND $this->getSecretKey()) {
            if ($this->getSignatureMethod() == 'md5') {
                $signature = base64_encode(pack("H*", md5($fieldValues . $this->getSecretKey())));
                $fields["WMI_SIGNATURE"] = $signature;
            }
            elseif ($this->getSignatureMethod() == 'sha1') {
                $signature = base64_encode(pack("H*", sha1($fieldValues . $this->getSecretKey())));
                $fields["WMI_SIGNATURE"] = $signature;
            }

        }

        if ( $this->isEnabledFormAutoSubmit() ) {
            $submitScript = '<script type="text/javascript">document.getElementById( \'' . $this->getFormTagId() . '\' ).submit();</script>';
        } else {
            $submitScript = '';
        }

        $form = '<form method="POST" ' . $formTagId . ' action="' . $this->getFormActionLink() . '" accept-charset="UTF-8">';

        foreach($fields as $key => $val) {
            if (is_array($val)) {
                foreach ($val as $value) {
                    $form .= '<input type="hidden" name="' . $key . '" value="' . $value . '"/>';
                }
            }
            else {
                $form .= '<input type="hidden" name="' . $key . '" value="' . $val . '"/>';
            }
        }

        $form .= '</form>' . $submitScript;

        return $form;
    }

}