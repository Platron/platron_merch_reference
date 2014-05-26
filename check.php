<?php
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
/*
 * Здесь нужно выяснить, можно ли оплачивать заказ с номером $order_id
 */
$is_order_available = false;
$error_desc = "Товар не доступен";
// или
$is_order_available = true;


/*
 * Формируем ответный XML
 * (Это можно делать вручную, как в данном примере, или используя SimpleXML, как в примере result.php)
 */
$arrResp['pg_salt']              = $arrParams['pg_salt']; // в ответе необходимо указывать тот же pg_salt, что и в запросе
$arrResp['pg_status']            = $is_order_available ? 'ok' : 'error';
$arrResp['pg_error_description'] = $is_order_available ?  ""  : $error_desc;
$arrResp['pg_sig'] = PG_Signature::make($thisScriptName, $arrResp, $MERCHANT_SECRET_KEY);

header('Content-type: text/xml');
print '<?xml version="1.0" encoding="utf-8"?>';
?>
<response>
    <pg_salt><?=$arrResp['pg_salt']?></pg_salt>
    <pg_status><?=$arrResp['pg_status']?></pg_status>
    <pg_error_description><?=htmlentities($arrResp['pg_error_description'])?></pg_error_description>
    <pg_sig><?=$arrResp['pg_sig']?></pg_sig>
</response>
