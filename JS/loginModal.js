/*Скрипт авторизации, чтобы открылась кнопка "Загрузить файл"*/
// === Косметическая авторизация ===

// Элементы DOM
const btnAuth = document.querySelector('button[data-bs-target="#loginModal"]'); // Кнопка "Авторизация"
const btnUpload = document.querySelector('button[data-bs-target="#uploadModal"]'); // Кнопка "Загрузить Excel-файл"

const loginInput = document.getElementById('loginInput');
const passwordInput = document.getElementById('passwordInput');
const loginForm = document.getElementById('loginForm');

// Скрытые значения для проверки (регистр не важен для логина)
const correctLogin = document.getElementById('login').textContent.trim();
const correctPassword = document.getElementById('password').textContent.trim();

// При загрузке страницы скрываем кнопку загрузки
document.addEventListener('DOMContentLoaded', () => {
    if (btnUpload) {
        btnUpload.style.display = 'none';
    }
});

// Обработка формы входа
loginForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const enteredLogin = loginInput.value.trim();
    const enteredPassword = passwordInput.value;

    // Проверка (логин нечувствителен к регистру)
    if (enteredLogin.toLowerCase() === correctLogin.toLowerCase() && enteredPassword === correctPassword) {
        // Успех
        if (btnAuth) {
            btnAuth.style.display = 'none'; // Скрываем "Авторизация"
        }
        if (btnUpload) {
            btnUpload.style.display = 'inline-block'; // Показываем "Загрузить"
        }

        // Закрываем модальное окно
        const loginModalInstance = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
        if (loginModalInstance) {
            loginModalInstance.hide();
        }

        // Очищаем поля
        loginInput.value = '';
        passwordInput.value = '';

        // Опционально: приветствие
        // alert(`Добро пожаловать, ${enteredLogin}!`);

    } else {
        // Ошибка — можно добавить визуальное уведомление
        alert('Неверный логин или пароль');
    }
});