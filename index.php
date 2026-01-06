<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/iconsbootstrap.css">
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="icon" type="image/png" href="IMG/icon/phone.png">
    <title>Телефонный справочник</title>
</head>
<body>

<!-- Блок для основного филиала START -->
<div class="container">
    <h1 class="text-uppercase">список телефонов сотрудников</h1>
    <h2 class="text-center text-uppercase">гку цзн то</h2>

    <div class="text-end mb-3">
        <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#loginModal">
            Авторизация
        </button>
        <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#uploadModal">
            Загрузить Excel-файл
        </button>
    </div>

    <!-- Основной справочник -->
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle">
            <thead class="table-dark sticky-top">
            <tr>
                <th class="text-uppercase text-center">должность</th>
                <th class="text-uppercase text-center">ф.и.о.</th>
                <th class="text-uppercase text-center">город</th>
                <th class="text-uppercase text-center">внутр.</th>
                <th class="text-uppercase text-center">№ кабинета</th>
                <th class="text-uppercase text-center">отпуск</th>
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
                            echo '<tr class="table-secondary fw-bold">
                                    <td colspan="6" class="text-center py-3 fs-5">' . htmlspecialchars($row['name']) . '</td>
                                </tr>';
                        } else {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['position'] ?? '') . "</td>
                                    <td>" . htmlspecialchars($row['fio'] ?? '') . "</td>
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

    <!-- Филиалы -->
    <h2 class="mt-5 text-center text-uppercase pb-5">список телефонов сотрудников отделений гку цзн то</h2>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle">
            <thead class="table-dark sticky-top">
            <tr>
                <th class="text-uppercase text-center">должность</th>
                <th class="text-uppercase text-center">ф.и.о.</th>
                <th class="text-uppercase text-center">город</th>
                <th class="text-uppercase text-center">моб.</th>
                <th class="text-uppercase text-center">отпуск</th>
            </tr>
            </thead>
            <tbody id="branchesTableBody">
            <?php
            if (file_exists($jsonFile) && filesize($jsonFile) > 0) {
                $branches = $jsonData['branches'] ?? [];
                if (is_array($branches) && !empty($branches)) {
                    foreach ($branches as $branch) {
                        echo '<tr class="table-primary fw-bold">
                                <td colspan="5" class="text-center py-3 fs-5">' . nl2br(htmlspecialchars($branch['info'])) . '</td>
                            </tr>';

                        foreach ($branch['employees'] as $item) {
                            if ($item['type'] === 'subdepartment') {
                                echo '<tr class="table-secondary fw-bold">
                                        <td colspan="5" class="text-center py-3 fs-5">' . htmlspecialchars($item['name']) . '</td>
                                    </tr>';
                            } else {
                                echo "<tr>
                                        <td>" . htmlspecialchars($item['position'] ?? '') . "</td>
                                        <td>" . htmlspecialchars($item['fio'] ?? '') . "</td>
                                        <td>" . htmlspecialchars($item['city'] ?? '') . "</td>
                                        <td>" . htmlspecialchars($item['mobile'] ?? '') . "</td>
                                        <td>" . htmlspecialchars($item['vacation'] ?? '') . "</td>
                                    </tr>";
                            }
                        }
                    }
                } else {
                    echo '<tr><td colspan="5" class="text-center text-muted py-5">Филиалы не найдены.</td></tr>';
                }
            } else {
                echo '<tr><td colspan="5" class="text-center text-muted py-5">Данные не загружены.</td></tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Блок для основного филиала END -->


<!-- Модальное окно -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Загрузка Excel-файла</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadForm" enctype="multipart/form-data">
                    <div id="dropZone" class="text-muted">
                        <i class="bi bi-cloud-upload"></i>
                        <p class="mb-0">Перетащите файл сюда</p>
                        <small>или</small>
                        <p class="mb-0">нажмите для выбора</p>
                        <div id="fileName"></div>
                    </div>

                    <input type="file" id="excelFile" name="excelFile" accept=".xlsx" class="d-none" required>

                    <div id="uploadStatus"></div>

                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Загрузить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
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

<span id="login" class="opacity-0">Admin</span>
<span id="password" class="opacity-0">Admin</span>
<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="JS/main.js"></script>
<script src="JS/loginModal.js"></script>
</body>
</html>