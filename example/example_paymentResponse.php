<?php

require_once( __DIR__ . '/../src/Helper/ResponseCustomerFields.php' );
require_once( __DIR__ . '/../src/Helper/ResponseDataLoader.php' );
require_once( __DIR__ . '/../src/PaymentVerify.php' );

use iiifx\Component\Payment\WalletOne\PaymentVerify as WalletOneVerify;

$sellerPurse = 167849679901;

# Секретный ключ

$secret = '...';

$w1Verify = new WalletOneVerify();

# Загружаем данные
$w1Verify->loadFromPOST();

$orderId = $w1Verify->getCustomerValue( 'orderId' );
$w1Verify->loadFromPOST();

# Проверяем подпись и статус
if ( $w1Verify->checkSignature('sha1', $secret) && $w1Verify->isPaymentAccepted() ) {

    # А также сверяем номер заказа, сумму заказа и тд.

    $transactionId = $w1Verify->getTransactionId();

    # Успешно
    echo 'WMI_RESULT=OK';

} else {
    # Ошибка, подпись не совпадает
}