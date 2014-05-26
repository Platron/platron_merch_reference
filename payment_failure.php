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
?>
Ваш заказ <b>не</b> оплачен.
