<!-- Модальное окно загрузки exel-файла -->
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