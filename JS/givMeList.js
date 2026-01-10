/**
 * Обрабатывает таблицу филиалов (List_2):
 * - Для филиалов без внутреннего телефона (нет класса six_columns):
 *   — удаляет колонку "внутр." в строках сотрудников
 *   — растягивает колонку "моб." на 2 ячейки
 * - Пропускает филиалы с классом six_columns (где есть внутр. телефоны)
 * - В конце корректирует шапку таблицы (thead)
 */
function processBranchesTable() {
    const tableBodyBranches = document.querySelector('#branchesTableBody');
    if (!tableBodyBranches) return;

    // Находим все заголовки филиалов с классом title_branch — это наши ориентиры
    const separators = tableBodyBranches.querySelectorAll('tr.title_branch');

    for (let i = 0; i < separators.length; i++) {
        const currentSeparatorTr = separators[i];           // текущий заголовок филиала
        const nextSeparatorTr = separators[i + 1] || null;   // следующий заголовок или null

        // Если филиал имеет внутренние телефоны — пропускаем обработку
        if (currentSeparatorTr.classList.contains('six_columns')) {
            console.log(`Пропускаем филиал: "${currentSeparatorTr.textContent.trim().substring(0, 60)}..." (есть внутр. телефоны)`);
            continue;
        }

        // Границы блока филиала
        let currentRow = currentSeparatorTr.nextElementSibling;

        while (currentRow && currentRow !== nextSeparatorTr) {
            // Пропускаем строки подотделов (table-secondary)
            if (currentRow.classList.contains('table-secondary')) {
                currentRow = currentRow.nextElementSibling;
                continue;
            }

            // Обрабатываем только строки сотрудников (6 ячеек)
            if (currentRow.children.length === 6) {
                const cells = currentRow.children;

                // Удаляем колонку "внутр." (индекс 3)
                cells[3]?.remove();

                // Растягиваем колонку "моб." (теперь она на индексе 3)
                if (cells[3]) {
                    cells[3].setAttribute('colspan', '2');
                    cells[3].classList.add('merged-mobile');
                    cells[3].style.textAlign = 'center'; // для красоты
                }
            }

            currentRow = currentRow.nextElementSibling;
        }
    }

    // Корректируем шапку таблицы (thead) — удаляем "внутр." и растягиваем "моб."
    adjustBranchesTableHeader();
}

/**
 * Удаляет колонку "внутр." из шапки таблицы филиалов
 * и растягивает заголовок "моб." на 2 колонки
 */
function adjustBranchesTableHeader() {
    const branchesTable = document.getElementById('branches-table');
    if (!branchesTable) return;

    const headerRow = branchesTable.querySelector('thead tr');
    if (!headerRow || headerRow.children.length < 5) return;

    const cells = headerRow.children;

    // Удаляем 4-ю ячейку ("внутр.", индекс 3)
    cells[3]?.remove();

    // Растягиваем новую 4-ю ячейку ("моб.", теперь индекс 3)
    if (cells[3]) {
        cells[3].setAttribute('colspan', '2');
        cells[3].style.textAlign = 'center';
    }
}

// Запускаем обработку сразу после загрузки DOM или после AJAX-обновления таблицы
document.addEventListener('DOMContentLoaded', processBranchesTable);

// Если таблица обновляется через AJAX (main.js), вызывай эту функцию после вставки нового HTML:
// processBranchesTable();

console.log('Скрипт работает.')