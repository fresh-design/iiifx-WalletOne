<?php

require_once( 'PaymentForm.php' );

use iiifx\Component\Payment\Webmoney\PaymentForm;

#
### Настройки платежа #########################################################
#

$testingMode = TRUE;

$sellerPurse = 'Z145179295679';
$orderId = 1000;

#
### Создание платежа ##########################################################
#

$webmoneyForm = new PaymentForm( $sellerPurse );

# Только для режима тестирования
if ( $testingMode ) {
    $webmoneyForm->setTestingMode( PaymentForm::TestingMode_AllSuccess );
}

# Можно пропустить
$webmoneyForm
    ->setSuccessMethod( PaymentForm::ResponseMethod_POST )
    ->setFailMethod( PaymentForm::ResponseMethod_POST );

# Настройка страниц
$webmoneyForm
    ->setResultLink( 'http://' )
    ->setSuccessLink( 'http://' )
    ->setFailLink( 'http://' );

# Направление на конкретный тип оплаты
$webmoneyForm->setAuthType( PaymentForm::AuthType_KeeperClassic );
//$webmoneyForm->setEInvoicingType( PaymentForm::EInvoicingType_Cards );

# Параметры оплаты
$webmoneyForm
    ->setPaymentAmount( 1.00 )
    ->setPaymentId( $orderId )
    ->setComment( "Оплата заказа #{$orderId}" );

# Если нужно передать свои данные
# Данные будут размещены в форме как CUSTOMER_{$valueName}
$webmoneyForm->addCustomerValue( 'orderId', $orderId ); # CUSTOMER_orderId

if ( $webmoneyForm->validateData() ) {

    # Задаем свой ID для формы, иначе будет сгенерирован случайный
    //$webmoneyForm->setFormTagId( 'webmoney-form' );

    # Включаем автоматическую отправку формы
    $webmoneyForm->enableFormAutoSubmit();

    # Получаем данные формы для отображения
    $formView = $webmoneyForm->buildFormView();

    # Отображаем форму пользователю
    echo $formView;

} else {
    # Ошибка данных
}


