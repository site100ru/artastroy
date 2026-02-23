<?php
/**
 * Секция: Лицензии и свидетельства
 *
 * Данные берутся из Customizer (панель "Контакты" → "Лицензии и свидетельства").
 * Каждая запись: название, картинка (превью), PDF (необязательно).
 *
 * Использование:
 *   get_template_part('template-parts/license/license');
 *
 * С параметрами:
 *   get_template_part('template-parts/license/license', null, [
 *       'title' => 'Лицензии и свидетельства',
 *       'bg'    => 'bg-white',
 *   ]);
 */

$title    = $args['title'] ?? 'Лицензии и свидетельства';
$bg       = $args['bg']    ?? 'bg-white';
$licenses = mytheme_get_licenses(); // функция из functions.php

if (empty($licenses)) {
    return;
}
?>

<!-- ========== ЛИЦЕНЗИИ И СВИДЕТЕЛЬСТВА ========== -->
<section class="section <?php echo esc_attr($bg); ?>">
    <div class="container">

        <div class="row">
            <div class="col text-md-center">
                <?php if ($title) : ?>
                    <h2 class="section-title"><?php echo esc_html($title); ?></h2>
                <?php endif; ?>
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/section-title-dec.svg" class="dec" alt="">
            </div>
        </div>

        <div class="row justify-content-start justify-content-lg-between gx-4 gy-4">
            <?php foreach ($licenses as $license) :
                $title_item = $license['title']   ?? '';
                $img_url    = $license['img_url'] ?? '';
                $pdf_url    = $license['pdf_url'] ?? '';
                $is_pdf     = !empty($pdf_url);

                if (!$img_url) {
                    $img_url = get_template_directory_uri() . '/img/license-placeholder.jpg';
                }
            ?>
                <div class="col-6 col-md-4 col-lg-2half license-col">
                    <div class="license-card"
                         data-img="<?php echo esc_attr($img_url); ?>"
                         data-title="<?php echo esc_attr($title_item); ?>"
                         <?php if ($is_pdf) : ?>data-pdf="<?php echo esc_attr($pdf_url); ?>"<?php endif; ?>
                         style="cursor: pointer;">
                        <div class="license-card__img-wrap">
                            <?php if ($is_pdf) : ?>
                                <span class="license-pdf-badge">PDF</span>
                            <?php endif; ?>
                            <img src="<?php echo esc_url($img_url); ?>"
                                 alt="<?php echo esc_attr($title_item); ?>"
                                 class="license-card__img">
                            <div class="license-card__overlay">
                                <img width="32" height="32"
                                     src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/magnifying-glass.svg" alt="">
                            </div>
                        </div>
                        <p class="license-card__title"><?php echo esc_html($title_item); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- Лайтбокс -->
<div id="licenseGalleryWrapper" style="background: rgba(0,0,0,0.85); display: none; position: fixed; inset: 0; z-index: 9999;">
    <div style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
        <img id="licenseFullImg" src="" alt="" style="max-width: 90vw; max-height: 90vh; object-fit: contain;">
    </div>
    <button type="button" onclick="closeLicenseGallery();"
            class="btn-close btn-close-white"
            style="position: fixed; top: 25px; right: 25px; z-index: 99999;"
            aria-label="Close"></button>
</div>

<script>
document.querySelectorAll('.license-card').forEach(function (card) {
    card.addEventListener('click', function () {
        var pdf = this.dataset.pdf;
        if (pdf) {
            window.open(pdf, '_blank');
            return;
        }
        document.getElementById('licenseFullImg').src = this.dataset.img;
        document.getElementById('licenseFullImg').alt = this.dataset.title;
        document.getElementById('licenseGalleryWrapper').style.display = 'block';
        document.body.style.overflow = 'hidden';
    });
});

function closeLicenseGallery() {
    document.getElementById('licenseGalleryWrapper').style.display = 'none';
    document.body.style.overflow = '';
}

document.getElementById('licenseGalleryWrapper').addEventListener('click', function (e) {
    if (e.target === this) closeLicenseGallery();
});
</script>
<!-- ========== ЛИЦЕНЗИИ И СВИДЕТЕЛЬСТВА ========== -->