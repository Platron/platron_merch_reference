<?php

require_once 'PG_Signature.php';

$MERCHANT_ID = 13;
$MERCHANT_SECRET_KEY = "secret_key";

if(!empty($_POST['platron'])){
	$arrReq = array();

	/* Обязательные параметры */
	$arrReq['pg_merchant_id'] = $MERCHANT_ID;// Идентификатор магазина
	$arrReq['pg_amount']      = $_REQUEST['amount'];		// Сумма заказа
	$arrReq['pg_description'] = $_REQUEST['description']; // Описание заказа (показывается в Платёжной системе)

	$arrReq['pg_salt'] = rand(21,43433);
	$arrReq['pg_sig'] = PG_Signature::make('payment.php', $arrReq, $MERCHANT_SECRET_KEY);
	$query = http_build_query($arrReq);

	header("Location: https://www.platron.ru/payment.php?$query");
}

?>

<form name="platron_payment" method="POST">
	<table>
		<tr>
			<td>Описание: </td>
			<td><input type="text" name="description">
		</tr>
		<tr>
			<td>Сумма: </td> 
			<td><input type="text" name="amount"></td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="platron"><td>
		</tr>
	</table>
</form>
