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

    <?php require 'templates/button`s.php' ?>
    <div class="view-container" data-view="container_for_List_1" style="display: block;">
        <!-- Основной справочник -->

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
    </div>
    <!-- Филиалы  т.е. раздел для филиалов (List_2)-->
    <div class="view-container" data-view="container_for_List_2" style="display: none;">

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
</body>
</html>