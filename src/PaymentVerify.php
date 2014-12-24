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
        return ( strtolower( $this->getOrderState() ) == 'accepted' );
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

    /**
     * @param string   $signatureMethod
     * @param string   $secret
     * @param string   $encoding
     *
     * @return bool
     *
     * На данный момент у единой кассы заметен глюк: при отправке WMI_DESCRIPTION, содержащем двойные кавычки в теле обратно
     * придет урезанный до этих кавычек WMI_DESCRIPTION, однако хэш будет посчитан по необрезанному параметру.
     * Сейчас если хотите использовать подпись, то кавычек можно лишь избегать.
     * Если это поправят - отпишите мне и уберите этот комментарий коммитом, буду признателен.
     *
     */
    public function checkSignature($signatureMethod = NULL, $secret = NULL, $encoding = 'UTF-8') {

        if (is_null($signatureMethod)) {
            return TRUE;
        }

        if (($signatureMethod == 'md5' OR $signatureMethod == 'sha1') AND !is_null($secret)) {

            $fields = $_POST;
            if (!is_array($fields) or !isset($fields) or is_null($fields)) {
                return FALSE;
            }

            foreach($fields as $name => $value)
            {
                if ($name != "WMI_SIGNATURE") $params[$name] = $value;
            }

            uksort($params, "strcasecmp"); $values = "";

            foreach($params as $name => $value)
            {
                if ($encoding != 'windows-1251') {

                    /*$value = mb_convert_encoding($value, 'windows-1251');*/

                }
                $values .= $value;
            }

            // Формирование подписи для сравнения ее с параметром WMI_SIGNATURE

            $signature = '';

            if ($signatureMethod == 'md5') {
                $signature = base64_encode(pack("H*", md5($values . $secret)));
            }
            if ($signatureMethod == 'sha1') {
                $signature = base64_encode(pack("H*", sha1($values . $secret)));
            }

            if ($signature == $fields["WMI_SIGNATURE"])
            {
                return TRUE;
            }
        }
        return FALSE;
    }


}