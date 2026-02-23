<?php

/**
 * WooCommerce: Архив товаров (каталог)
 * Файл: woocommerce/archive-product.php
 *
 */

defined('ABSPATH') || exit;

get_header();
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


<section class="section bg-alt-light">
    <div class="container">

        <!-- Заголовок -->
        <div class="row">
            <div class="section-title-wrapper text-md-center">
                <?php
                // На странице категории — название категории, иначе общий заголовок
                if (is_product_category()) {
                    $term = get_queried_object();
                    echo '<h1 class="section-title">' . esc_html($term->name) . '</h1>';
                    if ($term->description) {
                        echo '<p class="section-title-dec">' . esc_html($term->description) . '</p>';
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
                    $current_cat    = is_product_category() ? get_queried_object() : null;
                    $current_cat_id = $current_cat ? $current_cat->term_id : 0;

                    // ── Подкатегории для сайдбара ─────────────────────────────────
                    // Если мы в корневой категории — показываем её подкатегории.
                    // Если в подкатегории — показываем сестёр (детей родителя).
                    $parent_cat = null;
                    $show_cats  = [];

                    if ($current_cat) {
                        if ((int)$current_cat->parent === 0) {
                            // Корневая: подкатегории
                            $parent_cat = $current_cat;
                            $show_cats  = get_terms([
                                'taxonomy'   => 'product_cat',
                                'hide_empty' => true,
                                'parent'     => $current_cat->term_id,
                                'orderby'    => 'menu_order',
                            ]);
                        } else {
                            // Подкатегория: сёстры
                            $parent_cat = get_term($current_cat->parent, 'product_cat');
                            $show_cats  = get_terms([
                                'taxonomy'   => 'product_cat',
                                'hide_empty' => true,
                                'parent'     => $current_cat->parent,
                                'orderby'    => 'menu_order',
                            ]);
                        }
                    }

                    // Корневые категории
                    $root_cats = get_terms([
                        'taxonomy'   => 'product_cat',
                        'hide_empty' => true,
                        'parent'     => 0,
                        'orderby'    => 'menu_order',
                    ]);
                    ?>

                    <!-- Виджет: Категории товаров (корневые) -->
                    <div class="catalog-widget">
                        <h3 class="catalog-widget__title">Категории товаров</h3>
                        <ul class="catalog-categories">
                            <?php foreach ($root_cats as $cat) :
                                $is_active = $current_cat && (
                                    $cat->term_id === $current_cat_id ||
                                    $cat->term_id === (int)($current_cat->parent ?? 0) ||
                                    term_is_ancestor_of($cat->term_id, $current_cat_id, 'product_cat')
                                );
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

                    <?php if (!empty($show_cats)) : ?>
                        <!-- Виджет: Подкатегории -->
                        <div class="catalog-widget">
                            <h3 class="catalog-widget__title">
                                <?php echo $parent_cat ? esc_html($parent_cat->name) : 'Разделы'; ?>
                            </h3>
                            <ul class="catalog-categories">
                                <?php foreach ($show_cats as $sub) : ?>
                                    <li>
                                        <a href="<?php echo esc_url(get_term_link($sub)); ?>"
                                            class="<?php echo ($sub->term_id === $current_cat_id) ? 'active' : ''; ?>">
                                            <?php echo esc_html($sub->name); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php
                    /**
                     * Фильтры по атрибутам.
                     * Показываем только атрибуты, реально используемые товарами
                     * в текущей категории (включая подкатегории).
                     * Виджет скрывается если у атрибута < 2 значений в этой категории.
                     */

                    // ID товаров в текущей категории (или все товары на /shop/)
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

                    // Собираем pa_* таксономии, используемые этими товарами
                    $used_taxonomies = [];
                    foreach ((array)$product_ids as $pid) {
                        foreach (get_post_taxonomies($pid) as $tax_name) {
                            if (strpos($tax_name, 'pa_') === 0) {
                                $used_taxonomies[$tax_name] = true;
                            }
                        }
                    }

                    // Активные значения фильтров из URL
                    $url_params = $_GET;

                    foreach (wc_get_attribute_taxonomies() as $attr) :
                        if (!$attr->attribute_public) continue;

                        $taxonomy = wc_attribute_taxonomy_name($attr->attribute_name);

                        // Пропускаем атрибуты не используемые в этой категории
                        if (!empty($product_ids) && !isset($used_taxonomies[$taxonomy])) continue;

                        // Только значения реально встречающиеся у товаров категории
                        $term_args = [
                            'taxonomy'   => $taxonomy,
                            'hide_empty' => true,
                            'orderby'    => 'menu_order',
                        ];
                        if (!empty($product_ids)) {
                            $term_args['object_ids'] = $product_ids;
                        }
                        $terms = get_terms($term_args);

                        if (is_wp_error($terms) || count($terms) < 2) continue;

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
                        // Кнопка "назад"
                        if ($current > 1) {
                            $prev_url = get_pagenum_link($current - 1);
                            echo '<a href="' . esc_url($prev_url) . '" class="catalog-pagination__btn" data-page="' . ($current - 1) . '" aria-label="Предыдущая страница">&larr;</a>';
                        }

                        // Номера страниц
                        $range = 2; // сколько страниц показывать вокруг текущей
                        for ($i = 1; $i <= $total_pages; $i++) :
                            if ($i === 1 || $i === $total_pages || ($i >= $current - $range && $i <= $current + $range)) :
                                // Добавляем многоточие
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

                        // Кнопка "вперёд"
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


<script>
    document.querySelectorAll('[data-bs-target="#orderModal"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var title = this.getAttribute('data-product-title');
            document.getElementById('modalProductTitle').textContent = title || '';
            document.getElementById('hiddenProductTitle').value = title || '';
        });
    });
</script>

<?php get_template_part('template-parts/order-gradient/order-gradient'); ?>

<?php get_footer(null, ['bg_corporate' => false]); ?>