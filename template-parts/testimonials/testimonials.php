<?php

/**
 * Секция: Отзывы
 *
 * Использование:
 *   get_template_part('template-parts/testimonials/testimonials');
 */

$tpl = get_template_directory_uri();
?>

<!-- ========== ОТЗЫВЫ ========== -->
<section class="section section-testimonials">
    <div class="container">
        <div class="section-title-wrapper text-md-center">
            <h2 class="section-title">Отзывы о нас в независимых источниках</h2>
            <img src="<?php echo esc_url($tpl); ?>/img/ico/section-title-dec.svg" class="dec" alt="">
        </div>

        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="col text-center pb-4">
                    <img src="<?php echo esc_url($tpl); ?>/img/ico/yandex-logo.svg" class="mb-3 yandex-logo" alt="Яндекс">
                    <div class="review-rating mb-3 d-flex gap-4 align-items-center justify-content-center">
                        <div>
                            <img src="<?php echo esc_url($tpl); ?>/img/ico/star.svg" alt="">
                            <img src="<?php echo esc_url($tpl); ?>/img/ico/star.svg" alt="">
                            <img src="<?php echo esc_url($tpl); ?>/img/ico/star.svg" alt="">
                            <img src="<?php echo esc_url($tpl); ?>/img/ico/star.svg" alt="">
                            <img src="<?php echo esc_url($tpl); ?>/img/ico/star.svg" alt="">
                        </div>
                        <p class="mb-0 lh-1">4,9 из 5</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Отзыв 1 -->
            <div class="col-12 col-lg-6 col-xl-4 mb-4">
                <div class="review-card rounded h-100">
                    <div class="review-header d-flex align-items-center mb-3">
                        <div class="review-photo me-3">
                            <img src="<?php echo esc_url($tpl); ?>/img/ico/icon-1.png" alt="" class="rounded-circle" width="50" height="50">
                        </div>
                        <div class="review-info">
                            <p class="review-title mb-0">Ivan Petrov</p>
                            <p class="review-date text-muted small mb-0">4 ноября 2024</p>
                        </div>
                    </div>
                    <div class="review-rating mb-3">
                        <img src="<?php echo esc_url($tpl); ?>/img/ico/star.svg" alt="">
                        <img src="<?php echo esc_url($tpl); ?>/img/ico/star.svg" alt="">
                        <img src="<?php echo esc_url($tpl); ?>/img/ico/star.svg" alt="">
                        <img src="<?php echo esc_url($tpl); ?>/img/ico/star.svg" alt="">
                        <img src="<?php echo esc_url($tpl); ?>/img/ico/star.svg" alt="">
                    </div>
                    <div class="review-text">
                        <p class="review-description">
                            Нужен был шкаф в спальню. Обзвонил множество контор. Остановился на гарантшкаф. По телефону все подробно объяснили по стоимости, срокам. В этот же день связался замерщик, договорились о встрече. У замерщика в наличии все образцы материалов для по...
                            <a href="#" class="review-description-link">Еще</a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Отзыв 2 -->
            <div class="col-12 col-lg-6 col-xl-4 mb-4">
                <div class="review-card rounded h-100">
                    <div class="review-header d-flex align-items-center mb-3">
                        <div class="review-photo me-3">
                            <img src="<?php echo esc_url($tpl); ?>/img/ico/icon-2.png" alt="" class="rounded-circle" width="50" height="50">
                        </div>
                        <div class="review-info">
                            <p class="review-title mb-0">Ivan Petrov</p>
                            <p class="review-date text-muted small mb-0">4 ноября 2024</p>
                        </div>
                    </div>
                    <div class="review-rating mb-3">
                        <img src="<?php echo esc_url($tpl); ?>/img/ico/star.svg" alt="">
                        <img src="<?php echo esc_url($tpl); ?>/img/ico/star.svg" alt="">
                        <img src="<?php echo esc_url($tpl); ?>/img/ico/star.svg" alt="">
                        <img src="<?php echo esc_url($tpl); ?>/img/ico/star.svg" alt="">
                        <img src="<?php echo esc_url($tpl); ?>/img/ico/star.svg" alt="">
                    </div>
                    <div class="review-text">
                        <p class="review-description">
                            Нужен был шкаф в спальню. Обзвонил множество контор. Остановился на гарантшкаф. По телефону все подробно объяснили по стоимости, срокам. В этот же день связался замерщик, договорились о встрече. У замерщика в наличии все образцы материалов для по...
                            <a href="#" class="review-description-link">Еще</a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Отзыв 3 -->
            <div class="col-12 col-lg-6 col-xl-4 mb-4">
                <div class="review-card rounded h-100">
                    <div class="review-header d-flex align-items-center mb-3">
                        <div class="review-photo me-3">
                            <img src="<?php echo esc_url($tpl); ?>/img/ico/icon-3.png" alt="" class="rounded-circle" width="50" height="50">
                        </div>
                        <div class="review-info">
                            <p class="review-title mb-0">Ivan Petrov</p>
                            <p class="review-date text-muted small mb-0">4 ноября 2024</p>
                        </div>
                    </div>
                    <div class="review-rating mb-3">
                        <img src="<?php echo esc_url($tpl); ?>/img/ico/star.svg" alt="">
                        <img src="<?php echo esc_url($tpl); ?>/img/ico/star.svg" alt="">
                        <img src="<?php echo esc_url($tpl); ?>/img/ico/star.svg" alt="">
                        <img src="<?php echo esc_url($tpl); ?>/img/ico/star.svg" alt="">
                        <img src="<?php echo esc_url($tpl); ?>/img/ico/star.svg" alt="">
                    </div>
                    <div class="review-text">
                        <p class="review-description">
                            Нужен был шкаф в спальню. Обзвонил множество контор. Остановился на гарантшкаф. По телефону все подробно объяснили по стоимости, срокам. В этот же день связался замерщик, договорились о встрече. У замерщика в наличии все образцы материалов для по...
                            <a href="#" class="review-description-link">Еще</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row text-center mt-4">
            <div class="col">
                <a href="#" target="_blank" class="btn btn-corporate-color-1">Все отзывы</a>
            </div>
        </div>
    </div>
</section>
<!-- ========== ОТЗЫВЫ ========== -->