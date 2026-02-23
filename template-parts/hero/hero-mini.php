<?php

/**
 * Секция: Мини-баннер (Hero Mini)
 *
 * Использование:
 *   get_template_part('template-parts/hero/hero-mini');
 *
 * С параметрами:
 *   get_template_part('template-parts/hero/hero-mini', null, [
 *       'title'       => 'Заголовок страницы',
 *       'description' => 'Подзаголовок',
 *       'show_search' => false,
 *   ]);
 */

$title       = $args['title']       ?? get_the_title();
$description = $args['description'] ?? '';
$show_search = $args['show_search'] ?? false;
?>

<!-- ========== HERO MINI ========== -->
<section class="home-section_main home-section_main-mini d-flex flex-column justify-content-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col">

                <?php if ($title) : ?>
                    <h1 class="home-section-title"><?php echo wp_kses_post($title); ?></h1>
                <?php endif; ?>

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
<!-- /HERO MINI -->