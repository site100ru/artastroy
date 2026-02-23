<?php
/**
 * Главная страница
 * Файл: index.php
 */

get_header();
?>

<?php get_template_part('template-parts/hero/hero'); ?>

<?php get_template_part('template-parts/advantages/advantages'); ?>

<?php get_template_part('template-parts/section-catalog-categories/section-catalog-categories'); ?>

<?php get_template_part('template-parts/partners/partners'); ?>

<?php get_template_part('template-parts/testimonials/testimonials'); ?>

<?php get_template_part('template-parts/order-gradient/order-gradient'); ?>

<?php get_template_part('template-parts/license/license'); ?>

<?php get_footer(null, ['bg_corporate' => true]); ?>