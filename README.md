### Webmoney

Модуль оплаты Webmoney

##### Подключение через Composer:

composer.json

    "require": {
        "fresh-design/iiifx-Webmoney": "dev-master"
    },
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/fresh-design/iiifx-Webmoney"
        }
    ]


##### Использование:

Отправка запроса с пользователем:

```php
    use iiifx\Component\Payment\Webmoney\PaymentForm;

    $webmoneyForm = new PaymentForm( $sellerPurse );

    # Только для режима тестирования
    if ( $testingMode ) {
        $webmoneyForm->setTestingMode( PaymentForm::TestingMode_AllSuccess );
    }

    # Настройка страниц, на которые будут отправлены ответы
    $webmoneyForm
        ->setResultLink( 'http://' )
        ->setSuccessLink( 'http://' )
        ->setFailLink( 'http://' );

    # Настройка типа ответов
    $webmoneyForm
        ->setSuccessMethod( PaymentForm::ResponseMethod_POST )
        ->setFailMethod( PaymentForm::ResponseMethod_POST );

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
```

Проверка результата оплаты Result

```php
    use iiifx\Component\Payment\Webmoney\PaymentVerify;

    $webmoneyWerify = new PaymentVerify( $sellerPurse, $sellerPassword );

    # Загружаем данные
    $webmoneyWerify->loadFromPOST();

    # Проверяем подпись
    if ( $webmoneyWerify->verifyResponseSignature() ) {
        # Успешно

        # Получаем данные оплаты
        $orderId = $webmoneyWerify->getPaymentId();
        # Или с переданных данных, если его передавали
        $orderId = $webmoneyWerify->getCustomerValue( 'orderId' );
        # ... и другое
        $invoiseId = $webmoneyWerify->getPaymentInvoiseId();
        $transferId = $webmoneyWerify->getPaymentTransferId();
        $transferDate = date( 'Y.m.d H', $webmoneyWerify->getTransferTimestamp() );
        $paymentAmount = $webmoneyWerify->getPaymentAmount();

        /**
         * Проверяем данные: сверяем номер заказа, сумму, записываем в логи
         *
         * Усли все в порядке - отдаем 'Yes'
         */

        echo 'Yes';

    } else {
        # Ошибка, подпись не совпадает
    }
```

Получаем ответ на страницах Success / Fail

```php
    use iiifx\Component\Payment\Webmoney\PaymentResponse;

    /*
    * !!! Нельзя подтверждать оплату заказа на основе этих данных !!!
    *
    * Эти данные имеют информационный характер и должны использоваться лишь для отображения
    */

    $webmoneyResponse = new PaymentResponse();

    # Загружаем данные
    $webmoneyResponse->loadFromPOST();

    # Получаем номер заказа
    $orderId = $webmoneyResponse->getPaymentId();
    # Или с переданных данных, если его передавали
    $orderId = $webmoneyResponse->getCustomerValue( 'orderId' );
    # Номер счета оплаты, если успешно завершилась
    $invoiseId = $webmoneyResponse->getPaymentInvoiseId();
    # Номер трансфера, если успешно завершилась
    $transferId = $webmoneyResponse->getPaymentTransferId();
    # Дату проведения оплаты, если успешно завершилась
    $transferDate = date( 'Y.m.d H', $webmoneyResponse->getTransferTimestamp() );
```