<?php
/**
 * Обработчик формы: Остались вопросы / Обратный звонок
 *
 * Принимает: name, phone
 * Email получателей берётся из Customizer → Контакты → Email
 */

// Подключаем WordPress для доступа к get_theme_mod()
$wp_load = dirname( __FILE__ );
// Поднимаемся вверх пока не найдём wp-load.php
while ( ! file_exists( $wp_load . '/wp-load.php' ) ) {
    $parent = dirname( $wp_load );
    if ( $parent === $wp_load ) {
        die( 'wp-load.php не найден' );
    }
    $wp_load = $parent;
}
require_once $wp_load . '/wp-load.php';

session_start();

if ( ! $_POST ) {
    wp_safe_redirect( wp_get_referer() ?: home_url() );
    exit;
}

// ─── Email получателей из Customizer ─────────────────────────────
$from_domain = $_SERVER['HTTP_HOST'] ?? parse_url( home_url(), PHP_URL_HOST );
$subject     = 'Заявка на обратный звонок с сайта артастрой.рф';

// Дополнительные email из повторителя
$emails_extra = [];
$emails_json  = get_theme_mod( 'mytheme_emails_extra_json', '' );
$emails_data  = json_decode( $emails_json, true );
if ( is_array( $emails_data ) ) {
    foreach ( $emails_data as $item ) {
        if ( ! empty( $item['email'] ) && is_email( $item['email'] ) ) {
            $emails_extra[] = $item['email'];
        }
    }
}

// Собираем только дополнительные email из повторителя
$recipients = array_filter( $emails_extra );

$to_email = implode( ', ', $recipients );
// ─────────────────────────────────────────────────────────────────

// Санитизация входных данных
$name  = sanitize_text_field( $_POST['name']  ?? '' );
$phone = sanitize_text_field( $_POST['phone'] ?? '' );

$referer = wp_get_referer() ?: home_url();

// Проверка обязательного поля
if ( empty( $phone ) ) {
    $_SESSION['win']       = 1;
    $_SESSION['recaptcha'] = '<p class="text-light"><strong>Ошибка!</strong><br>Обязательное поле с номером телефона не заполнено! Пожалуйста, повторите попытку.</p>';
    wp_safe_redirect( $referer );
    exit;
}
// ─── Отправка Email ──────────────────────────────────────────────
$headers  = "MIME-Version: 1.0\r\n";
$from_name = 'артастрой.рф';
$headers .= "From: =?UTF-8?B?" . base64_encode($from_name) . "?= <noreply@{$from_domain}>\r\n";
$headers .= "Reply-To: noreply@{$from_domain}\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

$message = "Потенциальный клиент с именем <strong>{$name}</strong><br>просит перезвонить на номер <strong>{$phone}</strong>";

wp_mail( $to_email, $subject, $message, $headers );

// ─── Редирект с результатом ──────────────────────────────────────
$_SESSION['win']       = 1;
$_SESSION['recaptcha'] = '<p class="text-light">Спасибо за обращение в нашу компанию. Мы ответим Вам в&#160;ближайшее время.</p>';

wp_safe_redirect( $referer );
exit;