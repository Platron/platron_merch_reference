<?
include("PG_Signature.php");

/*
 * Секретный ключ магазина в системе Platron (выдается при подключении магазина к Platron)
 */
$MERCHANT_SECRET_KEY = "secret_word";


$arrParams = $_GET;
$thisScriptName = PG_Signature::getOurScriptName();

if ( !PG_Signature::check($arrParams['pg_sig'], $thisScriptName, $arrParams, $MERCHANT_SECRET_KEY) )
    die("Bad signature");


$order_id = $arrParams['pg_order_id'];
if ( $arrParams['pg_result'] == 1 ) {
	// обрабатываем случай успешной оплаты заказа с номером $order_id
}
else {
	// заказ с номером $order_id не будет оплачен.
}


/*
 * Формируем ответный XML
 * (Это можно делать вручную, как в примере check.php, или используя SimpleXML, как в данном примере)
 */
$xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><response/>');
$xml->addChild('pg_salt', $arrParams['pg_salt']); // в ответе необходимо указывать тот же pg_salt, что и в запросе
$xml->addChild('pg_status', 'ok');
$xml->addChild('pg_description', "Оплата принята");
$xml->addChild('pg_sig', PG_Signature::makeXML($thisScriptName, $xml, $MERCHANT_SECRET_KEY));

header('Content-type: text/xml');
print $xml->asXML();
