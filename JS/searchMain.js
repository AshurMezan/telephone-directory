// Поиск по ФИО с показом только нужного отдела и сотрудника
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchFio');
    const clearBtn = document.getElementById('clearSearch');
    const resultMessage = document.getElementById('searchResult');
    const tableBody = document.getElementById('phoneTableBody');

    if (!searchInput || !tableBody) return;

    // Сохраняем исходное состояние всех строк (для восстановления)
    let originalRows = [];
    tableBody.querySelectorAll('tr').forEach(row => {
        originalRows.push({
            element: row,
            display: row.style.display || 'table-row'
        });
    });

    function performSearch() {
        const query = searchInput.value.trim().toLowerCase();
        const employeeRows = tableBody.querySelectorAll('tr.employee-row');
        const departmentRows = tableBody.querySelectorAll('tr.department-row');

        let found = false;
        let firstFoundRow = null;

        // Сбрасываем всё к исходному состоянию
        originalRows.forEach(item => {
            item.element.style.display = item.display;
        });

        if (query === '') {
            resultMessage.classList.add('d-none');
            return;
        }

        // Сначала скрываем всех
        employeeRows.forEach(row => row.style.display = 'none');
        departmentRows.forEach(row => row.style.display = 'none');

        // Ищем совпадения
        employeeRows.forEach(row => {
            const fioCell = row.querySelector('.fio-cell');
            if (!fioCell) return;

            const fioText = fioCell.textContent.trim().toLowerCase();

            if (fioText.includes(query)) {
                found = true;

                // Показываем найденную строку сотрудника
                row.style.display = 'table-row';

                // Находим ближайший заголовок отдела сверху
                let current = row.previousElementSibling;
                while (current) {
                    if (current.classList.contains('department-row') || current.classList.contains('main_table_title')) {
                        current.style.display = 'table-row';
                        break;
                    }
                    current = current.previousElementSibling;
                }

                // Запоминаем первую найденную строку для скролла
                if (!firstFoundRow) {
                    firstFoundRow = row;
                }
            }
        });

        if (found && firstFoundRow) {
            resultMessage.classList.add('d-none');

            // Плавный скролл к первому найденному сотруднику
            firstFoundRow.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        } else {
            resultMessage.classList.remove('d-none');
        }
    }

    // События
    searchInput.addEventListener('input', performSearch);

    clearBtn.addEventListener('click', function () {
        searchInput.value = '';
        performSearch(); // Восстанавливаем всё
        searchInput.focus();
    });

    // Поиск по Enter
    searchInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            performSearch();
        }
    });
});