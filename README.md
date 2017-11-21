### WalletOne

Модуль оплаты WalletOne [ https://www.walletone.com ]

##### Подключение через Composer:

composer.json

    "require": {
        "fresh-design/iiifx-WalletOne": "dev-master"
    },
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/fresh-design/iiifx-WalletOne"
        }
    ]


##### Использование:

Отправка запроса с пользователем:

```php
use iiifx\Component\Payment\WalletOne\PaymentForm as WalletOneForm;

$sellerPurse = 123234345456;
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
    ->setSuccessLink( "http://site.me/shop/payment/success/{$orderId}" )
    ->setFailLink( "http://site.me/shop/payment/fail/{$orderId}" );

# Задаем разрешенные методы оплаты
if ( $paymentTypeList && is_array( $paymentTypeList ) ) {
    foreach ( $paymentTypeList as $paymentType ) {
        $w1Form->addPaymentType( $paymentType );
    }
}

# Создаем объект(ы) ККМ
$kkm = new KKM();

$kkm->setTitle($sendComment)
    ->setQuantity($quantity)
    ->setUnitPrice($paymentAmount)
    ->setSubTotal($paymentAmount)
    ->setTaxType(KKM::TAX1)
    ->setTax($taxCount);

# Проверяем на валидность и формируем массив с объектом(и)

if(!$kkmJson = KKM::arrayOfObjectsToJson([$kkm])) {
    throw new ErrorException(__('Проблема при формировании объекта  ККМ'));
}


# Параметры оплаты
$w1Form
    ->setPaymentAmount( $paymentAmount )
    ->setCurrencyCode( $currencyCode )
    ->setPaymentId( $orderId )
    ->setComment( "Оплата заказа #{$orderId}" )
    ->addCustomerValue( 'orderId', $orderId )
    //-> Добавляем поля для ККМ json-объект и номер телефона или email покупателя
    ->setOrderItems($kkmJson)
    ->setCustomerEmail($customerEmail)
    //->setCustomerEmail($customerPhone)

# Проверяем данные

if ( $w1Form->validateData() ) {

    # Получаем и сохраняем номер транзакции
    $transactionId = $w1Form->gerTransactionId();

    # Включаем автосабмит формы сразу после загрузки страницы
    $w1Form->enableFormAutoSubmit();

    # Выводим форму
    echo $w1Form->buildFormView();

}
```

Проверка результата оплаты Result

```php
use iiifx\Component\Payment\WalletOne\PaymentVerify as WalletOneVerify;

$sellerPurse = 123234345456;

# Получаем заранее сохраненный номер транзакции
$transactionId = 'WP-123-140820-142252-934';

$w1Verify = new WalletOneVerify();

# Загружаем данные
$w1Verify->loadFromPOST();

# Проверяем номер транзакции и статус оплаты
if ( $w1Verify->getTransactionId() === $transactionId && $w1Verify->isPaymentAccepted() ) {

    # Успешно
    echo 'WMI_RESULT=OK';

} else {
    # Ошибка
}
```