<?php

require_once( __DIR__ . '/../src/PaymentVerify.php' );

use iiifx\Component\Payment\WalletOne\PaymentVerify as WalletOneVerify;

$sellerPurse = 167849679901;

$transactionId = '...';

$w1Verify = new WalletOneVerify( $sellerPurse );

# Загружаем данные
$w1Verify->loadFromPOST();

# Проверяем номер транзакции и статус оплаты
if ( $w1Verify->getTransactionId() === $transactionId && $w1Verify->isPaymentAccepted() ) {
    # Успешно

    /**
     * Проверяем данные: сверяем номер заказа, сумму, записываем в логи
     *
     * Усли все в порядке - отдаем 'WMI_RESULT=OK'
     */

    echo 'WMI_RESULT=OK';

} else {
    # Ошибка, подпись не совпадает
}