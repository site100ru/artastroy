<?php
	session_start();
	$win = "true";
	// Если существует переменная POST, то
	
	/*
	if($_POST){
		// Отправляем данные в Google
		function getCaptcha($SecretKey){
			$Response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LdV1IcUAAAAABnQ0mXIp5Yh7tLEcAXzdqG6rx9Y&response={$SecretKey}");
			$Return = json_decode($Response);
			return $Return;
		}
		
		print_r( $Return );
		
		/* Принимаем данные обратно
		$Return = getCaptcha($_POST['g-recaptcha-response']);
		// Если вероятность робота более 0.5, то считаем отправителя человеком и выполняем отправку почты
		if( $Return->success == true && $Return->score > 1 ){ */
			
			$name = $_POST['name'];	
			$phone = $_POST['phone'];
			$answer_1 = $_POST['answer-1'];
			$answer_2 = $_POST['answer-2'];
			$answer_3 = $_POST['answer-3'];	
			$answer_4 = $_POST['answer-4'];	
			$answer_5 = $_POST['answer-5'];	
			$answer_6 = $_POST['answer-6'];
			
			/* Проверям что заполнено поле с телефоном */
			if ( $_POST['phone'] AND $_POST['answer-6'] ) {
				$headers = "MIME-Version: 1.0\r\n";
				$headers .= "From: esenin-group.ru\r\n";
				$headers .= "Content-type: text/html; charset=utf-8\r\n";
				
				// Сообщение на email
				$message = "Клиент: "     . $name . "<br>";
				$message .= "Телефон: "   . $phone . "<br>";
				$message .= "Этажей: "    . $answer_1 . "<br>";
				$message .= "Площадь: "   . $answer_2 . "<br>";
				$message .= "Фундамент: " . $answer_3 . "<br>";
				$message .= "Кровля: "    . $answer_4 . "<br>";
				$message .= "Бюджет: "    . $answer_5 . "<br>";
				$message .= "Сроки: "     . $answer_6;
				
				// Сообщение в Телегу
				$messageInTelegram = "Зявка на расчет сметы.%0A";
				$messageInTelegram .= "Клиент: "     . $name . "%0A";
				$messageInTelegram .= "Телефон: "   . $phone . "%0A";
				$messageInTelegram .= "Этажей: "    . $answer_1 . "%0A";
				$messageInTelegram .= "Площадь: "   . $answer_2 . "%0A";
				$messageInTelegram .= "Фундамент: " . $answer_3 . "%0A";
				$messageInTelegram .= "Кровля: "    . $answer_4 . "%0A";
				$messageInTelegram .= "Бюджет: "    . $answer_5 . "%0A";
				$messageInTelegram .= "Сроки: "     . $answer_6;
				
				// Если поле с телефоно заполненно
				mail( "info@yesenin-group.ru, new-lid@yandex.ru", "Зявка на расчет сметы с сайта esenin-group.ru", $message, $headers );
				
				$token = "6774093854:AAGwZe0zhsp-aVA1fZI6S21cwBJnM8o3myA";
				$chat_id = "-4095379999";
				
				$sendToTelegram = fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$messageInTelegram}","r");
				
				$_SESSION['win'] = 1;
				$_SESSION['recaptcha'] = '<p class="text-light">Спасибо за обращение в строительную компанию «Есенин-групп». Мы ответим Вам в&#160;ближайшее время.</p>';
				header("Location: ".$_SERVER['HTTP_REFERER']);
			} else {
				// Если поле с телефоно НЕ заполненно
				$_SESSION['win'] = 1;
				$_SESSION['recaptcha'] = '<p class="text-light">Обязательное поле с номером телефона не заполненно! Пожалуйста, повторите попытку и заполенте поле с номером телефона.</p>';
				header("Location: ".$_SERVER['HTTP_REFERER']);
			}
			
		
		/*} else {
			// Иначе считаем отправителя роботом и выводим сообщение с просьбой повторить попытку
			$_SESSION['win'] = 1;
			$_SESSION['recaptcha'] = '<p class="text-light"><strong>Извините!</strong><br>Ваши действия похожи на робота. Пожалуйста повторите попытку!</p>';
			header("Location: ".$_SERVER['HTTP_REFERER']);
		}
	} */
?>