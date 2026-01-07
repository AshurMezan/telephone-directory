<?php
// upload.php

require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

header('Content-Type: application/json; charset=utf-8');

$response = ['success' => false, 'error' => '', 'mainHtml' => '', 'branchesHtml' => ''];

if (!isset($_FILES['excelFile']) || $_FILES['excelFile']['error'] !== UPLOAD_ERR_OK) {
    $response['error'] = 'Файл не загружен или произошла ошибка при загрузке.';
    echo json_encode($response);
    exit;
}

$fileTmpPath = $_FILES['excelFile']['tmp_name'];
$fileName = $_FILES['excelFile']['name'];

$extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
if ($extension !== 'xlsx') {
    $response['error'] = 'Разрешены только файлы .xlsx';
    echo json_encode($response);
    exit;
}

try {
    $spreadsheet = IOFactory::load($fileTmpPath);

    // === List_1: основной справочник ===
    $worksheet1 = $spreadsheet->getSheetByName('List_1');
    $mainData = [];
    $highestRow1 = $worksheet1->getHighestRow();

    for ($row = 3; $row <= $highestRow1; $row++) { // начинаем с 3-й строки
        $position       = trim($worksheet1->getCell('A' . $row)->getValue() ?? '');
        $fio            = trim($worksheet1->getCell('B' . $row)->getValue() ?? '');
        $city           = trim($worksheet1->getCell('C' . $row)->getValue() ?? '');
        $internal_phone = trim($worksheet1->getCell('D' . $row)->getValue() ?? '');
        $cabinet        = trim($worksheet1->getCell('E' . $row)->getValue() ?? '');
        $vacation       = trim($worksheet1->getCell('F' . $row)->getValue() ?? '');

        if ($position === '' && $fio === '' && $city === '' && $internal_phone === '' && $cabinet === '' && $vacation === '') {
            continue;
        }

        if ($position !== '' && $fio === '' && $city === '' && $internal_phone === '' && $cabinet === '' && $vacation === '') {
            $mainData[] = ['type' => 'department', 'name' => $position];
        } else {
            $mainData[] = [
                'type'           => 'employee',
                'position'       => $position,
                'fio'            => $fio,
                'city'           => $city,
                'internal_phone' => $internal_phone,
                'cabinet'        => $cabinet,
                'vacation'       => $vacation
            ];
        }
    }


    // === List_2: филиалы ===
    $worksheet2 = $spreadsheet->getSheetByName('List_2');
    $branches = [];
    $highestRow2 = $worksheet2->getHighestRow();

    $currentBranch = null;
    $branchEmployees = [];

    for ($row = 2; $row <= $highestRow2; $row++) {
        $cellA = trim($worksheet2->getCell('A' . $row)->getValue() ?? '');
        $cellB = trim($worksheet2->getCell('B' . $row)->getValue() ?? '');
        $cellC = trim($worksheet2->getCell('C' . $row)->getValue() ?? '');
        $cellD = trim($worksheet2->getCell('D' . $row)->getValue() ?? '');
        $cellE = trim($worksheet2->getCell('E' . $row)->getValue() ?? '');

        // Начало нового филиала
        if (stripos($cellA, 'ОГКУ ЦЗН ТО по') !== false || stripos($cellA, 'ОГКУ ЦЗН ТО') !== false) {
            if ($currentBranch) {
                $branches[] = ['info' => $currentBranch, 'employees' => $branchEmployees];
                $branchEmployees = [];
            }
            $currentBranch = $cellA;
            $nextRow = $row + 1;
            while ($nextRow <= $highestRow2) {
                $nextA = trim($worksheet2->getCell('A' . $nextRow)->getValue() ?? '');
                if ($nextA === '' || $nextA === 'ДОЛЖНОСТЬ' || stripos($nextA, 'ОГКУ ЦЗН ТО по') !== false) {
                    break;
                }
                $currentBranch .= "\n" . $nextA;
                $nextRow++;
            }
            $row = $nextRow - 1;
            continue;
        }

        // Пропуск заголовка колонок
        if ($cellA === 'ДОЛЖНОСТЬ') {
            continue;
        }

        // Пропуск пустых строк
        if ($cellA === '' && $cellB === '' && $cellC === '' && $cellD === '' && $cellE === '') {
            continue;
        }

        // Подотдел внутри филиала
        if ($cellA !== '' && $cellB === '' && $cellC === '' && $cellD === '' && $cellE === '') {
            $branchEmployees[] = ['type' => 'subdepartment', 'name' => $cellA];
        } else {
            $branchEmployees[] = [
                'type'     => 'employee',
                'position' => $cellA,
                'fio'      => $cellB,
                'city'     => $cellC,
                'mobile'   => $cellD,
                'vacation' => $cellE
            ];
        }
    }

    if ($currentBranch) {
        $branches[] = ['info' => $currentBranch, 'employees' => $branchEmployees];
    }

    // Сохраняем в JSON
    $jsonData = ['main' => $mainData, 'branches' => $branches];
    file_put_contents('data.json', json_encode($jsonData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

    // HTML для основного справочника
    $mainHtml = '';
    if (empty($mainData)) {
        $mainHtml = '<tr><td colspan="6" class="text-center text-muted py-5">Данные не найдены в файле.</td></tr>';
    } else {
        foreach ($mainData as $row) {
            if ($row['type'] === 'department') {
                $mainHtml .= '<tr class="table-secondary fw-bold">
                    <td colspan="6" class="text-center py-3 fs-5">' . htmlspecialchars($row['name']) . '</td>
                </tr>';
            } else {
                $mainHtml .= "<tr>
                    <td>" . htmlspecialchars($row['position'] ?? '') . "</td>
                    <td>" . htmlspecialchars($row['fio'] ?? '') . "</td>
                    <td>" . htmlspecialchars($row['city'] ?? '') . "</td>
                    <td>" . htmlspecialchars($row['internal_phone'] ?? '') . "</td>
                    <td>" . htmlspecialchars($row['cabinet'] ?? '') . "</td>
                    <td>" . htmlspecialchars($row['vacation'] ?? '') . "</td>
                </tr>";
            }
        }
    }

    // HTML для филиалов
    $branchesHtml = '';
    if (empty($branches)) {
        $branchesHtml = '<tr><td colspan="5" class="text-center text-muted py-5">Филиалы не найдены.</td></tr>';
    } else {
        foreach ($branches as $branch) {
            $branchesHtml .= '<tr class="table-primary fw-bold">
                <td colspan="5" class="text-center py-3 fs-5">' . nl2br(htmlspecialchars($branch['info'])) . '</td>
            </tr>';

            foreach ($branch['employees'] as $item) {
                if ($item['type'] === 'subdepartment') {
                    $branchesHtml .= '<tr class="table-secondary fw-bold">
                        <td colspan="5" class="text-center py-3 fs-5">' . htmlspecialchars($item['name']) . '</td>
                    </tr>';
                } else {
                    $branchesHtml .= "<tr>
                        <td>" . htmlspecialchars($item['position'] ?? '') . "</td>
                        <td>" . htmlspecialchars($item['fio'] ?? '') . "</td>
                        <td>" . htmlspecialchars($item['city'] ?? '') . "</td>
                        <td>" . htmlspecialchars($item['mobile'] ?? '') . "</td>
                        <td>" . htmlspecialchars($item['vacation'] ?? '') . "</td>
                    </tr>";
                }
            }
        }
    }

    $response['success'] = true;
    $response['mainHtml'] = $mainHtml;
    $response['branchesHtml'] = $branchesHtml;

} catch (Exception $e) {
    $response['error'] = 'Ошибка обработки файла: ' . $e->getMessage();
}

echo json_encode($response);