<?php

/**
 * WooCommerce: Архив товаров (каталог)
 * Файл: woocommerce/archive-product.php
 *
 */

defined('ABSPATH') || exit;

get_header();

// Определяем текущую категорию
$current_cat    = is_product_category() ? get_queried_object() : null;
$current_cat_id = $current_cat ? $current_cat->term_id : 0;

// Проверяем, есть ли подкатегории у текущей категории
$has_subcategories = false;
if ($current_cat_id) {
    $subcategories = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => $current_cat_id,
    ]);
    $has_subcategories = !is_wp_error($subcategories) && !empty($subcategories);
}
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


<?php if ($has_subcategories) : ?>
    <!-- ===== ВЫБОР ПОДРАЗДЕЛА ===== -->
    <section class="section bg-alt-light">
        <div class="container">

            <!-- Заголовок -->
            <div class="row">
                <div class="section-title-wrapper text-md-center">
                    <h1 class="section-title"><?php echo esc_html($current_cat->name); ?></h1>
                    <?php if ($current_cat->description) : ?>
                        <p class="section-title-dec"><?php echo esc_html($current_cat->description); ?></p>
                    <?php endif; ?>
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/section-title-dec.svg" class="dec" alt="">
                </div>
            </div>

            <!-- Сетка подкатегорий -->
            <div class="row gx-catalog gy-catalog">
                <?php foreach ($subcategories as $subcat) :
                    $thumbnail_id  = get_term_meta($subcat->term_id, 'thumbnail_id', true);
                    $thumbnail_url = $thumbnail_id
                        ? wp_get_attachment_image_url($thumbnail_id, 'medium_large')
                        : wc_placeholder_img_src('medium_large');
                    $subcat_url = get_term_link($subcat);
                ?>
                    <div class="col-lg-4 col-md-6 col-12">
                        <a href="<?php echo esc_url($subcat_url); ?>" class="catalog-card">
                            <div class="catalog-card__img-wrap">
                                <img src="<?php echo esc_url($thumbnail_url); ?>"
                                    alt="<?php echo esc_attr($subcat->name); ?>"
                                    class="catalog-card__img">
                            </div>
                            <h2 class="catalog-card__title"><?php echo esc_html($subcat->name); ?></h2>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>

<?php else : ?>
    <!-- ===== СПИСОК ТОВАРОВ ===== -->
    <section class="section bg-alt-light">
        <div class="container">

            <!-- Заголовок -->
            <div class="row">
                <div class="section-title-wrapper text-md-center">
                    <?php
                    if ($current_cat) {
                        echo '<h1 class="section-title">' . esc_html($current_cat->name) . '</h1>';
                        if ($current_cat->description) {
                            echo '<p class="section-title-dec">' . esc_html($current_cat->description) . '</p>';
                        }
                    } else {
                        echo '<h1 class="section-title">' . woocommerce_page_title(false) . '</h1>';
                        echo '<p class="section-title-dec">Широкий выбор строительных материалов для профессионального использования</p>';
                    }
                    ?>
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/section-title-dec.svg" class="dec" alt="">
                </div>
            </div>

            <!-- Layout: сайдбар + товары -->
            <div class="row">

                <!-- ===== САЙДБАР ===== -->
                <div class="col-lg-3 catalog-sidebar-col">
                    <aside class="catalog-sidebar">

                        <?php
                        // Корневые категории (только верхний уровень, без подкатегорий)
                        $root_cats = get_terms([
                            'taxonomy'   => 'product_cat',
                            'hide_empty' => true,
                            'parent'     => 0,
                            'orderby'    => 'menu_order',
                        ]);

                        // Находим родительскую категорию текущей (если мы в подкатегории)
                        $active_root_id = 0;
                        if ($current_cat) {
                            $active_root_id = ((int)$current_cat->parent === 0)
                                ? $current_cat->term_id
                                : (int)$current_cat->parent;
                        }
                        ?>

                        <!-- Виджет: Категории товаров -->
                        <div class="catalog-widget">
                            <h3 class="catalog-widget__title">Категории товаров</h3>
                            <ul class="catalog-categories">
                                <?php foreach ($root_cats as $cat) :
                                    $is_active = ($cat->term_id === $active_root_id);
                                ?>
                                    <li>
                                        <a href="<?php echo esc_url(get_term_link($cat)); ?>"
                                            class="<?php echo $is_active ? 'active' : ''; ?>">
                                            <?php echo esc_html($cat->name); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <?php
                        /**
                         * Фильтры по атрибутам.
                         */
                        $products_query_args = [
                            'post_type'      => 'product',
                            'post_status'    => 'publish',
                            'posts_per_page' => -1,
                            'fields'         => 'ids',
                        ];
                        if ($current_cat_id) {
                            $products_query_args['tax_query'] = [[
                                'taxonomy'         => 'product_cat',
                                'field'            => 'term_id',
                                'terms'            => $current_cat_id,
                                'include_children' => true,
                            ]];
                        }
                        $product_ids = get_posts($products_query_args);

                        $used_taxonomies = [];
                        foreach ((array)$product_ids as $pid) {
                            foreach (get_post_taxonomies($pid) as $tax_name) {
                                if (strpos($tax_name, 'pa_') === 0) {
                                    $used_taxonomies[$tax_name] = true;
                                }
                            }
                        }

                        $url_params = $_GET;

                        foreach (wc_get_attribute_taxonomies() as $attr) :
                            if (!$attr->attribute_public) continue;

                            $taxonomy = wc_attribute_taxonomy_name($attr->attribute_name);

                            if (!empty($product_ids) && !isset($used_taxonomies[$taxonomy])) continue;

                            $term_args = [
                                'taxonomy'   => $taxonomy,
                                'hide_empty' => true,
                                'orderby'    => 'menu_order',
                            ];
                            if (!empty($product_ids)) {
                                $term_args['object_ids'] = $product_ids;
                            }
                            $terms = get_terms($term_args);

                            if (is_wp_error($terms) || count($terms) < 1) continue;

                            $url_key      = 'pa_' . $attr->attribute_name;
                            $active_slugs = !empty($url_params[$url_key])
                                ? array_map('sanitize_title', explode(',', $url_params[$url_key]))
                                : [];
                        ?>
                            <div class="catalog-widget">
                                <h3 class="catalog-widget__title"><?php echo esc_html($attr->attribute_label); ?></h3>
                                <ul class="catalog-filter-list" data-filter-attribute="<?php echo esc_attr($attr->attribute_name); ?>">
                                    <?php foreach ($terms as $term) :
                                        $checked    = in_array($term->slug, $active_slugs) ? ' checked' : '';
                                        $input_id   = 'filter-' . $taxonomy . '-' . $term->slug;
                                        $input_name = 'filter_' . $taxonomy . '[]';
                                    ?>
                                        <li>
                                            <label class="catalog-filter-check" for="<?php echo esc_attr($input_id); ?>">
                                                <input type="checkbox"
                                                    id="<?php echo esc_attr($input_id); ?>"
                                                    name="<?php echo esc_attr($input_name); ?>"
                                                    value="<?php echo esc_attr($term->slug); ?>"
                                                    <?php echo $checked; ?>>
                                                <span class="custom-checkbox"></span>
                                                <span><?php echo esc_html($term->name); ?></span>
                                            </label>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>

                    </aside>
                </div>
                <!-- /САЙДБАР -->

                <!-- ===== ТОВАРЫ ===== -->
                <div class="col-lg-9">
                    <div id="catalog-products-container"
                        data-nonce="<?php echo esc_attr(wp_create_nonce('catalog_filter_nonce')); ?>"
                        data-action="catalog_filter_products"
                        data-category="<?php echo esc_attr(is_product_category() ? get_queried_object()->slug : ''); ?>">

                        <div class="row g-3 catalog-product-grid" id="catalog-products-grid">
                            <?php
                            if (woocommerce_product_loop()) {
                                while (have_posts()) {
                                    the_post();
                                    wc_get_template_part('content', 'product-card');
                                }
                            } else {
                                echo '<div class="col"><p>Товары не найдены.</p></div>';
                            }
                            ?>
                        </div>
                        <!-- /catalog-product-grid -->

                    </div>
                    <!-- /catalog-products-container -->

                    <!-- Пагинация -->
                    <nav id="catalog-pagination-container" class="catalog-pagination" aria-label="Навигация по страницам каталога">
                        <?php
                        $total_pages = wc_get_loop_prop('total_pages');
                        $current     = max(1, get_query_var('paged'));

                        if ($total_pages > 1) :
                            if ($current > 1) {
                                $prev_url = get_pagenum_link($current - 1);
                                echo '<a href="' . esc_url($prev_url) . '" class="catalog-pagination__btn" data-page="' . ($current - 1) . '" aria-label="Предыдущая страница">&larr;</a>';
                            }

                            $range = 2;
                            for ($i = 1; $i <= $total_pages; $i++) :
                                if ($i === 1 || $i === $total_pages || ($i >= $current - $range && $i <= $current + $range)) :
                                    if ($i === $current - $range && $i > 2) {
                                        echo '<span class="catalog-pagination__btn dots">&hellip;</span>';
                                    }
                                    $active = ($i === $current) ? ' active" aria-current="page' : '';
                                    $url    = get_pagenum_link($i);
                                    echo '<a href="' . esc_url($url) . '" class="catalog-pagination__btn' . $active . '" data-page="' . $i . '">' . $i . '</a>';
                                    if ($i === $current + $range && $i < $total_pages - 1) {
                                        echo '<span class="catalog-pagination__btn dots">&hellip;</span>';
                                    }
                                endif;
                            endfor;

                            if ($current < $total_pages) {
                                $next_url = get_pagenum_link($current + 1);
                                echo '<a href="' . esc_url($next_url) . '" class="catalog-pagination__btn" data-page="' . ($current + 1) . '" aria-label="Следующая страница">&rarr;</a>';
                            }
                        endif;
                        ?>
                    </nav>
                    <!-- /Пагинация -->

                </div>
                <!-- /ТОВАРЫ -->

            </div>
            <!-- /row -->

        </div>
        <!-- /container -->
    </section>

<?php endif; ?>

<script>
    document.addEventListener('click', function(e) {
        var btn = e.target.closest('[data-bs-target="#orderModal"]');
        if (!btn) return;
        var title = btn.getAttribute('data-product-title');
        document.getElementById('modalProductTitle').textContent = title || '';
        document.getElementById('hiddenProductTitle').value = title || '';
    });
</script>

<?php get_template_part('template-parts/order-gradient/order-gradient'); ?>

<?php get_footer(null, ['bg_corporate' => false]); ?>