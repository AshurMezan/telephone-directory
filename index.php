<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <!--    <link rel="stylesheet" href="CSS/iconsbootstrap.css">-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="icon" type="image/png" href="IMG/icon/phone.png">
    <title>Телефонный справочник</title>
    <style>
    </style>
</head>
<body>

<!-- Блок для основного филиала START -->
<div class="container">

    <h1 class="text-uppercase title_for_main_table">список телефонов сотрудников</h1>
    <h2 class="text-center text-uppercase title_for_main_table">гку цзн то</h2>

    <?php require_once 'templates/button`s.php' ?>
    <div class="view-container" data-view="container_for_List_1" style="display: block;">
        <h3 class="mt-4 mb-3">Поиск по ФИО</h3>

        <!-- Поле поиска + сообщение -->
        <div class="input-group mb-4">
            <input type="text"
                   class="form-control"
                   id="searchFio"
                   placeholder="Введите фамилию или имя (например: Азимов)"
                   aria-label="Поиск по ФИО">
            <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                <i class="bi bi-x-lg"></i> Очистить
            </button>
        </div>

        <!-- Сообщение, если ничего не найдено -->
        <div id="searchResult" class="alert alert-info d-none mb-3" role="alert">
            Ничего не найдено по запросу
        </div>

        <!-- Таблица -->
        <div class="table-responsive">
            <table id="main-table" class="table table-striped table-hover table-bordered align-middle">
                <thead class="table-dark">
                <tr>
                    <th class="text-uppercase text-center">должность</th>
                    <th class="text-uppercase text-center">ф.и.о.</th>
                    <th class="text-uppercase text-center">город</th>
                    <th class="text-uppercase text-center">внутр.</th>
                    <th class="text-uppercase text-center">№ кабинета</th>
                    <th class="text-uppercase text-center">E-mail</th>
                </tr>
                </thead>
                <tbody id="phoneTableBody">
                <?php
                $jsonFile = 'data.json';
                if (file_exists($jsonFile) && filesize($jsonFile) > 0) {
                    $jsonData = json_decode(file_get_contents($jsonFile), true);
                    $data = $jsonData['main'] ?? [];
                    if (is_array($data) && !empty($data)) {
                        foreach ($data as $row) {
                            if ($row['type'] === 'department') {
                                echo '<tr class="table-secondary fw-bold department-row">
                                <td colspan="6" class="text-center py-3 fs-5">' . htmlspecialchars($row['name']) . '</td>
                            </tr>';
                            } else {
                                echo "<tr class='employee-row'>
                                <td>" . htmlspecialchars($row['position'] ?? '') . "</td>
                                <td class='fio-cell'>" . htmlspecialchars($row['fio'] ?? '') . "</td>
                                <td>" . htmlspecialchars($row['city'] ?? '') . "</td>
                                <td>" . htmlspecialchars($row['internal_phone'] ?? '') . "</td>
                                <td>" . htmlspecialchars($row['cabinet'] ?? '') . "</td>
                                <td>" . htmlspecialchars($row['vacation'] ?? '') . "</td>
                            </tr>";
                            }
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center text-muted py-5">Данные не найдены в загруженном файле.</td></tr>';
                    }
                } else {
                    echo '<tr><td colspan="6" class="text-center text-muted py-5">
                        Данные не загружены. Нажмите кнопку выше, чтобы загрузить Excel-файл.
                      </td></tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Филиалы  т.е. раздел для филиалов (List_2)-->
    <div class="view-container" data-view="container_for_List_2" style="display: none;">
        <h3 class="mt-4 mb-3">Поиск по ФИО в филиалах</h3>

        <div class="input-group mb-4">
            <input type="text"
                   class="form-control"
                   id="searchFioBranches"
                   placeholder="Введите фамилию или имя (например: Алигьери)"
                   aria-label="Поиск по ФИО в филиалах">
            <button class="btn btn-outline-secondary" type="button" id="clearSearchBranches">
                <i class="bi bi-x-lg"></i> Очистить
            </button>
        </div>

        <div id="searchResultBranches" class="alert alert-info d-none mb-3" role="alert">
            Ничего не найдено по запросу
        </div>
        <div class="table-responsive ">
            <table id="branches-table" class="table table-striped table-hover table-bordered align-middle">
                <thead class="table-dark custom-height">
                <tr>
                    <th class="text-uppercase text-center">должность</th>
                    <th class="text-uppercase text-center">ф.и.о.</th>
                    <th class="text-uppercase text-center">город</th>
                    <th class="text-uppercase text-center">внутр.</th>
                    <th class="text-uppercase text-center">моб.</th>
                    <th class="text-uppercase text-center">E-mail</th>
                </tr>
                </thead>
                <tbody id="branchesTableBody">
                <?php
                if (file_exists($jsonFile) && filesize($jsonFile) > 0) {
                    $branches = $jsonData['branches'] ?? [];
                    if (is_array($branches) && !empty($branches)) {
                        foreach ($branches as $branch) {
                            // Наличие внутреннего телефона → six_columns
                            $hasSixColumns = false;
                            foreach ($branch['employees'] as $item) {
                                if (isset($item['internal']) && $item['internal'] !== '') {
                                    $hasSixColumns = true;
                                    break;
                                }
                            }

                            // Содержит "ОГКУ ЦЗН ТО" → title_branch
                            $isTitleBranch = (stripos($branch['info'], 'ОГКУ ЦЗН ТО') !== false);

                            $trClass = 'table-primary fw-bold';
                            if ($hasSixColumns) $trClass .= ' six_columns';
                            if ($isTitleBranch) $trClass .= ' title_branch';

                            echo '<tr class="' . trim($trClass) . '">
                        <td colspan="6" class="text-center py-3 fs-5">' . nl2br(htmlspecialchars($branch['info'])) . '</td>
                    </tr>';

                            foreach ($branch['employees'] as $item) {
                                if ($item['type'] === 'subdepartment') {
                                    echo '<tr class="table-secondary fw-bold">
                                <td colspan="6" class="text-center py-3 fs-5">' . htmlspecialchars($item['name']) . '</td>
                            </tr>';
                                } else {
                                    echo "<tr>
                                <td>" . htmlspecialchars($item['position'] ?? '') . "</td>
                                <td>" . htmlspecialchars($item['fio'] ?? '') . "</td>
                                <td>" . htmlspecialchars($item['city'] ?? '') . "</td>
                                <td>" . htmlspecialchars($item['internal'] ?? '') . "</td>
                                <td>" . htmlspecialchars($item['mobile'] ?? '') . "</td>
                                <td>" . htmlspecialchars($item['vacation'] ?? '') . "</td>
                            </tr>";
                                }
                            }
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center text-muted py-5">Филиалы не найдены.</td></tr>';
                    }
                } else {
                    echo '<tr><td colspan="6" class="text-center text-muted py-5">Данные не загружены.</td></tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Фиксированная кнопка "Наверх" -->
<button type="button" id="back-to-top" class="btn btn-primary btn-lg rounded-circle shadow"
        style="position: fixed; bottom: 30px; right: 30px; z-index: 5; display: none;">
    <i class="bi bi-arrow-up"></i>
</button>
<!-- Блок для основного филиала END -->
<span id="login" class="opacity-0">Admin</span>
<span id="password" class="opacity-0">Admin</span>

<?php
require_once 'templates/modal-upload.php';
require_once 'templates/modal-login.php';
?>

<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="JS/main.js"></script>
<script src="JS/loginModal.js"></script>
<script src="JS/scroll.js"></script>
<script src="JS/switchBranch.js"></script>
<script src="JS/buttonUp.js"></script>
<!--<script src="JS/givMeList.js"></script>-->
<script src="JS/searchMain.js"></script>
<script>// Поиск по ФИО в таблице филиалов (List_2)
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchFioBranches');
        const clearBtn = document.getElementById('clearSearchBranches');
        const resultMessage = document.getElementById('searchResultBranches');
        const tableBody = document.getElementById('branchesTableBody');

        if (!searchInput || !tableBody) return;

        // Сохраняем исходное состояние всех строк (для восстановления при очистке)
        let originalRows = [];
        tableBody.querySelectorAll('tr').forEach(row => {
            originalRows.push({
                element: row,
                display: row.style.display || 'table-row'
            });
        });

        function performSearch() {
            const query = searchInput.value.trim().toLowerCase();
            const rows = tableBody.querySelectorAll('tr');

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

            // Сначала скрываем абсолютно всё
            rows.forEach(row => {
                row.style.display = 'none';
            });

            // Ищем совпадения только среди строк сотрудников (не заголовки)
            rows.forEach(row => {
                // Пропускаем заголовки филиалов и подотделов
                if (row.classList.contains('table-primary') || row.classList.contains('table-secondary')) {
                    return;
                }

                const fioCell = row.querySelector('td:nth-child(2)'); // Вторая колонка — Ф.И.О.
                if (!fioCell) return;

                const fioText = fioCell.textContent.trim().toLowerCase();

                if (fioText.includes(query)) {
                    found = true;

                    // Показываем найденную строку сотрудника
                    row.style.display = 'table-row';

                    // Показываем ближайший заголовок филиала/подотдела сверху
                    let current = row.previousElementSibling;
                    while (current) {
                        if (current.classList.contains('table-primary') ||
                            current.classList.contains('table-secondary')) {
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

        // События ввода
        searchInput.addEventListener('input', performSearch);

        // Очистка поиска
        clearBtn.addEventListener('click', function () {
            searchInput.value = '';
            performSearch();
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
</script>
</body>
</html>