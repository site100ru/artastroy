<?php

session_start();
if (isset($_SESSION['win'])) {
    unset($_SESSION['win']);
    $_SESSION['display'] = "block";
} else $_SESSION['display'] = "none";

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>«АртаСтрой» - все для фасадных и внутренних работ</title>

  <link href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous">
  <link href="<?php echo get_template_directory_uri(); ?>/css/theme.css" rel="stylesheet">
  <link href="<?php echo get_template_directory_uri(); ?>/css/new.css" rel="stylesheet">

  <?php if ( $counter_head = get_theme_mod( 'mytheme_counter_head' ) ) : ?>
  <!-- Код счетчика (head) -->
  <?php echo $counter_head; ?>
  <?php endif; 
  ?>
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>

  <?php
    // Получаем данные из Customizer
    $main_phone      = mytheme_get_phone('main');
    $main_phone_link = mytheme_get_phone_link('main');
    $add_phone       = mytheme_get_phone('additional');
    $add_phone_link  = mytheme_get_phone_link('additional');
    $extra_phones    = mytheme_get_phones_extra(); // [{display, link}, ...]
    $email           = mytheme_get_email();
    $address         = mytheme_get_address();
    $job_time        = mytheme_get_job_time();

    // Собираем все телефоны в один массив для удобства вывода
    $all_phones = [];
    if ($main_phone) {
        $all_phones[] = ['display' => $main_phone, 'link' => $main_phone_link];
    }
    if ($add_phone) {
        $all_phones[] = ['display' => $add_phone, 'link' => $add_phone_link];
    }
    foreach ($extra_phones as $phone) {
        if (!empty($phone['display'])) {
            $all_phones[] = [
                'display' => $phone['display'],
                'link'    => preg_replace('/[^0-9+]/', '', $phone['link'] ?? $phone['display']),
            ];
        }
    }
    ?>

  <header class="header-white">

    <!-- Top bar: адрес / время / email / телефоны -->
    <nav class="header-nav-top navbar navbar-expand-lg navbar-light d-none d-lg-block bg-corporate">
      <div class="container">
        <div class="collapse navbar-collapse" id="navbarSupportedContent1">
          <ul class="navbar-nav ms-auto align-items-center">

            <?php if ($address) : ?>
            <li class="nav-item me-1 me-xxl-3">
              <a class="nav-link">
                <div class="d-flex align-items-center gap-2">
                  <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/location-ico.svg" alt=""
                    class="mobile-ico">
                  <span class="address-footer"><?php echo mytheme_kses_br($address); ?></span>
                </div>
              </a>
            </li>
            <?php endif; ?>

            <?php if ($job_time) : ?>
            <li class="nav-item me-1 me-xxl-3">
              <a class="nav-link">
                <div class="d-flex align-items-center gap-2">
                  <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/clock-ico.svg" alt=""
                    class="mobile-ico">
                  <span class="time-footer"><?php echo mytheme_kses_br($job_time); ?></span>
                </div>
              </a>
            </li>
            <?php endif; ?>

            <?php if ($email) : ?>
            <li class="nav-item me-1 me-xxl-4">
              <a class="nav-link" href="mailto:<?php echo esc_attr($email); ?>">
                <div class="d-flex align-items-center gap-2">
                  <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/email-ico.svg" alt=""
                    class="mobile-ico">
                  <span><?php echo esc_html($email); ?></span>
                </div>
              </a>
            </li>
            <?php endif; ?>

            <?php foreach ($all_phones as $phone) : ?>
            <li class="nav-item me-1 me-xxl-4">
              <a class="top-menu-tel nav-link" href="tel:<?php echo esc_attr($phone['link']); ?>">
                <div class="d-flex align-items-center gap-2">
                  <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/mobile-phone-ico.svg" alt=""
                    class="mobile-ico">
                  <span class="phone-footer"><?php echo esc_html($phone['display']); ?></span>
                </div>
              </a>
            </li>
            <?php endforeach; ?>

          </ul>
        </div>
      </div>
    </nav>
    <!-- /Top bar -->

    <!-- Main navbar -->
    <nav class="header-nav-bottom navbar navbar-expand-lg navbar-light bg-white shadow">
      <div class="container">

        <!-- Логотип -->
        <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>"
          style="white-space: normal; margin-right: 0;">
          <div id="header-logo" style="display: flex; align-items: center; transition: .25s;">
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/logo-dark.svg" id="navbar-brand-img"
              alt="<?php bloginfo('name'); ?>">
          </div>
        </a>

        <!-- Кнопка бургера -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobail-header-collapse"
          aria-controls="mobail-header-collapse" aria-expanded="false"
          aria-label="<?php esc_attr_e('Toggle navigation'); ?>">
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Меню -->
        <div class="collapse navbar-collapse" id="mobail-header-collapse">
          <?php
                    wp_nav_menu([
                        'theme_location' => 'main-menu',
                        'container'      => false,
                        'menu_id'        => 'menu-main-menu',
                        'menu_class'     => 'navbar-nav align-items-lg-center ms-auto mb-2 mb-lg-0',
                        'walker'         => new bootstrap_5_wp_nav_menu_walker(),
                        'fallback_cb'    => false,
                        'after'          => '', // точки добавляет walker
                    ]);
                    ?>

          <!-- Мобильный блок контактов (виден только на мобилке внутри бургера) -->
          <div class="nav-item d-lg-none py-2 mt-2">

            <?php if ($address) : ?>
            <div class="d-flex align-items-center gap-2 mobile-sliding-header-time">
              <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/location-ico-dark.svg" alt=""
                class="mobile-ico">
              <span class="address-footer"><?php echo mytheme_kses_br($address); ?></span>
            </div>
            <?php endif; ?>

            <?php foreach ($all_phones as $phone) : ?>
            <div class="mobile-sliding-header-time">
              <a class="nav-link py-2 d-flex align-items-center gap-2"
                href="tel:<?php echo esc_attr($phone['link']); ?>">
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/mobile-phone-ico-dark.svg" alt=""
                  class="mobile-ico">
                <span class="header-phone"><?php echo esc_html($phone['display']); ?></span>
              </a>
            </div>
            <?php endforeach; ?>

            <?php if ($job_time) : ?>
            <div class="d-flex align-items-center gap-2 mobile-sliding-header-time">
              <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/clock-ico-dark.svg" alt=""
                class="mobile-ico">
              <span class="time-footer"><?php echo mytheme_kses_br($job_time); ?></span>
            </div>
            <?php endif; ?>

          </div>
          <!-- /Мобильный блок контактов -->

        </div>
        <!-- /Меню -->

      </div>
    </nav>
    <!-- /Main navbar -->

  </header>