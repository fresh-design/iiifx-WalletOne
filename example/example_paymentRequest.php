<?php

require_once( __DIR__ . '/../src/PaymentForm.php' );

use iiifx\Component\Payment\WalletOne\PaymentForm as WalletOneForm;

$sellerPurse = 167849679901;
$paymentAmount = 1.00;
$currencyCode = 643;
$orderId = 1000;
$paymentTypeList = array (
    'LiqPayMoneyRUB',
    'CreditCardRUB'
);

# Создаем форму
$w1Form = new WalletOneForm( $sellerPurse );

# Страницы на которые будут отправлены ответы
$w1Form
    ->setSuccessLink( "http://weplay.tv/all/shop_payment/success/{$orderId}/card" )
    ->setFailLink( "http://weplay.tv/all/shop_payment/fail/{$orderId}/card" );

# Задаем разрешенные методы оплаты
if ( $paymentTypeList && is_array( $paymentTypeList ) ) {
    foreach ( $paymentTypeList as $paymentType ) {
        $w1Form->addPaymentType( $paymentType );
    }
}

# Параметры оплаты
$w1Form
    ->setPaymentAmount( $paymentAmount )
    ->setCurrencyCode( $currencyCode )
    ->setPaymentId( $orderId )
    ->setComment( "Оплата заказа #{$orderId}" )
    ->addCustomerValue( 'orderId', $orderId );

# Проверяем данные
if ( $w1Form->validateData() ) {

    # Сохраняем номер транзакции
    $transactionId = $w1Form->getTransactionId();

    # Включаем автосабмит формы сразу после загрузки страницы
    $w1Form->enableFormAutoSubmit();
    # Выводим форму
    echo $w1Form->buildFormView();

}