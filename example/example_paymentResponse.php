<?php

require_once( __DIR__ . '/../src/PaymentVerify.php' );

use iiifx\Component\Payment\WalletOne\PaymentVerify as WalletOneVerify;

$sellerPurse = 167849679901;

$transactionId = '...';

$w1Verify = new WalletOneVerify();

# Загружаем данные
$w1Verify->loadFromPOST();

$orderId = $w1Verify->getCustomerValue( 'orderId' );

# Проверяем номер транзакции и статус
if ( $w1Verify->getTransactionId() === $transactionId && $w1Verify->isPaymentAccepted() ) {

    # А также сверяем номер заказа, сумму заказа и тд.

    # Успешно
    echo 'WMI_RESULT=OK';

} else {
    # Ошибка, подпись не совпадает
}