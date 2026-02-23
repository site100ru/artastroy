<?php

/**
 * Template Name: О нас
 * Template Post Type: page
 *
 */

get_header();

get_template_part('template-parts/hero/hero-mini', null, [
    'title'       => 'все для <span class="corporate-color">фасадных</span> и <span class="corporate-color">внутренних работ</span> в одном месте',
    'description' => 'Гарантируем качество продукции, стабильные поставки и прозрачные условия сотрудничества',
    'show_search' => true,
]);

get_template_part('template-parts/about/about', null, [
    'title'  => 'О нас',
    'images' => [
        ['src' => get_template_directory_uri() . '/img/about.png', 'alt' => ''],
        ['src' => get_template_directory_uri() . '/img/about.png', 'alt' => ''],
    ],
]);
get_template_part('template-parts/order-gradient/order-gradient');

get_footer(null, ['bg_corporate' => false]);
