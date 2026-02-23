<?php

/**
 * Секция: Главный баннер (Hero)
 *
 * Использование:
 *   get_template_part('template-parts/hero/hero');
 *
 * С параметрами:
 *   get_template_part('template-parts/hero/hero', null, [
 *       'title'       => 'все для <span class="corporate-color">фасадных</span> работ',
 *       'description' => 'Гарантируем качество...',
 *       'show_search' => true,
 *   ]);
 */

$title       = $args['title']       ?? 'все для <span class="corporate-color">фасадных</span> и <span class="corporate-color">внутренних работ</span> в одном месте';
$description = $args['description'] ?? 'Гарантируем качество продукции, стабильные поставки и прозрачные условия сотрудничества';
$show_search = $args['show_search'] ?? true;
?>

<!-- ========== HERO ========== -->
<section class="home-section_main d-flex flex-column justify-content-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col">

                <h1 class="home-section-title"><?php echo wp_kses_post($title); ?></h1>

                <?php if ($description) : ?>
                    <p class="home-section-description"><?php echo esc_html($description); ?></p>
                <?php endif; ?>

                <?php if ($show_search) : ?>
                    <div class="col-md-6">
                        <div class="search-block">
                            <?php echo do_shortcode('[fibosearch]'); ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>
<!-- /HERO -->