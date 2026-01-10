<!-- Модальное окно авторизации -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Авторизация</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="loginForm">
                    <div class="mb-3">
                        <label for="loginInput" class="form-label">Логин</label>
                        <input type="text" class="form-control" id="loginInput" placeholder="Введите логин" minlength="4" maxlength="10" required>
                    </div>
                    <div class="mb-3">
                        <label for="passwordInput" class="form-label">Пароль</label>
                        <input type="password" class="form-control" id="passwordInput" placeholder="Введите пароль" minlength="5" maxlength="15" required>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary" id="entrance">Войти</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>