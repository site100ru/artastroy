<?php
/**
 * Обработчик формы: Заказать товар (модальное окно)
 *
 * Принимает: name, phone, product_title
 * Email получателей берётся из Customizer → Контакты → Email
 */

// Подключаем WordPress для доступа к get_theme_mod()
$wp_load = dirname( __FILE__ );
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
$subject     = 'Заявка на товар с сайта ' . $from_domain;

// Основной email
$main_email = get_theme_mod( 'mytheme_email', '' );

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

// Собираем всех получателей
$recipients = array_filter( array_merge(
    $main_email ? [ $main_email ] : [],
    $emails_extra
) );

$to_email = implode( ', ', $recipients );
// ─────────────────────────────────────────────────────────────────

// Санитизация входных данных
$name          = sanitize_text_field( $_POST['name']          ?? '' );
$phone         = sanitize_text_field( $_POST['phone']         ?? '' );
$product_title = sanitize_text_field( $_POST['product_title'] ?? '' );

$referer = wp_get_referer() ?: home_url();

// Проверка обязательного поля
if ( empty( $phone ) ) {
    $_SESSION['form_status']  = 'error';
    $_SESSION['form_message'] = 'Обязательное поле с номером телефона не заполнено! Пожалуйста, повторите попытку.';
    wp_safe_redirect( $referer );
    exit;
}

// ─── Отправка Email ──────────────────────────────────────────────
$headers  = "MIME-Version: 1.0\r\n";
$headers .= "From: noreply@{$from_domain}\r\n";
$headers .= "Reply-To: noreply@{$from_domain}\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

$message  = "<h3>Заявка на товар</h3>";
if ( $product_title ) {
    $message .= "<p><strong>Товар:</strong> {$product_title}</p>";
}
$message .= "<p><strong>Имя:</strong> " . ( $name ?: '—' ) . "</p>";
$message .= "<p><strong>Телефон:</strong> {$phone}</p>";
$message .= "<hr><p><small>Отправлено с сайта {$from_domain}</small></p>";

wp_mail( $to_email, $subject, $message, $headers );

// ─── Редирект с результатом ──────────────────────────────────────
$_SESSION['form_status']  = 'success';
$_SESSION['form_message'] = 'Спасибо! Мы свяжемся с Вами в течение 10 минут.';

wp_safe_redirect( $referer );
exit;