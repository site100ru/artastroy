<?php
/**
 * Секция: Остались вопросы (форма + соцсети)
 *
 * Использование:
 *   get_template_part('template-parts/order-gradient/order-gradient');
 *
 * С параметрами:
 *   get_template_part('template-parts/order-gradient/order-gradient', null, [
 *       'title'       => 'Остались вопросы?',
 *       'description' => 'Оставьте Ваши контакты...',
 *       'action'      => 'mails/order_mail.php',   // action формы
 *       'button_text' => 'Оставить заявку',
 *   ]);
 */

$title       = $args['title']       ?? 'Остались вопросы?';
$description = $args['description'] ?? 'Оставьте Ваши контакты и мы перезвоним Вам в течение 10 минут или напишите нам в мессенджер и мы подробно ответим на Ваши вопросы.';
$action      = $args['action']      ?? 'mails/order_mail.php';
$button_text = $args['button_text'] ?? 'Оставить заявку';

// Соцсети из Customizer
$telegram = mytheme_get_telegram();
$whatsapp = mytheme_get_whatsapp();
?>

<!-- ========== ОСТАЛИСЬ ВОПРОСЫ ========== -->
<section class="section order-right-gradient-section bg-corporate">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6"></div>
            <div class="col-md-6 col-xl-5">

                <h2 class="section-title order-section-title"><?php echo esc_html($title); ?></h2>
                <p class="section-description order-section-desc mb-3"><?php echo esc_html($description); ?></p>

                <form method="post" action="<?php echo esc_attr($action); ?>">
                    <div class="row">
                        <div class="col-6 col-md-5">
                            <label for="order-gradient-name" class="order-form-label">Ваше имя</label>
                            <input type="text" name="name" class="order-form-input mb-3 mb-md-0" id="order-gradient-name">
                        </div>
                        <div class="col-6 col-md-5">
                            <label for="order-gradient-phone" class="order-form-label">Ваш телефон*</label>
                            <input type="text" name="phone" class="order-form-input mb-3 mb-md-0 telMask" id="order-gradient-phone" inputmode="text" required>
                        </div>
                    </div>
                    <div class="row mt-md-3">
                        <div class="col-6 col-md-7 col-lg-5">
                            <button type="submit" class="btn btn-corporate-color-1 mt-md-3 w-100">
                                <?php echo esc_html($button_text); ?>
                            </button>
                        </div>
                    </div>
                </form>

                <?php if ($telegram || $whatsapp) : ?>
                    <div class="row mt-4">
                        <div class="col">
                            <ul class="nav">
                                <?php if ($telegram) : ?>
                                    <li class="nav-item">
                                        <a class="nav-link ico-button p-0 pe-3 pe-md-4" href="<?php echo esc_url($telegram); ?>">
                                            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/telegram-circle-ico.svg" alt="Telegram">
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($whatsapp) : ?>
                                    <li class="nav-item">
                                        <a class="nav-link ico-button p-0 pe-3 pe-md-4" href="<?php echo esc_url($whatsapp); ?>">
                                            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/whatsapp-circle-ico.svg" alt="WhatsApp">
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
            <div class="col-xl-1"></div>
        </div>
    </div>
    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/order-sections-mobile.jpg" class="w-100 d-md-none" alt="">
</section>
<!-- ========== ОСТАЛИСЬ ВОПРОСЫ ========== -->