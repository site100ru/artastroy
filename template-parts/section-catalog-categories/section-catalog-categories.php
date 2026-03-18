<?php

/**
 * Секция: Каталог категорий товаров (WooCommerce)
 *
 * Использование:
 *   get_template_part('template-parts/section-catalog-categories/section-catalog-categories');
 *
 * С параметрами:
 *   get_template_part('template-parts/section-catalog-categories/section-catalog-categories', null, [
 *       'title'       => 'Каталог категорий товаров',
 *       'subtitle'    => 'Широкий выбор...',
 *       'parent'      => 0,           // ID родительской категории (0 = корневые)
 *       'number'      => 6,           // кол-во категорий (0 = все)
 *       'orderby'     => 'menu_order',
 *       'hide_empty'  => true,
 *       'show_button' => true,
 *       'button_text' => 'Смотреть все товары',
 *       'button_url'  => '',          // по умолчанию — страница магазина WC
 *   ]);
 */

$title       = $args['title']       ?? 'Каталог категорий товаров';
$subtitle    = $args['subtitle']    ?? 'Широкий выбор строительных материалов для профессионального использования';
$parent      = $args['parent']      ?? 0;
$number      = $args['number']      ?? 0;
$orderby     = $args['orderby']     ?? 'menu_order';
$hide_empty  = $args['hide_empty']  ?? true;
$show_button = $args['show_button'] ?? true;
$button_text = $args['button_text'] ?? 'Смотреть все товары';
$button_url  = $args['button_url']  ?? get_permalink(wc_get_page_id('shop'));

$categories = get_terms([
    'taxonomy'   => 'product_cat',
    'orderby'    => $orderby,
    'order'      => 'ASC',
    'hide_empty' => $hide_empty,
    'parent'     => $parent,
    'number'     => $number,
]);

if (empty($categories) || is_wp_error($categories)) {
    return;
}
?>

<!-- ========== КАТАЛОГ МАТЕРИАЛОВ ========== -->
<section class="section bg-alt-light">
    <div class="container">

        <!-- Заголовок -->
        <div class="row">
            <div class="col text-md-center">
                <?php if ($title) : ?>
                    <h2 class="section-title"><?php echo esc_html($title); ?></h2>
                <?php endif; ?>
                <?php if ($subtitle) : ?>
                    <p class="section-title-dec"><?php echo esc_html($subtitle); ?></p>
                <?php endif; ?>
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/section-title-dec.svg" class="dec" alt="">
            </div>
        </div>

        <!-- Сетка подкатегорий -->
        <div class="row gx-catalog gy-catalog">
            <?php foreach ($categories as $category) :
                $thumbnail_id  = get_term_meta($category->term_id, 'thumbnail_id', true);
                $thumbnail_url = $thumbnail_id
                    ? wp_get_attachment_image_url($thumbnail_id, 'medium_large')
                    : wc_placeholder_img_src('medium_large');
                $category_url  = get_term_link($category);
            ?>
                <div class="col-lg-4 col-md-6 col-12">
                    <a href="<?php echo esc_url($category_url); ?>" class="catalog-card">
                        <div class="catalog-card__img-wrap">
                            <img src="<?php echo esc_url($thumbnail_url); ?>"
                                alt="<?php echo esc_attr($category->name); ?>"
                                class="catalog-card__img">
                        </div>
                        <h2 class="catalog-card__title"><?php echo esc_html($category->name); ?></h2>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($show_button && $button_url) : ?>
            <div class="row mt-5">
                <div class="col text-center">
                    <a href="<?php echo esc_url($button_url); ?>" class="catalog-all-btn">
                        <?php echo esc_html($button_text); ?>
                        <svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left: 8px;">
                            <path d="M13 1L19 7L13 13M1 7H19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>
<!-- ========== КАТАЛОГ МАТЕРИАЛОВ ========== -->