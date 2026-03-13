<?php

/*** MENU ***/
/* Bootstrap 5 wp_nav_menu walker */
class bootstrap_5_wp_nav_menu_walker extends Walker_Nav_menu
{
    private $current_item;
    private $dropdown_menu_alignment_values = [
        'dropdown-menu-start',
        'dropdown-menu-end',
        'dropdown-menu-sm-start',
        'dropdown-menu-sm-end',
        'dropdown-menu-md-start',
        'dropdown-menu-md-end',
        'dropdown-menu-lg-start',
        'dropdown-menu-lg-end',
        'dropdown-menu-xl-start',
        'dropdown-menu-xl-end',
        'dropdown-menu-xxl-start',
        'dropdown-menu-xxl-end'
    ];

    function start_lvl(&$output, $depth = 0, $args = null)
    {
        $dropdown_menu_class[] = '';
        foreach ($this->current_item->classes as $class) {
            if (in_array($class, $this->dropdown_menu_alignment_values)) {
                $dropdown_menu_class[] = $class;
            }
        }
        $indent = str_repeat("\t", $depth);
        $submenu = ($depth > 0) ? ' sub-menu' : '';
        $output .= "\n$indent<ul class=\"dropdown-menu$submenu " . esc_attr(implode(" ", $dropdown_menu_class)) . " depth_$depth\">\n";
    }

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $this->current_item = $item;

        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $li_attributes = '';
        $class_names = $value = '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;

        $classes[] = ($args->walker->has_children) ? 'dropdown' : '';
        $classes[] = 'nav-item';
        $classes[] = 'nav-item-' . $item->ID;
        if ($depth && $args->walker->has_children) {
            $classes[] = 'dropdown-menu dropdown-menu-end';
        }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = ' class="' . esc_attr($class_names) . '"';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li ' . $id . $value . $class_names . $li_attributes . '>';

        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

        $active_class = ($item->current || $item->current_item_ancestor || in_array("current_page_parent", $item->classes, true) || in_array("current-post-ancestor", $item->classes, true)) ? 'active' : '';
        $nav_link_class = ($depth > 0) ? 'dropdown-item header-link ' : 'nav-link header-link ';
        $attributes .= ($args->walker->has_children) ? ' class="' . $nav_link_class . $active_class . ' dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : ' class="' . $nav_link_class . $active_class . '"';

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);

        // Показываем точки только в горизонтальных меню (не в footer колонках)
        // Определяем footer меню по наличию класса "flex-column" в items_wrap
        $is_footer_menu = (isset($args->items_wrap) && strpos($args->items_wrap, 'flex-column') !== false);

        if (!$is_footer_menu) {
            $item_title = $item->title;
            $dropdown = in_array('dropdown', $classes);

            // Проверяем, является ли элемент последним в меню
            $is_last_item = ($item_title == 'Контакты');

            // Добавляем точки только если это НЕ последний элемент
            if (!$is_last_item && $dropdown == false && $depth == 0) {
                $output .= '
        <li class="nav-item d-none d-lg-inline">
            <img loading="lazy" src="' . get_template_directory_uri() . '/img/ico/point.svg" alt="Декоративная точка" class="img-fluid dec">
        </li>
    ';
            }
        }
    }
}
/* End Bootstrap 5 wp_nav_menu walker */


/* Register a new menu */
add_action('after_setup_theme', function () {
    register_nav_menus([
        'main-menu' => 'Main menu',
        'mobail-header-collapse' => 'Mobail header collapse',
        'contacts-desktop-menu' => 'Contacts desktop menu',
        'footer-menu-1' => 'footer-menu-1',
        'footer-menu-2' => 'footer-menu-2'
    ]);
});
/* End register a new menu */
/*** END MENU ***/

/*** WOOCOMMERCE SUPPORT ***/
add_action('after_setup_theme', 'mytheme_add_woocommerce_support');
function mytheme_add_woocommerce_support()
{
    add_theme_support('woocommerce');
}


/* Изменяем размер миниатюр WooCommerce */
add_filter('woocommerce_get_image_size_thumbnail', 'add_thumbnail_size', 1, 10);
function add_thumbnail_size($size)
{
    $size['width'] = 600;
    $size['height'] = 400;
    $size['crop'] = 1; //0 - не обрезаем, 1 - обрезка
    return $size;
}


/* Отключаем ненужные опции вывода на страницу товара */
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

// Убираем хлебные крошки из woocommerce_before_main_content
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

// Цена
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);

// Кнопка
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);


/* Изменяем значек валюты */
add_filter('woocommerce_currency_symbol', 'change_existing_currency_symbol', 10, 2);
function change_existing_currency_symbol($currency_symbol, $currency)
{
    switch ($currency) {
        case 'RUB':
            $currency_symbol = '₽';
            break;
    }
    return $currency_symbol;
}
/*** END WOOCOMMERCE SUPPORT ***/



/*** BREADCRUMBS ***/
/* Change several of the breadcrumb defaults */
add_filter('woocommerce_breadcrumb_defaults', 'jk_woocommerce_breadcrumbs');
function jk_woocommerce_breadcrumbs()
{
    return array(
        'delimiter' => ' / ',
        'wrap_before' => '<nav class="woocommerce-breadcrumb" itemprop="breadcrumb"><a href="/" class="text-decoration-none"><img src="' . get_template_directory_uri() . '/img/ico/home.svg" alt="Домик"></a> / ',
        'wrap_after' => '</nav>',
        'before' => '',
        'after' => '',
        'home' => _x('Home', 'breadcrumb', 'woocommerce'),
    );
}

/* Убираем ссылку на главную страницу сайта в хлебных крошках */
add_filter('woocommerce_breadcrumb_defaults', 'wcc_change_breadcrumb_home_text');
function wcc_change_breadcrumb_home_text($defaults)
{
    $defaults['home'] = null;
    return $defaults;
}

/* Добавляем ссылку на главную страницу магазина в хлебных крошках */
add_filter('woocommerce_get_breadcrumb', function ($crumbs, $Breadcrumb) {
    if (is_post_type_archive('products') or is_product_taxonomy('product-cat')) {
        $new_breadcrumb = [
            _x('Продукция', 'breadcrumb', 'woocommerce'),
            get_permalink(wc_get_page_id('shop'))
        ];
        array_splice($crumbs, 0, 0, [$new_breadcrumb]);
    } else if (is_tax('portfolio-cat')) {
        $new_breadcrumb = [
            _x('Наши работы', 'breadcrumb', 'woocommerce'),
            home_url('/portfolio/')
        ];
        array_splice($crumbs, 0, 1, [$new_breadcrumb]);
    }
    return $crumbs;
}, 10, 2);


/* WC 2.6.4: Изменить любой элемент "хлебных крошек" */
add_filter('woocommerce_get_breadcrumb', 'my_woocommerce_get_breadcrumb');
function my_woocommerce_get_breadcrumb($breadcrumb)
{
    foreach ($breadcrumb as $key => $crumb) {
        if ($breadcrumb[$key][0] == 'Наша продукция')
            $breadcrumb[$key][0] = 'Продукция';
    }
    return $breadcrumb;
}
/*** END BREADCRUMBS ***/



/*** ДОБАВЛЯЕМ ВОЗМОЖНОСТЬ В НАСТРОЙКАХ ТЕМЫ ДОБАВИТЬ КОНТАКТЫ И КОД СЧЕТЧИКА ***/
function mytheme_customize_register($wp_customize)
{
    // Добавляем секцию для аналитики
    $wp_customize->add_section('mytheme_analytics', array(
        'title' => 'Аналитика и счетчики',
        'priority' => 200,
    ));

    // Поле для кода счетчика (head)
    $wp_customize->add_setting('mytheme_counter_head', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_counter_head', array(
        'label' => 'Код счетчика (в <head>)',
        'description' => 'Вставьте код, который должен быть в <head> (например, Google Analytics, Meta Pixel)',
        'section' => 'mytheme_analytics',
        'type' => 'textarea',
    ));

    // Поле для кода счетчика (body)
    $wp_customize->add_setting('mytheme_counter_body', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_counter_body', array(
        'label' => 'Код счетчика (перед </body>)',
        'description' => 'Вставьте код, который должен быть перед закрывающим тегом </body> (например, Яндекс.Метрика)',
        'section' => 'mytheme_analytics',
        'type' => 'textarea',
    ));


    /** КОНТАКТЫ **/
    // Создаем панель (родительский контейнер)
    $wp_customize->add_panel('contact_panel', array(
        'title' => 'Контакты',
        'description' => 'Описание контактов',
        'priority' => 205,
    ));

    /* ОСНОВНОЙ НОМЕР ТЕЛЕФОНА */
    $wp_customize->add_section('mytheme_contacts', array(
        'title' => 'Основной номер телефона',
        'panel' => 'contact_panel',
        'priority' => 5
    ));

    $wp_customize->add_setting('mytheme_main_phone_country_code', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_main_phone_country_code', array(
        'label' => 'Код страны',
        'description' => 'Например: 8 или +7',
        'section' => 'mytheme_contacts',
        'type' => 'input',
        'input_attrs' => array(
            'placeholder' => '',
            'style' => 'width: 60px; display: inline-block;',
        )
    ));

    $wp_customize->add_setting('mytheme_main_phone_region_code', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_main_phone_region_code', array(
        'label' => 'Код региона',
        'description' => 'Например: 800, без скобок',
        'section' => 'mytheme_contacts',
        'type' => 'input',
        'input_attrs' => array(
            'placeholder' => '',
            'style' => 'width: 60px; display: inline-block;',
        )
    ));

    $wp_customize->add_setting('mytheme_main_phone_number', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_main_phone_number', array(
        'label' => 'Номер телефона',
        'description' => 'Например: 880-80-88',
        'section' => 'mytheme_contacts',
        'type' => 'input',
        'input_attrs' => array(
            'placeholder' => '',
            'style' => 'width: 100px; display: inline-block;',
        )
    ));


    /* ДОПОЛНИТЕЛЬНЫЙ НОМЕР ТЕЛЕФОНА */
    $wp_customize->add_section('additional_phone_number', array(
        'title' => 'Дополнительный номер телефона',
        'panel' => 'contact_panel',
        'priority' => 10
    ));

    $wp_customize->add_setting('additional_phone_country_code', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('additional_phone_country_code', array(
        'label' => 'Код страны',
        'description' => 'Например: 8 или +7',
        'section' => 'additional_phone_number',
        'type' => 'input',
        'input_attrs' => array(
            'placeholder' => '',
            'style' => 'width: 60px; display: inline-block;',
        )
    ));

    $wp_customize->add_setting('additional_phone_region_code', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('additional_phone_region_code', array(
        'label' => 'Код региона',
        'description' => 'Например: 800, без скобок',
        'section' => 'additional_phone_number',
        'type' => 'input',
        'input_attrs' => array(
            'placeholder' => '',
            'style' => 'width: 60px; display: inline-block;',
        )
    ));

    $wp_customize->add_setting('additional_phone_number', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('additional_phone_number', array(
        'label' => 'Номер телефона',
        'description' => 'Например: 880-80-88',
        'section' => 'additional_phone_number',
        'type' => 'input',
        'input_attrs' => array(
            'placeholder' => '',
            'style' => 'width: 100px; display: inline-block;',
        )
    ));


    /* ДОПОЛНИТЕЛЬНЫЕ НОМЕРА ТЕЛЕФОНОВ (повторитель) */
    $wp_customize->add_section('mytheme_contacts_phones_extra', array(
        'title' => 'Дополнительные номера телефонов',
        'panel' => 'contact_panel',
        'priority' => 15
    ));

    $wp_customize->add_setting('mytheme_phones_extra_json', array(
        'default' => '',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new Mytheme_Phone_Repeater_Control(
        $wp_customize,
        'mytheme_phones_extra_json',
        array(
            'label' => 'Дополнительные телефоны',
            'description' => 'Добавьте дополнительные номера телефонов. Можно добавить несколько.',
            'section' => 'mytheme_contacts_phones_extra',
        )
    ));


    /* EMAIL */
    $wp_customize->add_section('mytheme_contacts_email', array(
        'title' => 'Email',
        'panel' => 'contact_panel',
        'priority' => 20
    ));

    $wp_customize->add_setting('mytheme_email', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_email', array(
        'label' => 'Email',
        'section' => 'mytheme_contacts_email',
        'type' => 'input',
    ));


    /* ДОПОЛНИТЕЛЬНЫЕ EMAIL (повторитель) */
    $wp_customize->add_section('mytheme_contacts_emails_extra', array(
        'title' => 'Дополнительные почты для приема писем',
        'panel' => 'contact_panel',
        'priority' => 25
    ));

    $wp_customize->add_setting('mytheme_emails_extra_json', array(
        'default' => '',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new Mytheme_Email_Repeater_Control(
        $wp_customize,
        'mytheme_emails_extra_json',
        array(
            'label' => 'Дополнительные Email адреса',
            'description' => 'Добавьте дополнительные email адреса для приема почты. Можно добавить несколько.',
            'section' => 'mytheme_contacts_emails_extra',
        )
    ));


    /* Telegram */
    $wp_customize->add_section('mytheme_contacts_telegram', array(
        'title' => 'Telegram',
        'panel' => 'contact_panel',
        'priority' => 30
    ));

    $wp_customize->add_setting('mytheme_telegram', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_telegram', array(
        'label' => 'Telegram',
        'description' => 'Укажите ссылку на Telegram',
        'section' => 'mytheme_contacts_telegram',
        'type' => 'input',
    ));


    /* Whatsapp */
    $wp_customize->add_section('mytheme_contacts_whatsapp', array(
        'title' => 'Whatsapp',
        'panel' => 'contact_panel',
        'priority' => 35
    ));

    $wp_customize->add_setting('mytheme_whatsapp', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_whatsapp', array(
        'label' => 'Whatsapp',
        'description' => 'Укажите ссылку на Whatsapp',
        'section' => 'mytheme_contacts_whatsapp',
        'type' => 'input',
    ));


    /* VK */
    $wp_customize->add_section('mytheme_contacts_vk', array(
        'title' => 'Вконтакте',
        'panel' => 'contact_panel',
        'priority' => 40
    ));

    $wp_customize->add_setting('mytheme_vk', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_vk', array(
        'label' => 'Вконтакте',
        'description' => 'Укажите ссылку на Вконтакте',
        'section' => 'mytheme_contacts_vk',
        'type' => 'input'
    ));


    /* Instagram */
    $wp_customize->add_section('mytheme_contacts_instagram', array(
        'title' => 'Instagram',
        'panel' => 'contact_panel',
        'priority' => 45
    ));

    $wp_customize->add_setting('mytheme_instagram', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_instagram', array(
        'label' => 'Instagram',
        'description' => 'Укажите ссылку на Instagram',
        'section' => 'mytheme_contacts_instagram',
        'type' => 'input'
    ));


    /* Address */
    $wp_customize->add_section('mytheme_contacts_address', array(
        'title' => 'Адрес',
        'panel' => 'contact_panel',
        'priority' => 50
    ));

    $wp_customize->add_setting('mytheme_address', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_address', array(
        'label' => 'Адрес',
        'description' => 'Укажите адрес организации',
        'section' => 'mytheme_contacts_address',
        'type' => 'input'
    ));

    $wp_customize->add_setting('mytheme_address_full', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_address_full', array(
        'label' => 'Адрес (полный)',
        'description' => 'Укажите полный адрес организации с подробностями',
        'section' => 'mytheme_contacts_address',
        'type' => 'textarea'
    ));


    /* MAX */
    $wp_customize->add_section('mytheme_contacts_max', array(
        'title' => 'МАХ',
        'panel' => 'contact_panel',
        'priority' => 55
    ));

    $wp_customize->add_setting('mytheme_max', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_max', array(
        'label' => 'Адрес',
        'description' => 'Укажите ссылку на МАХ',
        'section' => 'mytheme_contacts_max',
        'type' => 'input'
    ));


    /* Время работы */
    $wp_customize->add_section('mytheme_contacts_job_time', array(
        'title' => 'Время работы',
        'panel' => 'contact_panel',
        'priority' => 60
    ));

    $wp_customize->add_setting('mytheme_job_time', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_job_time', array(
        'label' => 'Время работы',
        'description' => 'Укажите время работы',
        'section' => 'mytheme_contacts_job_time',
        'type' => 'input'
    ));

    // Секция "Лицензии" в панели "Контакты"
    $wp_customize->add_section('mytheme_licenses', [
        'title'    => 'Лицензии и свидетельства',
        'panel'    => 'contact_panel',
        'priority' => 65,
    ]);

    $wp_customize->add_setting('mytheme_licenses_json', [
        'default'           => '',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control(new Mytheme_License_Repeater_Control(
        $wp_customize,
        'mytheme_licenses_json',
        [
            'label'       => 'Лицензии и свидетельства',
            'description' => 'Добавьте лицензии: название, картинка-превью и PDF (необязательно).',
            'section'     => 'mytheme_licenses',
        ]
    ));

    /* ── СЕКЦИЯ: Яндекс.Карта ── */
    $wp_customize->add_section('mytheme_map', [
        'title'    => 'Яндекс.Карта',
        'panel'    => 'contact_panel',   // вложена в панель "Контакты"
        'priority' => 70,
    ]);

    // API-ключ
    $wp_customize->add_setting('mytheme_yandex_api_key', [
        'default'           => '',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mytheme_yandex_api_key', [
        'label'       => 'API-ключ Яндекс.Карт',
        'description' => 'Получить ключ: https://developer.tech.yandex.ru/',
        'section'     => 'mytheme_map',
        'type'        => 'text',
    ]);

    // Центр карты — широта
    $wp_customize->add_setting('mytheme_map_center_lat', [
        'default'           => '54.586314',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mytheme_map_center_lat', [
        'label'       => 'Центр карты — широта',
        'description' => 'Например: 54.586314',
        'section'     => 'mytheme_map',
        'type'        => 'text',
    ]);

    // Центр карты — долгота
    $wp_customize->add_setting('mytheme_map_center_lng', [
        'default'           => '39.754897',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mytheme_map_center_lng', [
        'label'       => 'Центр карты — долгота',
        'description' => 'Например: 39.754897',
        'section'     => 'mytheme_map',
        'type'        => 'text',
    ]);

    // Метка — широта
    $wp_customize->add_setting('mytheme_map_lat', [
        'default'           => '54.586649',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mytheme_map_lat', [
        'label'       => 'Метка на карте — широта',
        'description' => 'Например: 54.586649',
        'section'     => 'mytheme_map',
        'type'        => 'text',
    ]);

    // Метка — долгота
    $wp_customize->add_setting('mytheme_map_lng', [
        'default'           => '39.755058',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('mytheme_map_lng', [
        'label'       => 'Метка на карте — долгота',
        'description' => 'Например: 39.755058',
        'section'     => 'mytheme_map',
        'type'        => 'text',
    ]);

    // Масштаб
    $wp_customize->add_setting('mytheme_map_zoom', [
        'default'           => '17',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('mytheme_map_zoom', [
        'label'       => 'Масштаб карты',
        'description' => 'От 0 (весь мир) до 19 (максимальное приближение). Рекомендуется 15–17.',
        'section'     => 'mytheme_map',
        'type'        => 'number',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 19,
            'step' => 1,
        ],
    ]);
}
add_action('customize_register', 'mytheme_customize_register');


/**
 * Кастомные контролы - загружаются только в контексте кастомайзера
 */
if (class_exists('WP_Customize_Control')) {

    /**
     * Кастомный контрол для повторителя телефонов
     */
    class Mytheme_Phone_Repeater_Control extends WP_Customize_Control
    {
        public $type = 'phone_repeater';

        public function render_content()
        {
            $values = json_decode($this->value(), true);
            if (!is_array($values)) {
                $values = array();
            }
?>
            <label>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php if (!empty($this->description)) : ?>
                    <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
                <?php endif; ?>
            </label>

            <div class="phone-repeater-list">
                <?php foreach ($values as $index => $phone) : ?>
                    <div class="phone-repeater-item" style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <input type="text" placeholder="Номер для отображения (напр: 8 (4912) 77-70-98)" value="<?php echo esc_attr($phone['display']); ?>" class="phone-display" style="width: 100%; margin-bottom: 5px;" />
                        <input type="text" placeholder="Номер для ссылки (напр: 84912777098)" value="<?php echo esc_attr($phone['link']); ?>" class="phone-link" style="width: 100%; margin-bottom: 5px;" />
                        <button type="button" class="button remove-phone" style="color: #a00;">Удалить</button>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="button" class="button add-phone" style="margin-top: 10px;">+ Добавить телефон</button>

            <input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr($this->value()); ?>" class="phone-repeater-value" />

            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    var control = $('#customize-control-<?php echo esc_js($this->id); ?>');

                    function updateValue() {
                        var phones = [];
                        control.find('.phone-repeater-item').each(function() {
                            var display = $(this).find('.phone-display').val();
                            var link = $(this).find('.phone-link').val();
                            if (display || link) {
                                phones.push({
                                    display: display,
                                    link: link
                                });
                            }
                        });
                        control.find('.phone-repeater-value').val(JSON.stringify(phones)).trigger('change');
                    }

                    control.on('click', '.add-phone', function() {
                        var template = '<div class="phone-repeater-item" style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">' +
                            '<input type="text" placeholder="Номер для отображения (напр: 8 (4912) 77-70-98)" class="phone-display" style="width: 100%; margin-bottom: 5px;" />' +
                            '<input type="text" placeholder="Номер для ссылки (напр: 84912777098)" class="phone-link" style="width: 100%; margin-bottom: 5px;" />' +
                            '<button type="button" class="button remove-phone" style="color: #a00;">Удалить</button>' +
                            '</div>';
                        control.find('.phone-repeater-list').append(template);
                    });

                    control.on('click', '.remove-phone', function() {
                        $(this).closest('.phone-repeater-item').remove();
                        updateValue();
                    });

                    control.on('input', '.phone-display, .phone-link', function() {
                        updateValue();
                    });
                });
            </script>
        <?php
        }
    }


    /**
     * Кастомный контрол для повторителя email
     */
    class Mytheme_Email_Repeater_Control extends WP_Customize_Control
    {
        public $type = 'email_repeater';

        public function render_content()
        {
            $values = json_decode($this->value(), true);
            if (!is_array($values)) {
                $values = array();
            }
        ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php if (!empty($this->description)) : ?>
                    <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
                <?php endif; ?>
            </label>

            <div class="email-repeater-list">
                <?php foreach ($values as $index => $email) : ?>
                    <div class="email-repeater-item" style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <input type="email" placeholder="Email адрес" value="<?php echo esc_attr($email['email']); ?>" class="email-address" style="width: 100%; margin-bottom: 5px;" />
                        <button type="button" class="button remove-email" style="color: #a00;">Удалить</button>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="button" class="button add-email" style="margin-top: 10px;">+ Добавить email</button>

            <input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr($this->value()); ?>" class="email-repeater-value" />

            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    var control = $('#customize-control-<?php echo esc_js($this->id); ?>');

                    function updateValue() {
                        var emails = [];
                        control.find('.email-repeater-item').each(function() {
                            var email = $(this).find('.email-address').val();
                            if (email) {
                                emails.push({
                                    email: email
                                });
                            }
                        });
                        control.find('.email-repeater-value').val(JSON.stringify(emails)).trigger('change');
                    }

                    control.on('click', '.add-email', function() {
                        var template = '<div class="email-repeater-item" style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">' +
                            '<input type="email" placeholder="Email адрес" class="email-address" style="width: 100%; margin-bottom: 5px;" />' +
                            '<button type="button" class="button remove-email" style="color: #a00;">Удалить</button>' +
                            '</div>';
                        control.find('.email-repeater-list').append(template);
                    });

                    control.on('click', '.remove-email', function() {
                        $(this).closest('.email-repeater-item').remove();
                        updateValue();
                    });

                    control.on('input', '.email-address', function() {
                        updateValue();
                    });
                });
            </script>
        <?php
        }
    }

    class Mytheme_License_Repeater_Control extends WP_Customize_Control
    {
        public $type = 'license_repeater';

        public function enqueue()
        {
            // Подключаем медиазагрузчик WordPress
            wp_enqueue_media();
        }

        public function render_content()
        {
            $values = json_decode($this->value(), true);
            if (!is_array($values)) {
                $values = [];
            }
            $control_id = esc_js($this->id);
        ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php if (!empty($this->description)) : ?>
                    <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
                <?php endif; ?>
            </label>

            <div class="license-repeater-list" id="license-repeater-list-<?php echo $control_id; ?>">
                <?php foreach ($values as $index => $item) : ?>
                    <div class="license-repeater-item" style="margin-bottom: 15px; padding: 12px; border: 1px solid #ddd; border-radius: 4px; background: #fafafa;">

                        <!-- Название -->
                        <p style="margin: 0 0 8px;"><strong>Лицензия <?php echo $index + 1; ?></strong></p>
                        <input type="text"
                            placeholder="Название (напр: Свидетельство СРО)"
                            value="<?php echo esc_attr($item['title'] ?? ''); ?>"
                            class="license-title widefat"
                            style="margin-bottom: 8px;">

                        <!-- Картинка-превью -->
                        <p style="margin: 0 0 4px; font-size: 12px; color: #555;">Картинка-превью:</p>
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                            <div class="license-img-preview" style="width: 60px; height: 60px; border: 1px solid #ddd; background: #eee; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                <?php if (!empty($item['img_url'])) : ?>
                                    <img src="<?php echo esc_attr($item['img_url']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else : ?>
                                    <span style="font-size: 10px; color: #999;">нет</span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <input type="hidden" class="license-img-url" value="<?php echo esc_attr($item['img_url'] ?? ''); ?>">
                                <button type="button" class="button license-upload-img" style="display: block; margin-bottom: 4px;">Выбрать картинку</button>
                                <button type="button" class="button license-remove-img" style="display: block; color: #a00; <?php echo empty($item['img_url']) ? 'display:none!important;' : ''; ?>">Удалить</button>
                            </div>
                        </div>

                        <!-- PDF -->
                        <p style="margin: 0 0 4px; font-size: 12px; color: #555;">PDF файл (необязательно):</p>
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                            <input type="hidden" class="license-pdf-url" value="<?php echo esc_attr($item['pdf_url'] ?? ''); ?>">
                            <span class="license-pdf-name" style="font-size: 12px; color: #333; flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                <?php echo !empty($item['pdf_url']) ? basename($item['pdf_url']) : '—'; ?>
                            </span>
                            <button type="button" class="button license-upload-pdf">Выбрать PDF</button>
                            <button type="button" class="button license-remove-pdf" style="color: #a00; <?php echo empty($item['pdf_url']) ? 'display:none;' : ''; ?>">✕</button>
                        </div>

                        <button type="button" class="button license-remove-item" style="color: #a00;">Удалить лицензию</button>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="button" class="button button-primary license-add-item" style="margin-top: 10px;">+ Добавить лицензию</button>
            <input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr($this->value()); ?>" class="license-repeater-value">

            <script type="text/javascript">
                (function($) {
                    var listId = 'license-repeater-list-<?php echo $control_id; ?>';
                    var $control = $('#customize-control-<?php echo $control_id; ?>');
                    var $list = $('#' + listId);

                    // ── Сохранить значение ────────────────────────────────
                    function updateValue() {
                        var licenses = [];
                        $list.find('.license-repeater-item').each(function() {
                            licenses.push({
                                title: $(this).find('.license-title').val(),
                                img_url: $(this).find('.license-img-url').val(),
                                pdf_url: $(this).find('.license-pdf-url').val(),
                            });
                        });
                        $control.find('.license-repeater-value').val(JSON.stringify(licenses)).trigger('change');
                    }

                    // ── Открыть медиазагрузчик ────────────────────────────
                    function openMediaUploader(options, callback) {
                        var frame = wp.media({
                            title: options.title || 'Выбрать файл',
                            button: {
                                text: options.button || 'Выбрать'
                            },
                            library: {
                                type: options.type || 'image'
                            },
                            multiple: false,
                        });
                        frame.on('select', function() {
                            var attachment = frame.state().get('selection').first().toJSON();
                            callback(attachment);
                        });
                        frame.open();
                    }

                    // ── Добавить картинку ─────────────────────────────────
                    $list.on('click', '.license-upload-img', function() {
                        var $item = $(this).closest('.license-repeater-item');
                        openMediaUploader({
                            title: 'Выбрать картинку',
                            button: 'Использовать',
                            type: 'image'
                        }, function(att) {
                            $item.find('.license-img-url').val(att.url);
                            $item.find('.license-img-preview').html('<img src="' + att.url + '" style="width:100%;height:100%;object-fit:cover;">');
                            $item.find('.license-remove-img').show();
                            updateValue();
                        });
                    });

                    // ── Удалить картинку ──────────────────────────────────
                    $list.on('click', '.license-remove-img', function() {
                        var $item = $(this).closest('.license-repeater-item');
                        $item.find('.license-img-url').val('');
                        $item.find('.license-img-preview').html('<span style="font-size:10px;color:#999;">нет</span>');
                        $(this).hide();
                        updateValue();
                    });

                    // ── Добавить PDF ──────────────────────────────────────
                    $list.on('click', '.license-upload-pdf', function() {
                        var $item = $(this).closest('.license-repeater-item');
                        openMediaUploader({
                            title: 'Выбрать PDF',
                            button: 'Использовать',
                            type: 'application/pdf'
                        }, function(att) {
                            $item.find('.license-pdf-url').val(att.url);
                            $item.find('.license-pdf-name').text(att.filename || att.url.split('/').pop());
                            $item.find('.license-remove-pdf').show();
                            updateValue();
                        });
                    });

                    // ── Удалить PDF ───────────────────────────────────────
                    $list.on('click', '.license-remove-pdf', function() {
                        var $item = $(this).closest('.license-repeater-item');
                        $item.find('.license-pdf-url').val('');
                        $item.find('.license-pdf-name').text('—');
                        $(this).hide();
                        updateValue();
                    });

                    // ── Добавить новую лицензию ───────────────────────────
                    $control.on('click', '.license-add-item', function() {
                        var count = $list.find('.license-repeater-item').length + 1;
                        var $item = $('<div class="license-repeater-item" style="margin-bottom:15px;padding:12px;border:1px solid #ddd;border-radius:4px;background:#fafafa;">' +
                            '<p style="margin:0 0 8px;"><strong>Лицензия ' + count + '</strong></p>' +
                            '<input type="text" placeholder="Название (напр: Свидетельство СРО)" class="license-title widefat" style="margin-bottom:8px;">' +
                            '<p style="margin:0 0 4px;font-size:12px;color:#555;">Картинка-превью:</p>' +
                            '<div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">' +
                            '<div class="license-img-preview" style="width:60px;height:60px;border:1px solid #ddd;background:#eee;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;">' +
                            '<span style="font-size:10px;color:#999;">нет</span>' +
                            '</div>' +
                            '<div>' +
                            '<input type="hidden" class="license-img-url" value="">' +
                            '<button type="button" class="button license-upload-img" style="display:block;margin-bottom:4px;">Выбрать картинку</button>' +
                            '<button type="button" class="button license-remove-img" style="display:none;color:#a00;">Удалить</button>' +
                            '</div>' +
                            '</div>' +
                            '<p style="margin:0 0 4px;font-size:12px;color:#555;">PDF файл (необязательно):</p>' +
                            '<div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">' +
                            '<input type="hidden" class="license-pdf-url" value="">' +
                            '<span class="license-pdf-name" style="font-size:12px;color:#333;flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">—</span>' +
                            '<button type="button" class="button license-upload-pdf">Выбрать PDF</button>' +
                            '<button type="button" class="button license-remove-pdf" style="display:none;color:#a00;">✕</button>' +
                            '</div>' +
                            '<button type="button" class="button license-remove-item" style="color:#a00;">Удалить лицензию</button>' +
                            '</div>');
                        $list.append($item);
                    });

                    // ── Удалить лицензию ──────────────────────────────────
                    $list.on('click', '.license-remove-item', function() {
                        $(this).closest('.license-repeater-item').remove();
                        updateValue();
                    });

                    // ── Обновить при изменении названия ──────────────────
                    $list.on('input', '.license-title', function() {
                        updateValue();
                    });

                })(jQuery);
            </script>
<?php
        }
    }
}
/*** END ДОБАВЛЯЕМ ВОЗМОЖНОСТЬ В НАСТРОЙКАХ ТЕМЫ ДОБАВИТЬ КОНТАКТЫ И КОД СЧЕТЧИКА ***/


/**
 * Получить отформатированный телефон для отображения
 */
function mytheme_get_phone($type = 'main')
{
    if ($type === 'main') {
        $country_code = get_theme_mod('mytheme_main_phone_country_code', '');
        $region_code = get_theme_mod('mytheme_main_phone_region_code', '');
        $number = get_theme_mod('mytheme_main_phone_number', '');
    } else {
        $country_code = get_theme_mod('additional_phone_country_code', '');
        $region_code = get_theme_mod('additional_phone_region_code', '');
        $number = get_theme_mod('additional_phone_number', '');
    }

    if (empty($country_code) || empty($region_code) || empty($number)) {
        return '';
    }

    return $country_code . ' (' . $region_code . ') ' . $number;
}

/**
 * Получить телефон в формате для ссылки tel:
 */
function mytheme_get_phone_link($type = 'main')
{
    if ($type === 'main') {
        $country_code = get_theme_mod('mytheme_main_phone_country_code', '');
        $region_code = get_theme_mod('mytheme_main_phone_region_code', '');
        $number = get_theme_mod('mytheme_main_phone_number', '');
    } else {
        $country_code = get_theme_mod('additional_phone_country_code', '');
        $region_code = get_theme_mod('additional_phone_region_code', '');
        $number = get_theme_mod('additional_phone_number', '');
    }

    if (empty($country_code) || empty($region_code) || empty($number)) {
        return '';
    }

    $phone_link = $country_code . $region_code . $number;
    $phone_link = preg_replace('/[^0-9+]/', '', $phone_link);

    return $phone_link;
}

/**
 * Получить email
 */
function mytheme_get_email()
{
    return get_theme_mod('mytheme_email', '');
}

/**
 * Получить ссылку на email с mailto
 */
function mytheme_get_email_link()
{
    $email = get_theme_mod('mytheme_email', '');
    return !empty($email) ? 'mailto:' . $email : '';
}

/**
 * Получить ссылку на Telegram
 */
function mytheme_get_telegram()
{
    return get_theme_mod('mytheme_telegram', '');
}

/**
 * Получить ссылку на WhatsApp
 */
function mytheme_get_whatsapp($with_params = true)
{
    $whatsapp = get_theme_mod('mytheme_whatsapp', '');

    if (empty($whatsapp)) {
        return '';
    }

    if ($with_params && strpos($whatsapp, '?') === false) {
        $whatsapp .= '?web=1&app_absent=1';
    }

    return $whatsapp;
}

/**
 * Получить ссылку на VK
 */
function mytheme_get_vk()
{
    return get_theme_mod('mytheme_vk', '');
}

/**
 * Получить адрес
 */
function mytheme_get_address()
{
    return get_theme_mod('mytheme_address', '');
}

/**
 * Получить полный адрес
 */
function mytheme_get_address_full()
{
    return get_theme_mod('mytheme_address_full', '');
}

/**
 * Получить время работы
 */
function mytheme_get_job_time()
{
    return get_theme_mod('mytheme_job_time', '');
}

/**
 * Получить ссылку на MAX
 */
function mytheme_get_max()
{
    return get_theme_mod('mytheme_max', '');
}

/**
 * Получить ссылку на Instagram
 */
function mytheme_get_instagram()
{
    return get_theme_mod('mytheme_instagram', '');
}


/**
 * Возвращает строку, в которой разрешён только тег <br> и <br />.
 * Все остальные HTML-теги экранируются.
 *
 */
function mytheme_kses_br( string $text ): string {
    return wp_kses( $text, [ 'br' => [] ] );
}

/**
 * Получить дополнительные телефоны из повторителя
 */
function mytheme_get_phones_extra()
{
    $phones_json = get_theme_mod('mytheme_phones_extra_json', '');
    $phones = json_decode($phones_json, true);
    return is_array($phones) ? $phones : array();
}

function mytheme_get_licenses()
{
    $json = get_theme_mod('mytheme_licenses_json', '');
    $data = json_decode($json, true);
    return is_array($data) ? array_filter($data, fn($item) => !empty($item['title']) || !empty($item['img_url'])) : [];
}

/**
 * Получить дополнительные email из повторителя
 */
function mytheme_get_emails_extra()
{
    $emails_json = get_theme_mod('mytheme_emails_extra_json', '');
    $emails = json_decode($emails_json, true);
    return is_array($emails) ? $emails : array();
}


// Включаем Excerpt для страниц
add_action('init', 'add_excerpt_to_pages');
function add_excerpt_to_pages()
{
    add_post_type_support('page', 'excerpt');
}


/*** ДЕЛАЕМ ПРАВИЛЬНЫЙ DESCRIPTION ДЛЯ КАЖДОЙ СТРАНИЦЫ ***/
function echo_description()
{
    if (is_category()) {
        echo wp_strip_all_tags(category_description());
    } elseif (is_product()) {
        $product = wc_get_product(get_the_ID());
        $short_description = $product->get_short_description();
        echo wp_strip_all_tags($short_description);
    } elseif (is_product_category()) {
        foreach (wp_get_post_terms(get_the_id(), 'product_cat') as $term) {
            if ($term) {
                if ($term->description) {
                    echo $term->description;
                }
            }
        }
    } elseif (is_post_type_archive('portfolio')) {
        echo 'Портфолио';
    } elseif (is_tax('portfolio-cat')) {
        $term = get_queried_object();
        echo $term->description;
    } elseif (is_shop()) {
        $shop_page_id = wc_get_page_id('shop');
        echo get_the_excerpt($shop_page_id);
    } elseif (is_page()) {
        echo get_the_excerpt();
    } else {
        echo get_the_title();
    }
}
/*** END ДЕЛАЕМ ПРАВИЛЬНЫЙ DESCRIPTION ДЛЯ КАЖДОЙ СТРАНИЦЫ ***/


/*** ДЕЛАЕМ ФАЙЛ ROBOTS.TXT ***/
add_filter('robots_txt', 'custom_robots_txt');
function custom_robots_txt($output)
{
    $output = "User-agent: *\n";
    $output .= "Disallow: *?add-to-cart=*\n";
    return $output;
}
/*** END ДЕЛАЕМ ФАЙЛ ROBOTS.TXT ***/

/*** РЕГИСТРАЦИЯ ОБЛАСТЕЙ ВИДЖЕТОВ ***/
/**
 * Регистрируем область виджетов для фильтров товаров
 */
add_action('widgets_init', 'mytheme_register_sidebars');
function mytheme_register_sidebars()
{
    // Виджеты для фильтров в архиве товаров
    register_sidebar(array(
        'name' => 'Фильтры товаров',
        'id' => 'shop-filters-sidebar',
        'description' => 'Виджеты для фильтрации товаров (отображаются в блоке фильтров)',
        'before_widget' => '<div id="%1$s" class="widget filter-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="filter-widget-title">',
        'after_title' => '</h4>',
    ));
}

/**
 * Подключение скриптов
 */
function mytheme_enqueue_scripts()
{
    // Подключаем встроенный jQuery WordPress
    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'mytheme_enqueue_scripts');


/**
 * Отключаем стандартные хлебные крошки WooCommerce
 */
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

require_once get_template_directory() . '/inc/transliteration.php';


/*** ПОИСК ***/
/**
 * Фильтруем поиск — только товары WooCommerce.
 * Работает для любого поискового запроса включая /?s=...&post_type=product
 */
add_action('pre_get_posts', 'mytheme_search_only_products');
function mytheme_search_only_products($query)
{
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        $query->set('post_type', ['product']);
        $query->set('post_status', 'publish');
        $query->set('posts_per_page', apply_filters('loop_shop_per_page', 18));
    }
}

add_filter('template_include', 'mytheme_force_search_template', 99);
function mytheme_force_search_template($template)
{
    if (isset($_GET['s']) && !empty($_GET['s'])) {
        $search_template = get_template_directory() . '/search.php';
        if (file_exists($search_template)) {
            return $search_template;
        }
    }
    return $template;
}

/* На странице поиска убираем "Shop" из хлебных крошек */
add_filter('woocommerce_get_breadcrumb', 'mytheme_search_breadcrumb');
function mytheme_search_breadcrumb($breadcrumb)
{
    if (!isset($_GET['s'])) {
        return $breadcrumb;
    }
    return [
        [sprintf('Результат поиска &laquo;%s&raquo;', esc_html(get_search_query())), ''],
    ];
}

/*** КОНЕЦ ПОИСКА ***/

// Подключение скриптов и стилей
add_action('wp_enqueue_scripts', 'enqueue_product_filters_scripts');
function enqueue_product_filters_scripts()
{
    if (is_shop() || is_product_category() || is_product_tag()) {
        // Подключаем jQuery если не подключен
        wp_enqueue_script('jquery');

        wp_enqueue_script('catalog-ajax', get_template_directory_uri() . '/js/catalog-ajax.js', ['jquery'], '1.0.0', true);
        wp_localize_script('catalog-ajax', 'catalogAjax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('catalog_filter_nonce'),
            'action'   => 'catalog_filter_products',
        ]);
    }
}


require_once get_template_directory() . '/inc/ajax-catalog.php';


add_filter('woocommerce_product_tabs', function($tabs) {
    unset($tabs['reviews']);
    return $tabs;
}, 98);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);

// Количество товаров в архиве WooCommerce
add_filter('loop_shop_per_page', function() {
    return 18;
}, 20);