const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('excelFile');
const fileNameDiv = document.getElementById('fileName');
const submitBtn = document.getElementById('submitBtn');
const uploadStatus = document.getElementById('uploadStatus');
const tableBody = document.getElementById('phoneTableBody');
const branchesTableBody = document.getElementById('branchesTableBody'); // ← новый элемент
const modalEl = document.getElementById('uploadModal');
const modal = new bootstrap.Modal(modalEl);

// Drag & Drop + клик
dropZone.addEventListener('click', () => fileInput.click());

fileInput.addEventListener('change', handleFile);

['dragover', 'dragenter'].forEach(evt => dropZone.addEventListener(evt, e => { e.preventDefault(); dropZone.classList.add('dragover'); }));
['dragleave', 'dragend', 'drop'].forEach(evt => dropZone.addEventListener(evt, e => { e.preventDefault(); dropZone.classList.remove('dragover'); }));

dropZone.addEventListener('drop', e => {
    if (e.dataTransfer.files.length) {
        fileInput.files = e.dataTransfer.files;
        handleFile();
    }
});

function handleFile() {
    const file = fileInput.files[0];
    if (file && file.name.toLowerCase().endsWith('.xlsx')) {
        fileNameDiv.textContent = `Выбран файл: ${file.name}`;
        submitBtn.disabled = false;
        uploadStatus.innerHTML = '';
    } else {
        fileNameDiv.textContent = 'Пожалуйста, выберите файл .xlsx';
        submitBtn.disabled = true;
    }
}

// AJAX-загрузка
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();

    submitBtn.disabled = true;
    uploadStatus.innerHTML = '<div class="text-info">Загрузка и обработка файла...</div>';

    const formData = new FormData();
    formData.append('excelFile', fileInput.files[0]);

    fetch('upload.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Обновляем основной справочник
                tableBody.innerHTML = data.mainHtml;

                // Обновляем филиалы (если блок существует)
                if (branchesTableBody) {
                    branchesTableBody.innerHTML = data.branchesHtml;
                    // processBranchesTable()
                    // location.reload();
                }

                uploadStatus.innerHTML = '<div class="text-success">Файл успешно загружен и обработан!</div>';
                setTimeout(() => modal.hide(), 1200);
            } else {
                uploadStatus.innerHTML = `<div class="text-danger"><strong>Ошибка:</strong> ${data.error}</div>`;
            }
        })
        .catch(err => {
            uploadStatus.innerHTML = '<div class="text-danger">Ошибка сети или сервера. Попробуйте ещё раз.</div>';
            console.error(err);
        })
        .finally(() => {
            submitBtn.disabled = false;
        });
});