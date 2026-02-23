<?php

/**
 * WooCommerce: Страница одного товара
 * Файл: woocommerce/single-product.php
 *
 * Цена: задаётся стандартными полями WooCommerce
 *   — «Обычная цена» → выводится как цена без скидки
 *   — «Акционная цена» → выводится как цена со скидкой
 *
 * Единица измерения (шт / м² / кг и т.д.):
 *   Создайте глобальный атрибут в WooCommerce → Атрибуты
 *   с именем «Единица» (slug: edinica).
 *   Укажите значение в каждом товаре — оно добавится к цене.
 *
 * В functions.php добавьте:
 *   // Убираем отзывы
 *   add_filter('woocommerce_product_tabs', function($tabs) {
 *       unset($tabs['reviews']); return $tabs;
 *   }, 98);
 *   remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
 */

defined('ABSPATH') || exit;

get_header();

while (have_posts()) :
    the_post();
    global $product;
    $product = wc_get_product(get_the_ID());
    if (!$product) continue;

    $product_id    = $product->get_id();
    $product_title = get_the_title();

    // Изображения
    $main_image_id = $product->get_image_id();
    $gallery_ids   = $product->get_gallery_image_ids();
    $all_images    = $main_image_id
        ? array_merge([$main_image_id], $gallery_ids)
        : $gallery_ids;

    // Атрибуты
    $attributes = $product->get_attributes();

    // Короткое описание
    $short_desc = $product->get_short_description();

    // ── Цена (стандартные поля WooCommerce) ──────────────────────
    $price_regular = wc_price($product->get_regular_price()); // Обычная цена
    $price_sale    = $product->get_sale_price();               // Акционная цена (пустая если нет)
    $has_sale      = $product->is_on_sale() && $price_sale;

    // Единица измерения — атрибут с slug «pa_edinica»
    // Создать: WooCommerce → Атрибуты → Название: «Единица», ярлык: «edinica»
    $unit_label = '';
    foreach ($attributes as $attr) {
        if (in_array($attr->get_name(), ['pa_edinica', 'pa_unit'])) {
            $terms = $attr->get_terms();
            $unit_label = $terms
                ? $terms[0]->name
                : ($attr->get_options()[0] ?? '');
            break;
        }
    }
    $unit_suffix = $unit_label ? '/' . $unit_label : '';

endwhile;
?>

<?php
get_template_part('template-parts/hero/hero-mini', null, [
    'title'       => 'все для <span class="corporate-color">фасадных</span> и <span class="corporate-color">внутренних работ</span> в одном месте',
    'description' => 'Гарантируем качество продукции, стабильные поставки и прозрачные условия сотрудничества',
    'show_search' => true,
]);
?>

<!-- Хлебные крошки -->
<section class="section section-min bg-alt-light">
    <div class="container">
        <div class="row">
            <div class="col">
                <?php woocommerce_breadcrumb(); ?>
            </div>
        </div>
    </div>
</section>

<!-- ========== ТОВАР ========== -->
<section class="section product-single bg-alt-light">
    <div class="container">
        <div class="row">

            <!-- ===== ГАЛЕРЕЯ ===== -->
            <div class="col-lg-6">
                <div class="product-gallery">
                    <div id="carousel-product" class="carousel slide h-100" data-bs-ride="carousel" data-bs-interval="false">
                        <div class="carousel-inner h-100">
                            <?php if (!empty($all_images)) : ?>
                                <?php foreach ($all_images as $index => $image_id) :
                                    $img_url = wp_get_attachment_image_url($image_id, 'woocommerce_single');
                                    $img_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: $product_title;
                                    $active  = $index === 0 ? ' active' : '';
                                ?>
                                    <div class="default-card carousel-item h-100<?php echo $active; ?>" data-bs-interval="9999">
                                        <a href="#" onclick="openProductGallery(<?php echo $index; ?>); return false;">
                                            <div class="single-product-img approximation">
                                                <img src="<?php echo esc_url($img_url); ?>"
                                                    class="d-block w-100" loading="lazy"
                                                    alt="<?php echo esc_attr($img_alt); ?>">
                                                <div class="magnifier"></div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <div class="carousel-item h-100 active">
                                    <div class="single-product-img">
                                        <img src="<?php echo esc_url(wc_placeholder_img_src('woocommerce_single')); ?>"
                                            class="d-block w-100"
                                            alt="<?php echo esc_attr($product_title); ?>">
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if (count($all_images) > 1) : ?>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-product" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Предыдущий</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carousel-product" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Следующий</span>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- /ГАЛЕРЕЯ -->

            <!-- ===== КОНТЕНТ ===== -->
            <div class="col-lg-6">
                <div class="product-content">

                    <h1 class="product-single__title"><?php echo esc_html($product_title); ?></h1>

                    <!-- Атрибуты / характеристики -->
                    <?php if (!empty($attributes)) : ?>
                        <div class="product-content__specs mb-3">
                            <?php foreach ($attributes as $attribute) :
                                $attr_name = wc_attribute_label($attribute->get_name());

                                // Пропускаем атрибут «Единица» — он используется для единицы измерения цены
                                if (in_array($attribute->get_name(), ['pa_edinica', 'pa_unit'])) continue;

                                $terms = $attribute->get_terms();
                                $attr_value = $terms
                                    ? implode(', ', wp_list_pluck($terms, 'name'))
                                    : implode(', ', $attribute->get_options());
                                if (!$attr_value) continue;
                            ?>
                                <div class="product-spec">
                                    <?php echo esc_html($attr_name); ?>:
                                    <span class="product-spec__value"><?php echo esc_html($attr_value); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Короткое описание -->
                    <?php if ($short_desc) : ?>
                        <div class="product-short-desc mb-3">
                            <?php echo wp_kses_post($short_desc); ?>
                        </div>
                    <?php endif; ?>

                    <!-- ── Цена ── -->
                    <?php if ($product->get_regular_price()) : ?>
                        <div class="product-content__specs product-card__price  mb-3">

                            <?php if ($has_sale) : ?>
                                <!-- Есть акционная цена: показываем старую зачёркнутой -->
                                <div class="price-old product-spec">
                                    Цена: <span class="product-spec__value"><?php echo $price_regular; ?> <?php echo esc_html($unit_suffix); ?></span>
                                </div>
                                <div class="price-current">
                                    Цена со скидкой: <span class="product-spec__value"><?php echo wc_price($price_sale); ?><?php echo esc_html($unit_suffix); ?></span>
                                </div>
                            <?php else : ?>
                                <!-- Только обычная цена -->
                                <div class="price-current">
                                    Цена: <?php echo $price_regular; ?><?php echo esc_html($unit_suffix); ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endif; ?>
                    <!-- /Цена -->

                    <!-- Кнопка заявки -->
                    <button class="btn btn-corporate-color-1"
                        data-bs-toggle="modal"
                        data-bs-target="#orderModal"
                        data-product-title="<?php echo esc_attr($product_title); ?>">
                        Оставить заявку
                    </button>

                </div>
            </div>
            <!-- /КОНТЕНТ -->

        </div>
    </div>
</section>
<!-- /ТОВАР -->

<script>
    document.querySelectorAll('[data-bs-target="#orderModal"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var title = this.getAttribute('data-product-title');
            document.getElementById('modalProductTitle').textContent = title || '';
            document.getElementById('hiddenProductTitle').value = title || '';
        });
    });
</script>

<!-- ===== ГАЛЕРЕЯ FULLSCREEN ===== -->
<?php if (!empty($all_images)) : ?>
    <div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content border-0" style="background: rgba(0,0,0,0.85);">
                <div class="modal-body p-0 d-flex align-items-center justify-content-center">
                    <div id="gallery-product" class="carousel slide w-100 h-100" data-bs-ride="false">

                        <?php if (count($all_images) > 1) : ?>
                            <div class="carousel-indicators">
                                <?php foreach ($all_images as $i => $img_id) : ?>
                                    <button type="button"
                                        data-bs-target="#gallery-product"
                                        data-bs-slide-to="<?php echo $i; ?>"
                                        <?php echo $i === 0 ? 'class="active"' : ''; ?>
                                        aria-label="Slide <?php echo $i + 1; ?>">
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="carousel-inner h-100">
                            <?php foreach ($all_images as $i => $image_id) :
                                $img_full = wp_get_attachment_image_url($image_id, 'full');
                                $img_alt  = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: $product_title;
                                $active   = $i === 0 ? ' active' : '';
                            ?>
                                <div class="carousel-item h-100<?php echo $active; ?>" data-bs-interval="999999999">
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <img src="<?php echo esc_url($img_full); ?>"
                                            class="img-fluid"
                                            style="max-width: 90vw; max-height: 90vh;"
                                            alt="<?php echo esc_attr($img_alt); ?>">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if (count($all_images) > 1) : ?>
                            <button class="carousel-control-prev" type="button" data-bs-target="#gallery-product" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Предыдущий</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#gallery-product" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Следующий</span>
                            </button>
                        <?php endif; ?>

                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Закрыть"
                    style="position: fixed; top: 25px; right: 25px; z-index: 99999;"></button>
            </div>
        </div>
    </div>

    <script>
        function openProductGallery(index) {
            var modal = new bootstrap.Modal(document.getElementById('galleryModal'));
            modal.show();
            document.getElementById('galleryModal').addEventListener('shown.bs.modal', function() {
                var carousel = bootstrap.Carousel.getOrCreateInstance(document.getElementById('gallery-product'));
                carousel.to(index);
            }, {
                once: true
            });
        }
    </script>
<?php endif; ?>
<!-- /ГАЛЕРЕЯ FULLSCREEN -->

<?php get_template_part('template-parts/order-gradient/order-gradient'); ?>

<?php get_footer(null, ['bg_corporate' => false]); ?>