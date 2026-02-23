<?php
	session_start();
	$win = "true";
	// Если существует переменная POST, то
	
	if($_POST){
		// Отправляем данные в Google
		function getCaptcha($SecretKey){
			$Response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LdV1IcUAAAAABnQ0mXIp5Yh7tLEcAXzdqG6rx9Y&response={$SecretKey}");
			$Return = json_decode($Response);
			return $Return;
		}
		
		/* Принимаем данные обратно */
		$Return = getCaptcha($_POST['g-recaptcha-response']);
		// Если вероятность робота более 0.5, то считаем отправителя человеком и выполняем отправку почты
		if( $Return->success == true && $Return->score > .25 ){
			
			$name = $_POST['name'];	
			$phone = $_POST['phone'];
			$mes = $_POST['mes'];
			
			/* Проверям что заполнено поле с телефоном */
			if ( $_POST['phone'] ) {
				$headers = "MIME-Version: 1.0\r\n";
				$headers .= "From: esenin-group.ru\r\n";
				$headers .= "Content-type: text/html; charset=utf-8\r\n";
				
				// Сообщение на email
				$message = "Клиент: " . $name ."<br>";
				$message .= "Телефон: " . $phone ."<br>";
				$message .= "Сообщение: " . $mes ."<br>";
				
				// Сообщение в Телегу
				$messageInTelegram = "Вопрос директору.%0A";
				$messageInTelegram .= "Клиент: " . $name ."%0A";
				$messageInTelegram .= "Телефон: " . $phone ."%0A";
				$messageInTelegram .= "Сообщение: " . $mes;
				
				// Если поле с телефоно заполненно
				mail( "info@yesenin-group.ru, new-lid@yandex.ru", "Вопрос директору с сайта esenin-group.ru", $message, $headers );
				
				$token = "6774093854:AAGwZe0zhsp-aVA1fZI6S21cwBJnM8o3myA";
				$chat_id = "-4095379999";
				
				$sendToTelegram = fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$messageInTelegram}","r");
				
				$_SESSION['win'] = 1;
				$_SESSION['recaptcha'] = '<p class="text-light">Спасибо за Ваше обращение. Александр ответит Вам в&#160;ближайшее время.</p>';
				header("Location: ".$_SERVER['HTTP_REFERER']);
			} else {
				// Если поле с телефоно НЕ заполненно
				$_SESSION['win'] = 1;
				$_SESSION['recaptcha'] = '<p class="text-light">Обязательное поле с номером телефона не заполненно! Пожалуйста, повторите попытку и заполенте поле с номером телефона.</p>';
				header("Location: ".$_SERVER['HTTP_REFERER']);
			}
		} else {
			// Иначе считаем отправителя роботом и выводим сообщение с просьбой повторить попытку
			$_SESSION['win'] = 1;
			$_SESSION['recaptcha'] = '<p class="text-light"><strong>Извините!</strong><br>Ваши действия похожи на робота. Пожалуйста повторите попытку!</p>';
			header("Location: ".$_SERVER['HTTP_REFERER']);
		}
	}
?>