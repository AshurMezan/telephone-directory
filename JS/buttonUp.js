// Показываем/скрываем кнопку "Наверх"
window.addEventListener('scroll', function() {
    const backToTop = document.getElementById('back-to-top');
    if (window.scrollY > 300) {  // Показываем после прокрутки 300px
        backToTop.classList.add('show');
    } else {
        backToTop.classList.remove('show');
    }
});

// Плавный скролл наверх при клике
document.getElementById('back-to-top').addEventListener('click', function(e) {
    e.preventDefault();
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});