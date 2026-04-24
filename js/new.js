// Функция открытия галереи
function galleryOn(galleryId) {
    const galleryWrapper = document.getElementById('galleryWrapper');
    const prevBtn = document.querySelector('#gallery-product-modal .carousel-control-prev');
    const nextBtn = document.querySelector('#gallery-product-modal .carousel-control-next');

    // Получаем количество изображений на странице
    const carouselItems = document.querySelectorAll('#carousel-product .carousel-item').length;

    // Показываем/скрываем кнопки навигации
    if (carouselItems > 1) {
        prevBtn.style.display = 'flex';
        nextBtn.style.display = 'flex';
    } else {
        prevBtn.style.display = 'none';
        nextBtn.style.display = 'none';
    }

    // Показываем галерею
    galleryWrapper.style.display = 'block';
}

// Функция закрытия галереи
function closeGallery() {
    var wrapper = document.getElementById('galleryWrapper');
    if (!wrapper) return;
    wrapper.style.display = 'none';
}

// Закрытие по Escape
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closeGallery();
    }
});

// Логика прилипающего меню при скролле
function prilipalo() {
    var headerNavBottom = document.querySelector('.header-nav-bottom');
    var headerNavTop = document.querySelector('.header-nav-top');

    if (!headerNavBottom) return;

    // Функция для установки фиксированного положения на мобильных
    function setMobileFixed() {
        var screenWidth = window.innerWidth;

        if (screenWidth < 992) {
            var menuHeight = headerNavBottom.offsetHeight;
            headerNavBottom.classList.add('fixed-top');
            headerNavBottom.style.position = 'fixed';
            headerNavBottom.style.top = '0';
            document.body.style.paddingTop = menuHeight + 'px';
        }
    }

    // Функция для проверки и установки фиксированного положения на десктопе
    function checkDesktopPosition() {
        var screenWidth = window.innerWidth;
        var prokrutka = window.pageYOffset;

        if (screenWidth >= 992) {
            var topMenuHeight = headerNavTop ? headerNavTop.offsetHeight : 57;

            if (prokrutka > topMenuHeight) {
                var menuHeight = headerNavBottom.offsetHeight;
                headerNavBottom.classList.add('fixed-top');
                headerNavBottom.style.position = 'fixed';
                headerNavBottom.style.top = '0';
                document.body.style.paddingTop = menuHeight + 'px';
            }
        }
    }

    // Применяем стили сразу при загрузке
    setMobileFixed();
    checkDesktopPosition();

    // Обработчик скролла
    window.addEventListener('scroll', function () {
        var prokrutka = window.pageYOffset;
        var screenWidth = window.innerWidth;

        if (screenWidth >= 992) {
            // Для десктопа
            var topMenuHeight = headerNavTop ? headerNavTop.offsetHeight : 57;

            if (prokrutka > topMenuHeight) {
                var menuHeight = headerNavBottom.offsetHeight;

                headerNavBottom.classList.add('fixed-top');
                headerNavBottom.classList.add('scrolled');
                headerNavBottom.style.position = 'fixed';
                headerNavBottom.style.top = '0';

                document.body.style.paddingTop = menuHeight + 'px';
            } else {
                headerNavBottom.classList.remove('fixed-top');
                headerNavBottom.classList.remove('scrolled');
                headerNavBottom.style.position = 'relative';
                headerNavBottom.style.top = '';

                document.body.style.paddingTop = '0';
            }
        }

        // Для мобилки
        if (screenWidth < 992) {
            if (prokrutka > 0) {
                headerNavBottom.classList.add('scrolled');
            } else {
                headerNavBottom.classList.remove('scrolled');
            }
        }
    });

    // Обработчик изменения размера окна
    window.addEventListener('resize', function () {
        var screenWidth = window.innerWidth;

        if (screenWidth < 992) {
            setMobileFixed();
        } else {
            // Сброс при переходе на десктоп
            headerNavBottom.classList.remove('fixed-top');
            headerNavBottom.style.position = 'relative';
            headerNavBottom.style.top = '';
            document.body.style.paddingTop = '0';
            // Проверяем позицию после сброса
            checkDesktopPosition();
        }
    });
}

// Вызываем функцию после загрузки DOM
document.addEventListener('DOMContentLoaded', prilipalo);


/* Убираем сообщение об успешной отправки */
function modalClose() {
    document.getElementById('background-msg').style.display = 'none';
    document.getElementById('message').style.display = 'none';
    document.getElementById('btn-close').style.display = 'none';
}