<?php

defined('ABSPATH') || exit;

global $product;
if (!$product || !$product->is_visible()) return;

$product_url   = get_permalink($product->get_id());
$product_title = get_the_title();
$thumbnail_url = get_the_post_thumbnail_url($product->get_id(), 'woocommerce_thumbnail') ?: wc_placeholder_img_src();
$price_html    = $product->get_price_html();

// Единица измерения — атрибут pa_edinica / pa_unit
$unit_suffix = '';
foreach ($product->get_attributes() as $attr) {
    if (in_array($attr->get_name(), ['pa_edinica', 'pa_unit'])) {
        $terms = $attr->get_terms();
        $unit_label = $terms
            ? $terms[0]->name
            : ($attr->get_options()[0] ?? '');
        if ($unit_label) $unit_suffix = '/' . $unit_label;
        break;
    }
}
?>

<div class="col-md-6 col-xl-4">
    <article class="catalog-card-item" data-product-id="<?php echo esc_attr($product->get_id()); ?>">

        <a href="<?php echo esc_url($product_url); ?>" class="catalog-card-item__img-wrap">
            <img src="<?php echo esc_url($thumbnail_url); ?>"
                alt="<?php echo esc_attr($product_title); ?>"
                loading="lazy">
        </a>

        <div class="catalog-card-item__body">
            <h2 class="catalog-card-item__title">
                <a href="<?php echo esc_url($product_url); ?>"><?php echo esc_html($product_title); ?></a>
            </h2>

            <?php if ($price_html) : ?>
                <div class="catalog-card-item__price">
                    <?php echo $price_html; ?>
                    <?php if ($unit_suffix) : ?>
                        <span class="catalog-card-item__unit"><?php echo esc_html($unit_suffix); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <button class="btn btn-corporate-color-1 btn-corporate-color-outline-1"
                data-bs-toggle="modal"
                data-bs-target="#orderModal"
                data-product-title="<?php echo esc_attr($product_title); ?>">
                Оставить заявку
            </button>
        </div>

    </article>
</div>