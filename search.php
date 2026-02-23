<?php
/**
 * Шаблон страницы поиска
 * Файл: search.php (корень темы)
 */

defined('ABSPATH') || exit;

get_header();

$search_query = get_search_query();

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
                <?php if ($search_query) : ?>
                    <h1 class="section-title">Результаты поиска: «<?php echo esc_html($search_query); ?>»</h1>
                <?php else : ?>
                    <h1 class="section-title">Поиск по каталогу</h1>
                <?php endif; ?>
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/section-title-dec.svg" class="dec" alt="">
            </div>
        </div>

        <!-- Layout: сайдбар + товары -->
        <div class="row">

            <!-- ===== САЙДБАР ===== -->
            <div class="col-lg-3 catalog-sidebar-col">
                <aside class="catalog-sidebar">
                    <div class="catalog-widget">
                        <h3 class="catalog-widget__title">Категории товаров</h3>
                        <ul class="catalog-categories">
                            <?php
                            $root_cats = get_terms([
                                'taxonomy'   => 'product_cat',
                                'hide_empty' => true,
                                'parent'     => 0,
                                'orderby'    => 'menu_order',
                            ]);
                            foreach ($root_cats as $cat) : ?>
                                <li>
                                    <a href="<?php echo esc_url(get_term_link($cat)); ?>">
                                        <?php echo esc_html($cat->name); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </aside>
            </div>
            <!-- /САЙДБАР -->

            <!-- ===== РЕЗУЛЬТАТЫ ===== -->
            <div class="col-lg-9">

                <?php if (have_posts()) : ?>

                    <div class="row g-3 catalog-product-grid" id="catalog-products-grid">
                        <?php
                        while (have_posts()) {
                            the_post();
                            global $product;
                            $product = wc_get_product(get_the_ID());
                            if ($product && $product->is_visible()) {
                                wc_get_template_part('content', 'product-card');
                            }
                        }
                        ?>
                    </div>

                    <!-- Пагинация -->
                    <nav class="catalog-pagination" aria-label="Навигация по страницам поиска">
                        <?php
                        global $wp_query;
                        $total_pages = $wp_query->max_num_pages;
                        $current     = max(1, get_query_var('paged'));

                        if ($total_pages > 1) :
                            if ($current > 1) {
                                echo '<a href="' . esc_url(get_pagenum_link($current - 1)) . '" class="catalog-pagination__btn" aria-label="Предыдущая страница">&larr;</a>';
                            }
                            $range = 2;
                            for ($i = 1; $i <= $total_pages; $i++) :
                                if ($i === 1 || $i === $total_pages || ($i >= $current - $range && $i <= $current + $range)) :
                                    if ($i === $current - $range && $i > 2) {
                                        echo '<span class="catalog-pagination__btn dots">&hellip;</span>';
                                    }
                                    $active = ($i === $current) ? ' active" aria-current="page' : '';
                                    echo '<a href="' . esc_url(get_pagenum_link($i)) . '" class="catalog-pagination__btn' . $active . '">' . $i . '</a>';
                                    if ($i === $current + $range && $i < $total_pages - 1) {
                                        echo '<span class="catalog-pagination__btn dots">&hellip;</span>';
                                    }
                                endif;
                            endfor;
                            if ($current < $total_pages) {
                                echo '<a href="' . esc_url(get_pagenum_link($current + 1)) . '" class="catalog-pagination__btn" aria-label="Следующая страница">&rarr;</a>';
                            }
                        endif;
                        ?>
                    </nav>

                <?php else : ?>

                    <div class="py-5">
                        <p class="section-title-dec">
                            По запросу «<?php echo esc_html($search_query); ?>» ничего не найдено.
                        </p>
                        <p>
                            Попробуйте изменить запрос или
                            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">перейдите в каталог</a>.
                        </p>
                    </div>

                <?php endif; ?>

            </div>
            <!-- /РЕЗУЛЬТАТЫ -->

        </div>

    </div>
</section>

<?php get_template_part('template-parts/how-to-order/how-to-order'); ?>

<?php get_template_part('template-parts/order-gradient/order-gradient'); ?>

<?php get_footer(null, ['bg_corporate' => false]); ?>