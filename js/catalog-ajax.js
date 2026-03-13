/**
 * AJAX фильтрация каталога
 * Файл: js/catalog-ajax.js
 */

(function ($) {
    'use strict';

    var $container = $('#catalog-products-container');
    var $grid = $('#catalog-products-grid');
    var $pagination = $('#catalog-pagination-container');
    var $sidebar = $('.catalog-sidebar');
    var currentPage = 1;
    var isLoading = false;

    if (!$container.length) return;

    var ajaxUrl = catalogAjax.ajax_url;
    var nonce = $container.data('nonce') || catalogAjax.nonce;
    var action = $container.data('action') || catalogAjax.action;
    var category = $container.data('category') || '';

    // ── Лоадер: оверлей поверх грида, кружок строго по центру ───────
    var loaderHtml =
        '<div class="catalog-loader">' +
        '<div class="spinner-border text-secondary" role="status">' +
        '<span class="visually-hidden">Загрузка...</span>' +
        '</div>' +
        '</div>';

    if (!document.getElementById('catalog-loader-style')) {
        var style = document.createElement('style');
        style.id = 'catalog-loader-style';
        style.textContent =
            '#catalog-products-container { position: relative; min-height: 200px; }' +
            '.catalog-loader {' +
            'position: absolute;' +
            'inset: 0;' +
            'display: flex;' +
            'align-items: center;' +
            'justify-content: center;' +
            'background: rgba(255,255,255,0.65);' +
            'z-index: 10;' +
            '}';
        document.head.appendChild(style);
    }

    function showLoader() {
        if (!$container.find('.catalog-loader').length) {
            $container.append(loaderHtml);
        }
        isLoading = true;
    }

    function hideLoader() {
        $container.find('.catalog-loader').remove();
        isLoading = false;
    }

    // ── Собрать данные фильтров для POST ─────────────────────────────
    function collectFilters() {
        var data = {
            action: action,
            nonce: nonce,
            paged: currentPage,
            category: category,
        };

        $sidebar.find('input[type="checkbox"]:checked').each(function () {
            var name = $(this).attr('name');
            if (!name) return;
            if (!data[name]) data[name] = [];
            data[name].push($(this).val());
        });

        var $orderby = $('#catalog-orderby');
        if ($orderby.length) {
            var val = $orderby.val().split('-');
            data.orderby = val[0] || 'menu_order';
            data.order = val[1] || 'ASC';
        }

        return data;
    }

    // ── Обновить URL через pushState ─────────────────────────────────
    function updateUrl() {
        var params = new URLSearchParams();
        var grouped = {};

        $sidebar.find('input[type="checkbox"]:checked').each(function () {
            var name = $(this).attr('name').replace('[]', '');
            if (!grouped[name]) grouped[name] = [];
            grouped[name].push($(this).val());
        });

        $.each(grouped, function (name, values) {
            params.set(name.replace(/^filter_/, ''), values.join(','));
        });

        var $orderby = $('#catalog-orderby');
        if ($orderby.length && $orderby.val() !== 'menu_order-ASC') {
            params.set('orderby', $orderby.val());
        }

        var basePath = window.location.pathname
            .replace(/\/page\/\d+\/?$/, '')
            .replace(/\/$/, '');

        var pagePath = currentPage > 1 ? basePath + '/page/' + currentPage + '/' : basePath + '/';

        var qs = params.toString();
        var newUrl = pagePath + (qs ? '?' + qs : '');
        history.pushState({ paged: currentPage }, '', newUrl);
    }

    // ── AJAX запрос ──────────────────────────────────────────────────
    function doFilter(scrollToTop) {
        if (isLoading) return;
        showLoader();
        updateUrl();

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: collectFilters(),
            success: function (response) {
                hideLoader();
                if (!response.success) return;

                $grid.html(response.data.products);
                $pagination.html(response.data.pagination);

                if (scrollToTop !== false) {
                    $('html, body').animate({ scrollTop: $container.offset().top - 100 }, 300);
                }
            },
            error: function () {
                hideLoader();
            }
        });
    }

    // ── Кнопка «назад» в браузере ────────────────────────────────────
    window.addEventListener('popstate', function () {
        window.location.reload();
    });

    // ── При загрузке страницы: восстановить чекбоксы из URL ──────────
    (function initFromUrl() {
        var params = new URLSearchParams(window.location.search);
        params.forEach(function (value, key) {
            if (key.indexOf('pa_') !== 0) return;
            var filterName = 'filter_' + key;
            value.split(',').forEach(function (val) {
                $sidebar.find('input[name="' + filterName + '[]"][value="' + val + '"]')
                    .prop('checked', true);
            });
        });

        var pageMatch = window.location.pathname.match(/\/page\/(\d+)\/?$/);
        if (pageMatch) currentPage = parseInt(pageMatch[1], 10);
    })();

    // ── Обработчики ──────────────────────────────────────────────────
    $sidebar.on('change', 'input[type="checkbox"]', function () {
        currentPage = 1;
        doFilter();
    });

    $(document).on('change', '#catalog-orderby', function () {
        currentPage = 1;
        doFilter();
    });

    $(document).on('click', '#catalog-pagination-container .catalog-pagination__btn[data-page]', function (e) {
        e.preventDefault();
        var page = parseInt($(this).data('page'), 10);
        if (!page || isLoading) return;
        currentPage = page;
        doFilter();
    });

})(jQuery);