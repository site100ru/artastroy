<?php
/**
 * AJAX-обработчик фильтрации и пагинации каталога
 * Файл: inc/ajax-catalog.php
 *
 * Подключение в functions.php:
 *   require_once get_template_directory() . '/inc/ajax-catalog.php';
 *
 * Ожидаемые POST-параметры от catalog-ajax.js:
 *   nonce    — wp_nonce
 *   paged    — номер страницы
 *   category — slug категории (опционально)
 *   filter_pa_{slug}[] — массив слагов значений атрибута
 *     Пример: filter_pa_brand[] = ['isover', 'knauf']
 *             filter_pa_insulation-type[] = ['basalt']
 */

defined('ABSPATH') || exit;

add_action('wp_ajax_catalog_filter_products',        'mytheme_ajax_catalog_filter');
add_action('wp_ajax_nopriv_catalog_filter_products', 'mytheme_ajax_catalog_filter');

function mytheme_ajax_catalog_filter() {
    check_ajax_referer('catalog_filter_nonce', 'nonce');

    $paged    = max(1, intval($_POST['paged']    ?? 1));
    $category = sanitize_text_field($_POST['category'] ?? '');
    $orderby  = sanitize_text_field($_POST['orderby']  ?? 'menu_order');
    $order    = in_array(strtoupper($_POST['order'] ?? 'ASC'), ['ASC', 'DESC'])
                    ? strtoupper($_POST['order']) : 'ASC';

    // ── Собираем tax_query из атрибутов ──────────────────────────────
    // Ключи вида: filter_pa_brand[], filter_pa_insulation-type[] и т.д.
    $tax_query = ['relation' => 'AND'];

    foreach ($_POST as $key => $value) {
        // Ищем только ключи начинающиеся с filter_pa_
        if (strpos($key, 'filter_pa_') !== 0) continue;

        $taxonomy = 'pa_' . sanitize_key(substr($key, strlen('filter_pa_')));
        $terms    = array_map('sanitize_title', (array) $value);
        $terms    = array_filter($terms);

        if (empty($terms)) continue;

        $tax_query[] = [
            'taxonomy' => $taxonomy,
            'field'    => 'slug',
            'terms'    => $terms,
            'operator' => 'IN',
        ];
    }

    // ── Основные аргументы запроса ───────────────────────────────────
    $args = [
        'post_type'           => 'product',
        'post_status'         => 'publish',
        'posts_per_page'      => get_option('posts_per_page', 6),
        'paged'               => $paged,
        'orderby'             => $orderby,
        'order'               => $order,
        'tax_query'           => $tax_query,
    ];

    // Фильтр по категории
    if ($category) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $category,
        ];
    }

    // ── Запрос ───────────────────────────────────────────────────────
    $query = new WP_Query($args);

    // Рендер карточек
    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product-card');
        }
    } else {
        echo '<div class="col"><p class="py-4">Товары не найдены. Попробуйте изменить параметры фильтра.</p></div>';
    }
    $products_html = ob_get_clean();
    wp_reset_postdata();

    // Рендер пагинации
    $total_pages = $query->max_num_pages;
    ob_start();
    if ($total_pages > 1) {
        $range = 2;

        if ($paged > 1) {
            echo '<a href="#" class="catalog-pagination__btn" data-page="' . ($paged - 1) . '" aria-label="Предыдущая страница">&larr;</a>';
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i === 1 || $i === $total_pages || ($i >= $paged - $range && $i <= $paged + $range)) {
                if ($i === $paged - $range && $i > 2) {
                    echo '<span class="catalog-pagination__btn dots">&hellip;</span>';
                }
                $active = ($i === $paged) ? ' active" aria-current="page' : '';
                echo '<a href="#" class="catalog-pagination__btn' . $active . '" data-page="' . $i . '">' . $i . '</a>';
                if ($i === $paged + $range && $i < $total_pages - 1) {
                    echo '<span class="catalog-pagination__btn dots">&hellip;</span>';
                }
            }
        }

        if ($paged < $total_pages) {
            echo '<a href="#" class="catalog-pagination__btn" data-page="' . ($paged + 1) . '" aria-label="Следующая страница">&rarr;</a>';
        }
    }
    $pagination_html = ob_get_clean();

    wp_send_json_success([
        'products'   => $products_html,
        'pagination' => $pagination_html,
        'total'      => $query->found_posts,
    ]);
}