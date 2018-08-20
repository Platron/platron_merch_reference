<?php
require_once 'PG_Signature.php';

$MERCHANT_ID = 13; // Ваш идентификатор магазина merchant_id
$MERCHANT_SECRET_KEY = "secret_key"; // Ваш секретный ключ secret_key

if (!empty($_POST['platron'])) {
	$parameters = array();
	/* Обязательные параметры */
	$parameters['pg_merchant_id'] = $MERCHANT_ID; // Идентификатор магазина
	$parameters['pg_amount'] = $_REQUEST['amount']; // Сумма заказа
	$parameters['pg_description'] = $_REQUEST['description']; // Описание заказа (показывается в Платёжной системе)

	$parameters['pg_salt'] = rand(21,43433);
	$parameters['pg_sig'] = PG_Signature::make('payment.php', $parameters, $MERCHANT_SECRET_KEY);
	
	header("Location: https://www.platron.ru/payment.php?" . http_build_query($parameters));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Donation button example</title>
	<style>
		body {width: 800px;}
	</style>
</head>
<body>
	<h1>Пример формы для пожертвования</h1>
	<h2>Описание</h2>
	<p>
		Пример демонстрирует простой способ интеграции, используемый, например, для сбора пожертвований.
		Можно создать форму для ввода суммы и описания платежа и разместить такую форму на своем сайте.
		После ввода данных и нажатия кнопки "Оплатить" пользователь будет отправлен на сайт Плтарон на форму выбора платежной системы.		
	</p>
	<p>
		Для упрощения кода, в примере не производится проверка введенных пользователем данных.
	</p>
	<form name="platron_payment" method="POST">
		<table>
			<tr>
				<td>Описание: </td>
				<td><input type="text" name="description" placeholder="Пожертвование">
			</tr>
			<tr>
				<td>Сумма: </td> 
				<td><input type="text" name="amount" placeholder="100"></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" name="platron" value="Пожертвовать"><td>
			</tr>
		</table>
	</form>
</body>
</html>
