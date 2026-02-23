<?php

/**
 * Секция: Как заказать
 *
 * Использование:
 *   get_template_part('template-parts/how-to-order/how-to-order');
 */

$tpl = get_template_directory_uri();
?>

<!-- ========== КАК ЗАКАЗАТЬ ========== -->
<section class="section advantages py-5">
    <div class="container">
        <div class="row">
            <div class="col text-md-center">
                <h2 class="mb-3">Как заказать</h2>
                <img src="<?php echo esc_url($tpl); ?>/img/ico/section-title-dec.svg" class="mb-5" alt="">

                <div class="row justify-content-around">

                    <!-- Шаг 1 -->
                    <div class="col-lg-6 col-xl-3 mb-5 mb-xl-0">
                        <div class="row align-items-center">
                            <div class="col-3 text-start">
                                <img src="<?php echo esc_url($tpl); ?>/img/ico/1.svg" class="img-fluid" alt="1">
                            </div>
                            <div class="col-4 text-start">
                                <img src="<?php echo esc_url($tpl); ?>/img/ico/advantage-5.svg" class="img-fluid" alt="">
                            </div>
                        </div>
                        <div class="row pt-3">
                            <div class="col text-start">
                                <h3>Консультация и выбор материалов</h3>
                                <p class="mb-0">Просмотрите каталог на сайте и выберите материалы для фасада или интерьера. Свяжитесь с нами удобным способом — по телефону, WhatsApp, Telegram, Max или через форму обратной связи — чтобы обсудить детали и получить профессиональную консультацию.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Шаг 2 -->
                    <div class="col-lg-6 col-xl-3 mb-5 mb-xl-0">
                        <div class="row align-items-center">
                            <div class="col-3 text-start">
                                <img src="<?php echo esc_url($tpl); ?>/img/ico/2.svg" class="img-fluid" alt="2">
                            </div>
                            <div class="col-4 text-start">
                                <img src="<?php echo esc_url($tpl); ?>/img/ico/advantage-6.svg" class="img-fluid" alt="">
                            </div>
                        </div>
                        <div class="row pt-3">
                            <div class="col text-start">
                                <h3>Уточнение параметров и подготовка счета</h3>
                                <p class="mb-0">Сообщите нам размеры, объёмы, особенности объекта или пришлите дизайн-проект. Мы рассчитаем точную стоимость материалов, доставки и сопутствующих услуг и вышлем вам коммерческое предложение или счёт.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Шаг 3 -->
                    <div class="col-lg-6 col-xl-3 mb-5 mb-xl-0">
                        <div class="row align-items-center">
                            <div class="col-3 text-start">
                                <img src="<?php echo esc_url($tpl); ?>/img/ico/3.svg" class="img-fluid" alt="3">
                            </div>
                            <div class="col-4 text-start">
                                <img src="<?php echo esc_url($tpl); ?>/img/ico/advantage-7.svg" class="img-fluid" alt="">
                            </div>
                        </div>
                        <div class="row pt-3">
                            <div class="col text-start">
                                <h3>Подтверждение заказа и заключение договора</h3>
                                <p class="mb-0">После согласования счёта мы заключаем с вами договор и согласовываем сроки поставки. Оплата производится по реквизитам в счёте банковским переводом или наличными при получении.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Шаг 4 -->
                    <div class="col-lg-6 col-xl-3 mb-0">
                        <div class="row align-items-center">
                            <div class="col-3 text-start">
                                <img src="<?php echo esc_url($tpl); ?>/img/ico/4.svg" class="img-fluid" alt="4">
                            </div>
                            <div class="col-4 text-start">
                                <img src="<?php echo esc_url($tpl); ?>/img/ico/advantage-8.svg" class="img-fluid" alt="">
                            </div>
                        </div>
                        <div class="row pt-3">
                            <div class="col text-start">
                                <h3>Доставка и передача материалов</h3>
                                <p class="mb-0">Мы доставляем материалы на ваш объект в оговоренные сроки или возможен самовывоз. При передаче проверяете комплектацию и качество, подписываете акт приёма-передачи.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- ========== КАК ЗАКАЗАТЬ ========== -->