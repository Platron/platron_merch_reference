<?php
include("PG_Signature.php");

/*
 * Следующие параметры выдаются при подключении магазина к Platron
 */
$MERCHANT_ID = 13;
$MERCHANT_SECRET_KEY = "secret_word";

$arrReq = array();

/* Обязательные параметры */
$arrReq['pg_merchant_id'] = $MERCHANT_ID;// Идентификатор магазина
$arrReq['pg_order_id']    = "64";		// Идентификатор заказа в системе магазина
$arrReq['pg_amount']      = 500.54;		// Сумма заказа
$arrReq['pg_lifetime']    = 3600*24;	// Время жизни счёта (в секундах)
$arrReq['pg_description'] = "Описание"; // Описание заказа (показывается в Платёжной системе)

/*
 * Название ПС из справочника ПС. Задаётся, если не требуется выбор ПС. Если не задано, выбор будет
 * предложен пользователю на сайте platron.ru.
 */
//$arrReq['pg_payment_system'] = 'TEST';

/*
 * Нижеследующие параметры имеет смысл определять, только если они отличаются от заданных
 * в настройках магазина на сайте platron.ru (https://www.platron.ru/admin/merchant_settings.php)
 */
//$arrReq['pg_success_url'] = 'http://example.com/payment_ok.php';
//$arrReq['pg_success_url_method'] = 'AUTOGET';
//$arrReq['pg_failure_url'] = 'http://example.com/payment_failure.php';
//$arrReq['pg_failure_url_method'] = 'AUTOGET';

/* Параметры безопасности сообщения. Необходима генерация pg_salt и подписи сообщения. */
$arrReq['pg_salt'] = rand(21,43433);
$arrReq['pg_sig'] = PG_Signature::make('payment.php', $arrReq, $MERCHANT_SECRET_KEY);
$query = http_build_query($arrReq);

header("Location: https://www.platron.ru/payment.php?$query");

?>
