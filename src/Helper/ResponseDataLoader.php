<?php

namespace iiifx\Component\Payment\WalletOne\Helper;

/**
 * Class ResponseDataLoader
 * @package iiifx\Component\Payment\WalletOne\Helper
 *
 * @property array $fieldList;
 * @property array $responseData
 */
trait ResponseDataLoader {

    /**
     * @param array $arrayData
     *
     * @return $this
     */
    public function loadFromArray ( $arrayData ) {
        if ( $arrayData && is_array( $arrayData ) ) {
            foreach ( $this->fieldList as $fieldName => $fieldType ) {
                if ( isset( $arrayData[ $fieldName ] ) ) {
                    $fieldValue = $arrayData[ $fieldName ];
                    if ( $fieldType === 'int' ) {
                        $fieldValue = (int) $fieldValue;
                    }
                    $this->responseData[ $fieldName ] = $fieldValue;
                }
            }
            foreach ( $arrayData as $fieldName => $fieldValue ) {
                if ( preg_match( '/^CUSTOMER_/', $fieldName ) === 1 ) {
                    $this->responseData[ $fieldName ] = $fieldValue;
                }
            }
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function loadFromPOST () {
        $this->loadFromArray( $_POST );
        return $this;
    }

    /**
     * @param string $valueName
     *
     * @return string|null
     */
    public function getResponseValue ( $valueName ) {
        if ( $valueName && isset( $this->responseData[ $valueName ] ) ) {
            return $this->responseData[ $valueName ];
        }
        return NULL;
    }

}