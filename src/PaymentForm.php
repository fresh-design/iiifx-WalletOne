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
     * @param string $sellerPurse
     */
    public function __construct ( $sellerPurse ) {
        $this->setSellerPurse( $sellerPurse );
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
        $this->paymentId = (int) $paymentId;
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
            $this->transactionId = 'W1-' . $this->paymentId . '-' . date( 'ymdHis' ) . '-' . $this->getPaymentAmount() . '-' . rand( 100, 999 );
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
        if ( !$this->getComment() ) {
            return FALSE;
        }
        if ( !$this->getCurrencyCode() ) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * @return string
     */
    public function buildFormView () {
        $formTagId = ( !is_null( $this->getFormTagId() ) ) ? "id=\"{$this->getFormTagId()}\"" : NULL;
        $inputsData = <<<HTML
    <input type="hidden" name="WMI_MERCHANT_ID" value="{$this->getSellerPurse()}">
    <input type="hidden" name="WMI_PAYMENT_AMOUNT" value="{$this->getPaymentAmount()}">
    <input type="hidden" name="WMI_CURRENCY_ID" value="{$this->getCurrencyCode()}">
    <input type="hidden" name="WMI_DESCRIPTION" value="{$this->getBase64Comment()}">
HTML;
        if ( !is_null( $this->getPaymentId() ) ) {
            $inputsData .= <<<HTML
\n    <input type="hidden" name="WMI_PAYMENT_NO" value="{$this->getTransactionId()}">
HTML;
        }
        if ( !is_null( $this->getSuccessLink() ) ) {
            $inputsData .= <<<HTML
\n    <input type="hidden" name="WMI_SUCCESS_URL" value="{$this->getSuccessLink()}">
HTML;
        }
        if ( !is_null( $this->getFailLink() ) ) {
            $inputsData .= <<<HTML
\n    <input type="hidden" name="WMI_FAIL_URL" value="{$this->getFailLink()}">
HTML;
        }
        if ( $this->getPaymentTypeList() ) {
            foreach ( $this->getPaymentTypeList() as $paymentType ) {
                $inputsData .= <<<HTML
\n    <input type="hidden" name="WMI_PTENABLED" value="{$paymentType}"/>
HTML;
            }
        }
        if ( $this->getCustomerValues() ) {
            foreach ( $this->getCustomerValues() as $name => $value ) {
                $inputsData .= <<<HTML
\n    <input type="hidden" name="CUSTOMER_{$name}" value="{$value}">
HTML;
            }
        }
        if ( $this->isEnabledFormAutoSubmit() ) {
            $submitScript = <<<HTML
<script type="text/javascript">
    document.getElementById( '{$this->getFormTagId()}' ).submit();
</script>
HTML;
        } else {
            $submitScript = NULL;
        }
        $inputsData = ltrim( $inputsData, "\r\n" );
        return <<<HTML
<!--suppress ALL -->
<form method="POST" {$formTagId} action="{$this->getFormActionLink()}" accept-charset="UTF-8">
{$inputsData}
</form>
{$submitScript}
HTML;
    }

}