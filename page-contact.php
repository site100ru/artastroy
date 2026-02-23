<?php
/**
 * Template Name: Контакты
 * Template Post Type: page
 *
 */

get_header();

$address  = mytheme_get_address();
$job_time = mytheme_get_job_time();
$email    = mytheme_get_email();

$main_phone      = mytheme_get_phone('main');
$main_phone_link = mytheme_get_phone_link('main');
$add_phone       = mytheme_get_phone('additional');
$add_phone_link  = mytheme_get_phone_link('additional');
$extra_phones    = mytheme_get_phones_extra();

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

// Настройки карты из Customizer
$map_lat    = get_theme_mod('mytheme_map_lat',    '54.586649');
$map_lng    = get_theme_mod('mytheme_map_lng',    '39.755058');
$map_zoom   = get_theme_mod('mytheme_map_zoom',   '17');
$map_center_lat = get_theme_mod('mytheme_map_center_lat', '54.586314');
$map_center_lng = get_theme_mod('mytheme_map_center_lng', '39.754897');
$map_api_key    = get_theme_mod('mytheme_yandex_api_key', '');
?>

<?php
get_template_part('template-parts/hero/hero-mini', null, [
    'title'       => 'все для <span class="corporate-color">фасадных</span> и <span class="corporate-color">внутренних</span> работ в одном месте',
    'description' => 'Гарантируем качество продукции, стабильные поставки и прозрачные условия сотрудничества',
    'show_search' => true,
]);
?>


<!-- ========== КОНТАКТЫ ========== -->
<section class="section contact-section bg-alt-light">
    <div class="container">

        <div class="row">
            <div class="section-title-wrapper text-md-center">
                <h2 class="section-title">Контакты</h2>
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/section-title-dec.svg" class="dec" alt="">
            </div>
        </div>

        <div class="row justify-content-center align-items-center gy-3 contact-section-link">

            <?php if ($address) : ?>
                <div class="col-12 col-sm-6 col-lg-3 col-xl-2">
                    <div class="d-flex align-items-center gap-3">
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/location-ico-dark.svg" class="mobile-ico flex-shrink-0" alt="">
                        <span class="address-footer"><?php echo mytheme_kses_br($address); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($job_time) : ?>
                <div class="col-12 col-sm-6 col-lg-3 col-xl-2">
                    <div class="d-flex align-items-center gap-3">
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/clock-ico-dark.svg" class="mobile-ico flex-shrink-0" alt="">
                        <span class="time-footer"><?php echo mytheme_kses_br($job_time); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($email) : ?>
                <div class="col-12 col-sm-6 col-lg-3 col-xl-2">
                    <a href="mailto:<?php echo esc_attr($email); ?>" class="d-flex align-items-center gap-3 text-decoration-none">
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/email-ico-dark.svg" class="mobile-ico flex-shrink-0" alt="">
                        <span class="time-footer"><?php echo esc_html($email); ?></span>
                    </a>
                </div>
            <?php endif; ?>

            <?php if (!empty($all_phones)) : ?>
                <div class="col-12 col-sm-6 col-lg-3 col-xl-2">
                    <div class="d-flex align-items-center gap-3">
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/mobile-phone-ico-dark.svg" class="mobile-ico flex-shrink-0" alt="">
                        <div>
                            <?php foreach ($all_phones as $phone) : ?>
                                <a href="tel:<?php echo esc_attr($phone['link']); ?>" class="d-block text-decoration-none">
                                    <span class="header-phone"><?php echo esc_html($phone['display']); ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>
<!-- /КОНТАКТЫ -->


<!-- ========== КАРТА ========== -->
<section class="section contact-section bg-alt-light pt-0">
    <div class="container">
        <div class="row">
            <div class="col">
                <div id="map" style="height: 650px;"></div>
            </div>
        </div>
    </div>
</section>
<!-- /КАРТА -->


<?php if ($map_api_key) : ?>
    <!-- Yandex Maps API -->
    <script src="https://api-maps.yandex.ru/2.1/?apikey=<?php echo esc_attr($map_api_key); ?>&lang=ru_RU" type="text/javascript"></script>
    <script type="text/javascript">
        ymaps.ready(function() {
            var myMap = new ymaps.Map("map", {
                center: [<?php echo esc_js($map_center_lat); ?>, <?php echo esc_js($map_center_lng); ?>],
                zoom: <?php echo intval($map_zoom); ?>,
                controls: ['zoomControl', 'fullscreenControl']
            });

            var placemark = new ymaps.Placemark(
                [<?php echo esc_js($map_lat); ?>, <?php echo esc_js($map_lng); ?>], {}, {
                    iconLayout: 'default#image',
                    iconImageHref: '<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/placemark0.png',
                    iconImageSize: [270, 270],
                    iconImageOffset: [-150, -230]
                }
            );

            myMap.geoObjects.add(placemark);
        });
    </script>
<?php endif; ?>


<?php get_footer(null, ['bg_corporate' => false]); ?>