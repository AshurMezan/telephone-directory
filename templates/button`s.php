<div style="display: flex; justify-content: space-between;" class="container_for_buttons">
    <div class="text-end mb-3">
        <!-- Кнопка Тюмень — активна по умолчанию -->
        <button type="button"
                class="btn btn-primary me-2 switch-view active"
                data-target="container_for_List_1"
                data-active="true">
            Тюмень
        </button>

        <!-- Кнопка Филиалы -->
        <button type="button"
                class="btn btn-primary me-2 switch-view"
                data-target="container_for_List_2"
                data-active="false">
            Филиалы
        </button>
    </div>

    <div class="text-end mb-3">
        <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#loginModal">
            Авторизация
        </button>
        <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#uploadModal">
            Загрузить Excel-файл
        </button>
    </div>
</div>