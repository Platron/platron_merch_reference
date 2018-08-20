<?php
require_once 'PG_Signature.php';

$MERCHANT_ID = 13; // Ваш идентификатор магазина merchant_id
$MERCHANT_SECRET_KEY = "secret_key"; // Ваш секретный ключ secret_key

// Обработка однократной оплаты
if (!empty($_POST['simple'])) {
	$parameters = array();
	$parameters['pg_merchant_id'] = $MERCHANT_ID; // Идентификатор магазина
	$parameters['pg_testing_mode'] = '1'; // Включает тестовый режим, используется для тестирования
	$parameters['pg_amount'] = $_REQUEST['amount']; // Сумма заказа
	$parameters['pg_description'] = $_REQUEST['description']; // Описание заказа (показывается в Платёжной системе)

	$parameters['pg_salt'] = rand(21,43433);
	$parameters['pg_sig'] = PG_Signature::make('payment.php', $parameters, $MERCHANT_SECRET_KEY);

	header("Location: https://www.platron.ru/payment.php?" . http_build_query($parameters));
}

// Обработка регулярной оплаты
if (!empty($_POST['regular'])) {
	$parameters = array();
	$parameters['pg_merchant_id'] = $MERCHANT_ID; // Идентификатор магазина
	$parameters['pg_testing_mode'] = '1'; // Включает тестовый режим, используется для тестирования
	$parameters['pg_amount'] = $_REQUEST['amount']; // Сумма заказа
	$parameters['pg_description'] = $_REQUEST['description']; // Описание заказа (показывается в Платёжной системе)

	if (isset($_POST['regular_type'])) {
		$parameters['pg_payment_system'] = 'TESTCARD'; // Тестовая платежная система поддерживающая рекуррентные платежи
		$parameters['pg_recurring_start'] = '1'; // Включает создание рекуррентного профиля
		$parameters['pg_schedule']['pg_amount'] = $_REQUEST['amount']; // Сумма автоматического платежа
		if ($_POST['regular_type'] === 'per_two_week') {
			$parameters['pg_schedule']['pg_template'] = array(
				'pg_start_date' => (new DateTime('+1 week'))->format('Y-m-d H:i:s'), // Дата первого автоматического платежа
				'pg_interval' => 'week', // Интервал автоматического платежа
				'pg_period' => '2', // Период автоматического платежа, используется с pg_interval. 2 week означает 1 раз в 2 недели
				'pg_max_periods' => '4', // Максимальное число автоматических платежей
			);
		} elseif ($_POST['regular_type'] === 'per_month') {
			$parameters['pg_schedule']['pg_template'] = array(
				'pg_start_date' => (new DateTime('+1 month'))->format('Y-m-d H:i:s'), // Дата первого автоматического платежа
				'pg_interval' => 'month', // Интервал автоматического платежа
				'pg_period' => '1', // Период автоматического платежа, используется с pg_interval. 1 month означает 1 раз в месяц
				// Так как pg_max_periods не указан, автоматические платежи будут совершаться пока действителен рекуррентный профиль
			);
		}
	}

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
	<section>
		<h3>Форма пожертвования</h3>
		<form name="platron_payment" method="post">
			<table>
				<tr>
					<td>Описание: </td>
					<td><input type="text" name="description" placeholder="Пожертвование"></td>
				</tr>
				<tr>
					<td>Сумма: </td> 
					<td><input type="text" name="amount" placeholder="100"></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" name="simple" value="Пожертвовать"><td>
				</tr>
			</table>
		</form>
	</section>
	<p>
		Помимо однократной оплаты, есть возможность создать автоматический регулярный платеж.
		Регулярный платеж поддерживают только банковские платежные системы.
	</p>
	<section>
		<h3>Регулярное пожертвование</h3>
		<form name="platron_regular_payment" method="post">
			<table>
				<tr>
					<td>Описание: </td>
					<td><input type="text" name="description" placeholder="Пожертвование"></td>
				</tr>
				<tr>
					<td>Сумма: </td> 
					<td><input type="text" name="amount" placeholder="100"></td>
				</tr>
				<tr>
					<td>Четыре платежа раз в две недели</td>
					<td><input type="radio" name="regular_type" value="per_two_week" checked></td>
				</tr>
				<tr>
					<td>Патеж каждый месяц</td>
					<td><input type="radio" name="regular_type" value="per_month"></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" name="regular" value="Пожертвовать"><td>
				</tr>
			</table>
		</form>
	</section>
</body>
</html>
