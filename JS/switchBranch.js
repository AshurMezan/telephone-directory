document.addEventListener('DOMContentLoaded', function () {
    const switchButtons = document.querySelectorAll('.switch-view');

    switchButtons.forEach(button => {
        button.addEventListener('click', function () {
            const targetView = this.getAttribute('data-target');

            // Скрываем все контейнеры
            document.querySelectorAll('.view-container').forEach(container => {
                container.style.display = 'none';
            });

            // Показываем нужный
            const targetContainer = document.querySelector(`[data-view="${targetView}"]`);
            if (targetContainer) {
                targetContainer.style.display = 'block';
            }

            // Подсвечиваем активную кнопку (опционально)
            switchButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
        });
    });
});