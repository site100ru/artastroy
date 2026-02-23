<?php

/**
 * Секция: Преимущества
 *
 * Использование:
 *   get_template_part('template-parts/advantages/advantages');
 */

$tpl = get_template_directory_uri();
?>

<!-- ========== ПРЕИМУЩЕСТВА ========== -->
<section class="section section-home-advantage">
    <div class="container">
        <div class="row g-4">

            <!-- 1 -->
            <div class="col-lg-3 col-md-6">
                <div class="advantage-item">
                    <div class="advantage-item__icon">
                        <img src="<?php echo esc_url($tpl); ?>/img/ico/advantage-1.svg" alt="">
                    </div>
                    <div class="advantage-item__content">
                        <h3 class="advantage-item__title">Большой выбор</h3>
                        <p class="advantage-item__text">У нас представлены самые востребованные строительные материалы</p>
                    </div>
                </div>
            </div>

            <!-- 2 -->
            <div class="col-lg-3 col-md-6">
                <div class="advantage-item">
                    <div class="advantage-item__icon">
                        <img src="<?php echo esc_url($tpl); ?>/img/ico/advantage-2.svg" alt="">
                    </div>
                    <div class="advantage-item__content">
                        <h3 class="advantage-item__title">Высокое качество</h3>
                        <p class="advantage-item__text">Мы сотрудничаем только с проверенными производителями, гарантируя надёжность своего продукта</p>
                    </div>
                </div>
            </div>

            <!-- 3 -->
            <div class="col-lg-3 col-md-6">
                <div class="advantage-item">
                    <div class="advantage-item__icon">
                        <img src="<?php echo esc_url($tpl); ?>/img/ico/advantage-3.svg" alt="">
                    </div>
                    <div class="advantage-item__content">
                        <h3 class="advantage-item__title">Профессиональный сервис</h3>
                        <p class="advantage-item__text">Наши специалисты готовы проконсультировать вас по любым вопросам</p>
                    </div>
                </div>
            </div>

            <!-- 4 -->
            <div class="col-lg-3 col-md-6">
                <div class="advantage-item">
                    <div class="advantage-item__icon">
                        <img src="<?php echo esc_url($tpl); ?>/img/ico/advantage-4.svg" alt="">
                    </div>
                    <div class="advantage-item__content">
                        <h3 class="advantage-item__title">Удобство доставки</h3>
                        <p class="advantage-item__text">Организуем доставку товаров в кратчайшие сроки прямо на объект строительства</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- ========== ПРЕИМУЩЕСТВА ========== -->