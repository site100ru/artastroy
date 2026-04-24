<?php

/**
 * Footer template
 *
 * Использование:
 *   get_footer();                                  // bg-corporate (по умолчанию)
 *   get_footer(null, ['bg_corporate' => false]);   // без фона
 */

$footer_bg_corporate  = isset($args['bg_corporate']) ? (bool) $args['bg_corporate'] : true;

// Иконки: на тёмном фоне — светлые, на светлом — тёмные
$logo_ico = $footer_bg_corporate ? 'logo-light.svg'       : 'logo-dark.svg';
$loc_ico  = $footer_bg_corporate ? 'location-ico.svg'     : 'location-ico-dark.svg';
$clk_ico  = $footer_bg_corporate ? 'clock-ico.svg'        : 'clock-ico-dark.svg';
$eml_ico  = $footer_bg_corporate ? 'email-ico.svg'        : 'email-ico-dark.svg';
$tel_ico  = $footer_bg_corporate ? 'mobile-phone-ico.svg' : 'mobile-phone-ico-dark.svg';
$footer_section_class = 'footer-contacts contacts-section' . ($footer_bg_corporate ? ' bg-corporate' : '');

$main_phone      = mytheme_get_phone('main');
$main_phone_link = mytheme_get_phone_link('main');
$add_phone       = mytheme_get_phone('additional');
$add_phone_link  = mytheme_get_phone_link('additional');
$extra_phones    = mytheme_get_phones_extra();
$email           = mytheme_get_email();
$telegram        = mytheme_get_telegram();
$whatsapp        = mytheme_get_whatsapp();
$viber           = mytheme_get_viber();
$vk              = mytheme_get_vk();
$address         = mytheme_get_address();
$job_time        = mytheme_get_job_time();
$max_link        = mytheme_get_max();

// Все телефоны в один массив
$all_phones = [];
if ($main_phone) $all_phones[] = ['display' => $main_phone, 'link' => $main_phone_link];
if ($add_phone)  $all_phones[] = ['display' => $add_phone,  'link' => $add_phone_link];
foreach ($extra_phones as $p) {
    if (!empty($p['display'])) {
        $all_phones[] = [
            'display' => $p['display'],
            'link'    => preg_replace('/[^0-9+]/', '', $p['link'] ?? $p['display']),
        ];
    }
}
?>

<!-- Contacts -->
<div id="contacts-sp"></div>
<section class="<?php echo esc_attr($footer_section_class); ?>">
  <div class="container pt-5 pb-0">
    <div class="row align-items-center justify-content-center">
      <div class="col-md-12 pt-2 pb-4 mb-2">
        <nav id="contacts-menu-1" class="navbar-light">

          <!-- Desktop version -->
          <div class="row h-100 justify-content-center align-items-center d-none d-lg-flex">

            <div class="col-4 col-lg-2">
              <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
                <img
                  src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/<?php echo esc_attr($logo_ico); ?>"
                  id="navbar-brand-img" alt="<?php bloginfo('name'); ?>">
              </a>
            </div>

            <div class="col-6 col-lg-8">
              <nav id="contacts-menu-2" class="navbar navbar-expand-lg navbar-light">
                <div class="collapse navbar-collapse">
                  <?php
                                    wp_nav_menu([
                                        'theme_location' => 'contacts-desktop-menu',
                                        'container'      => false,
                                        'menu_class'     => 'navbar-nav m-auto mb-2 mb-lg-0',
                                        'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                                        'walker'         => new bootstrap_5_wp_nav_menu_walker(),
                                        'fallback_cb'    => false,
                                    ]);
                                    ?>
                </div>
              </nav>
            </div>

            <div class="col-2 text-end d-flex gap-3">
              <?php if (!empty($all_phones)) : ?>
              <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/<?php echo esc_attr($tel_ico); ?>"
                alt="">
              <div>
                <?php foreach ($all_phones as $phone) : ?>
                <a href="tel:<?php echo esc_attr($phone['link']); ?>"
                  class="contacts-phone d-flex justify-content-end align-items-center">
                  <span class="phone-footer"><?php echo esc_html($phone['display']); ?></span>
                </a>
                <?php endforeach; ?>
              </div>
              <?php endif; ?>
            </div>

          </div><!-- End Desktop version -->

          <!-- Mobail version -->
          <div class="row d-lg-none">
            <div class="col-6">
              <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
                <img
                  src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/<?php echo esc_attr($logo_ico); ?>"
                  class="me-3" id="navbar-brand-img" alt="<?php bloginfo('name'); ?>">
              </a>
            </div>
          </div><!-- END Mobail version -->

        </nav>
      </div>
    </div>

    <div class="row">
      <div class="col-12">

        <ul class="nav justify-content-center flex-column flex-lg-row">
          <?php if ($address) : ?>
          <li class="nav-item me-1 me-lg-5">
            <a class="nav-link">
              <div class="d-flex align-items-center gap-3 lh-1">
                <img
                  src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/<?php echo esc_attr($loc_ico); ?>"
                  class="mobile-ico" alt="">
                <span class="address-footer"><?php echo mytheme_kses_br($address); ?></span>
              </div>
            </a>
          </li>
          <?php endif; ?>

          <?php if ($job_time) : ?>
          <li class="nav-item me-1 me-lg-5">
            <a class="nav-link">
              <div class="d-flex align-items-center gap-3 lh-1">
                <img
                  src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/<?php echo esc_attr($clk_ico); ?>"
                  class="mobile-ico" alt="">
                <span class="time-footer"><?php echo mytheme_kses_br($job_time); ?></span>
              </div>
            </a>
          </li>
          <?php endif; ?>

          <?php if ($email) : ?>
          <li class="nav-item me-1 me-lg-5">
            <a class="nav-link" href="mailto:<?php echo esc_attr($email); ?>">
              <div class="d-flex align-items-center gap-3 lh-1">
                <img
                  src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/<?php echo esc_attr($eml_ico); ?>"
                  class="mobile-ico" alt="">
                <span class="time-footer"><?php echo esc_html($email); ?></span>
              </div>
            </a>
          </li>
          <?php endif; ?>
        </ul>

        <ul class="nav justify-content-start justify-content-lg-center footer-social">
          <?php if ($telegram) : ?>
          <li class="nav-item">
            <a class="nav-link ico-button pe-3" href="<?php echo esc_url($telegram); ?>">
              <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/telegram-circle-ico.svg"
                alt="Telegram">
            </a>
          </li>
          <?php endif; ?>
          <?php if ($whatsapp) : ?>
          <li class="nav-item">
            <a class="nav-link ico-button pe-3" href="<?php echo esc_url($whatsapp); ?>">
              <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/whatsapp-circle-ico.svg"
                alt="WhatsApp">
            </a>
          </li>
          <?php endif; ?>
          <?php if ($viber) : ?>
          <li class="nav-item">
            <a class="nav-link ico-button pe-3" href="<?php echo esc_url($viber); ?>">
              <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/vider-circle-ico.svg" alt="Viber">
            </a>
          </li>
          <?php endif; ?>
          <?php if ($vk) : ?>
          <li class="nav-item">
            <a class="nav-link ico-button pe-3" href="<?php echo esc_url($vk); ?>">
              <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/vk-icon.svg" alt="ВКонтакте">
            </a>
          </li>
          <?php endif; ?>
          <?php if ($max_link) : ?>
          <li class="nav-item">
            <a class="nav-link ico-button" href="<?php echo esc_url($max_link); ?>">
              <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/max-icon.svg" alt="MAX">
            </a>
          </li>
          <?php endif; ?>
        </ul>

        <!-- Mobail version menu -->
        <div class="row d-lg-none">
          <div class="col-12">
            <?php
                        wp_nav_menu([
                            'theme_location' => 'contacts-desktop-menu',
                            'container'      => false,
                            'menu_class'     => 'navbar-nav m-auto mb-0 mb-md-4',
                            'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                            'walker'         => new bootstrap_5_wp_nav_menu_walker(),
                            'fallback_cb'    => false,
                        ]);
                        ?>
          </div>
        </div><!-- END Mobail version -->

      </div>
    </div>

  </div>

  <footer>
    <div class="container">
      <div class="row">
        <div class="col text-start text-md-center" id="im-in-footer">
          <p class="footer-bottom-text text-start text-md-center mb-0">
            Создание и продвижение сайтов: <a href="https://site100.ru">site100.ru</a>
          </p>
          <div class="policy-in-footer">
            <a class="links-one" href="<?php echo esc_url(get_template_directory_uri()); ?>/docs/Privacy-Policy.pdf"
              target="_blank">Политика конфиденциальности</a>
            <span class="d-block d-md-none"></span>
            <span class="d-none d-md-inline-block">&nbsp;|&nbsp;</span>
            <a href="<?php echo esc_url(get_template_directory_uri()); ?>/docs/Consent-to-the-processing-of-personal-data.pdf"
              target="_blank">Согласие на обработку персональных данных</a>
          </div>
        </div>
      </div>
    </div>
  </footer>
</section>
<!-- /Contacts -->

<!-- ========== МОДАЛЬНОЕ ОКНО ЗАКАЗА ТОВАРА ========== -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="post" action="<?php echo esc_url(get_template_directory_uri()); ?>/mails/order-product-mail.php"
      class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="orderModalLabel">Заказать товар</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col">
            <p><strong>Товар: <span id="modalProductTitle"></span></strong></p>
            <p><small>Мы свяжемся с Вами в течение 10 минут и ответим на все вопросы! Для звонка введите Ваше имя и
                телефон.</small></p>
          </div>
        </div>
        <div class="row">
          <div class="col-6 col-md-5">
            <label for="orderName" class="order-form-label d-none">Ваше имя</label>
            <input type="text" name="name" class="order-form-input order-form-input-dark mb-3 mb-md-0" id="orderName"
              placeholder="Ваше имя">
          </div>
          <div class="col-6 col-md-5">
            <label for="orderPhone" class="order-form-label d-none">Ваш телефон*</label>
            <input type="text" name="phone" class="order-form-input order-form-input-dark mb-3 mb-md-0 telMask"
              id="orderPhone" inputmode="text" placeholder="Ваш телефон*">
          </div>
        </div>
        <input type="hidden" name="product_title" id="hiddenProductTitle" value="">
      </div>
      <div class="modal-footer">
        <div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="orderCheck" checked>
            <label class="form-check-label" for="orderCheck">
              <p class="mb-0"><small>Даю согласие на обработку персональных данных. Подробнее об обработке персональных
                  данных в <a href="/politika-konfidenczialnosti/" target="_blank">Политике
                    конфиденциальности.</a></small></p>
            </label>
          </div>
        </div>
        <button type="submit" class="btn btn-corporate-color-1 mx-auto">Жду звонка</button>
      </div>
    </form>
  </div>
</div>
<!-- /ЗАКАЗ ТОВАРА -->

<!-- Показываем сообщение об успешной отправки -->
<div style="display: <?php echo $_SESSION['display'] ?>;" onclick="modalClose();">
  <div id="background-msg" style="display: <?php echo $_SESSION['display'] ?>;"></div>
  <button id="btn-close" type="button" class="btn-close btn-close-white" onclick="modalClose();"
    style="position: absolute; z-index: 9999; top: 15px; right: 15px;"></button>
  <div id="message">
    <?php echo $_SESSION['recaptcha'];
		unset($_SESSION['recaptcha']); ?>
  </div>
</div>



<?php wp_footer(); ?>

<script src="<?php echo esc_url(get_template_directory_uri()); ?>/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo esc_url(get_template_directory_uri()); ?>/js/inputmask.min.js"></script>
<script>
var telMask = document.getElementsByClassName("telMask");
var im = new Inputmask("+7(999)999-99-99");
im.mask(telMask);
</script>
<script src="<?php echo esc_url(get_template_directory_uri()); ?>/js/new.js"></script>

</body>

</html>