/* Функция "Выезжало" */
function vyezjalo() {
    window.addEventListener('scroll', function() {
        var prokrutka = window.pageYOffset;
        var screenWidth = window.innerWidth;  

        if (screenWidth >= 992) {  
            if (prokrutka > 400) {
                document.getElementById('sliding-header').style.top = '0px';
            } else if (prokrutka <= 400) {
                document.getElementById('sliding-header').style.top = '-120px';
            }
        }
    });
}

// Вызываем функцию после загрузки DOM
document.addEventListener('DOMContentLoaded', vyezjalo  );
