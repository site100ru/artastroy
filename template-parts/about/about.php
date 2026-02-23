<?php

/**
 * Секция: О нас
 * Файл: template-parts/about/about.php
 *
 * Использование:
 *   get_template_part('template-parts/about/about');
 *
 * С параметрами:
 *   get_template_part('template-parts/about/about', null, [
 *       'title'  => 'О нас',
 *       'images' => [
 *           ['src' => get_template_directory_uri() . '/img/about-1.jpg', 'alt' => ''],
 *           ['src' => get_template_directory_uri() . '/img/about-2.jpg', 'alt' => ''],
 *       ],
 *   ]);
 */

$title = $args['title'] ?? 'О нас';

// Картинки: из аргументов или из Customizer
if (!empty($args['images'])) {
    $images = $args['images'];
}

$gallery_id = 'aboutSectionGallery';
?>

<!-- ========== О НАС ========== -->
<section class="section about-section bg-alt-light">
    <div class="container">

        <div class="row">
            <div class="section-title-wrapper text-md-center">
                <h2 class="section-title"><?php echo esc_html($title); ?></h2>
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/section-title-dec.svg" class="dec" alt="">
            </div>
        </div>

        <div class="row justify-content-between">

            <!-- Карусель -->
            <div class="col-12 col-lg-6 col-xl-6 order-2 order-lg-first text-center">
                <div id="aboutCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner rounded">
                        <?php foreach ($images as $i => $img) : ?>
                            <div class="carousel-item default-card <?php echo $i === 0 ? 'active' : ''; ?>">
                                <a onclick="aboutSectionGalleryOn('<?php echo esc_js($gallery_id); ?>', 'imgAboutSectionGallery-<?php echo $i + 1; ?>');" style="cursor: pointer;">
                                    <div class="single-product-img approximation">
                                        <img src="<?php echo esc_url($img['src']); ?>"
                                            class="d-block w-100"
                                            loading="lazy"
                                            alt="<?php echo esc_attr($img['alt'] ?? ''); ?>">
                                        <div class="magnifier"></div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if (count($images) > 1) : ?>
                        <button class="carousel-control-prev" type="button" data-bs-target="#aboutCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Предыдущий</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#aboutCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Следующий</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Текст -->
            <div class="col-12 col-lg-6 col-xl-5 offset-xl-1 order-1 order-lg-last mb-5 mb-lg-0 ps-xl-5">
                <?php
                $about_text = get_theme_mod('mytheme_about_text', '');
                if ($about_text) :
                    echo wp_kses_post(wpautop($about_text));
                else :
                ?>
                    <p>
                        Наша компания — надежный партнер, в мире строительных материалов.
                    </p>
                    <p>
                        Мы работаем на рынке стройматериалов более десяти лет, обеспечивая, наших клиентов, высококачественными материалами для строительства и ремонта. За годы успешной деятельности, мы зарекомендовали себя как надежного поставщика, способного предложить широкий ассортимент продукции от проверенных производителей. Будьте уверены в качестве и надежности приобретаемых стройматериалов, выбрав нашу компанию.
                    </p>
                    <p>
                        Мы ценим каждого клиента и стремимся сделать сотрудничество комфортным и выгодным для всех сторон.
                    </p>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>
<!-- /О НАС -->


<!-- ========== ГАЛЕРЕЯ О НАС (полноэкранная) ========== -->
<div id="aboutSectionGalleryWrapper" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 99998;">

    <div id="<?php echo esc_attr($gallery_id); ?>" class="carousel slide h-100" data-bs-ride="false" data-bs-interval="false">

        <?php if (count($images) > 1) : ?>
            <div class="carousel-indicators">
                <?php foreach ($images as $i => $img) : ?>
                    <button id="indAboutSectionGallery-<?php echo $i + 1; ?>"
                        type="button"
                        data-bs-target="#<?php echo esc_attr($gallery_id); ?>"
                        data-bs-slide-to="<?php echo $i; ?>"
                        aria-label="Slide <?php echo $i + 1; ?>">
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="carousel-inner h-100">
            <?php foreach ($images as $i => $img) : ?>
                <div id="imgAboutSectionGallery-<?php echo $i + 1; ?>" class="carousel-item h-100">
                    <div class="row align-items-center h-100">
                        <div class="col text-center">
                            <img src="<?php echo esc_url($img['src']); ?>"
                                class="img-fluid"
                                loading="lazy"
                                style="max-width: 90vw; max-height: 90vh;"
                                alt="<?php echo esc_attr($img['alt'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (count($images) > 1) : ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#<?php echo esc_attr($gallery_id); ?>" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Назад</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#<?php echo esc_attr($gallery_id); ?>" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Далее</span>
            </button>
        <?php endif; ?>

    </div>

    <!-- Кнопка закрытия -->
    <button type="button"
        onclick="aboutSectionGalleryClose();"
        class="btn-close btn-close-white"
        style="position: fixed; top: 25px; right: 25px; z-index: 99999;"
        aria-label="Закрыть">
    </button>

</div>
<!-- /ГАЛЕРЕЯ О НАС -->


<!-- ========== СКРИПТ ГАЛЕРЕИ О НАС ========== -->
<script>
    (function() {
        var totalSlides = <?php echo count($images); ?>;

        function aboutSectionGalleryOn(gal, img) {
            for (var i = 1; i <= totalSlides; i++) {
                var slide = document.getElementById('imgAboutSectionGallery-' + i);
                var ind = document.getElementById('indAboutSectionGallery-' + i);
                if (slide) slide.classList.remove('active');
                if (ind) ind.classList.remove('active');
            }
            document.getElementById('aboutSectionGalleryWrapper').style.display = 'block';
            document.getElementById(gal).style.display = 'block';
            var activeSlide = document.getElementById(img);
            var activeInd = document.getElementById(img.replace('img', 'ind'));
            if (activeSlide) activeSlide.classList.add('active');
            if (activeInd) activeInd.classList.add('active');
        }

        function aboutSectionGalleryClose() {
            document.getElementById('aboutSectionGalleryWrapper').style.display = 'none';
            document.getElementById('<?php echo esc_js($gallery_id); ?>').style.display = 'none';
            for (var i = 1; i <= totalSlides; i++) {
                var slide = document.getElementById('imgAboutSectionGallery-' + i);
                var ind = document.getElementById('indAboutSectionGallery-' + i);
                if (slide) slide.classList.remove('active');
                if (ind) ind.classList.remove('active');
            }
        }

        // Закрытие по клику на фон
        document.getElementById('aboutSectionGalleryWrapper').addEventListener('click', function(e) {
            if (e.target === this) aboutSectionGalleryClose();
        });

        window.aboutSectionGalleryOn = aboutSectionGalleryOn;
        window.aboutSectionGalleryClose = aboutSectionGalleryClose;
    })();
</script>
<!-- /СКРИПТ ГАЛЕРЕИ О НАС -->