<?php
/**
 * Секция: Наши партнеры
 *
 * Использование:
 *   get_template_part('template-parts/partners/partners');
 */

$tpl = get_template_directory_uri();
?>

<!-- ========== НАШИ ПАРТНЕРЫ ========== -->
<section class="section bg-corporate">
    <div class="container">
        <div class="row">
            <div class="col text-center">
                <h2 class="section-title">Наши партнеры</h2>
                <img src="<?php echo esc_url($tpl); ?>/img/ico/section-title-dec.svg" class="dec" alt="">
            </div>
        </div>

        <div class="row align-items-md-center justify-content-between partners-row gap-3 align-items-center">
            <div class="col-auto partners-col">
                <a href="#" class="partner-logo-link">
                    <img src="<?php echo esc_url($tpl); ?>/img/partners/partner-1.svg" alt="SGS" class="partner-logo">
                </a>
            </div>
            <div class="col-auto partners-col">
                <a href="#" class="partner-logo-link">
                    <img src="<?php echo esc_url($tpl); ?>/img/partners/partner-2.svg" alt="New Mix — Сухие строительные смеси" class="partner-logo">
                </a>
            </div>
            <div class="col-auto partners-col">
                <a href="#" class="partner-logo-link">
                    <img src="<?php echo esc_url($tpl); ?>/img/partners/partner-3.svg" alt="Vetonit" class="partner-logo">
                </a>
            </div>
            <div class="col-auto partners-col">
                <a href="#" class="partner-logo-link">
                    <img src="<?php echo esc_url($tpl); ?>/img/partners/partner-4.svg" alt="ТИЗОЛ" class="partner-logo">
                </a>
            </div>
            <div class="col-auto partners-col">
                <a href="#" class="partner-logo-link">
                    <img src="<?php echo esc_url($tpl); ?>/img/partners/partner-5.svg" alt="ISOROC" class="partner-logo">
                </a>
            </div>
        </div>
    </div>
</section>
<!-- ========== НАШИ ПАРТНЕРЫ ========== -->