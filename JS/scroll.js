/***********************************************************
 *  ЛИПКИЙ THEAD (универсальная функция)
 ***********************************************************/
function makeStickyHeader(tableId) {
    const table = document.getElementById(tableId);
    if (!table) return null;

    const thead = table.querySelector('thead');
    if (!thead) return null;

    const stickyHeader = thead.cloneNode(true);
    stickyHeader.id = tableId + '-sticky';
    stickyHeader.style.position = 'fixed';
    stickyHeader.style.top = '0';
    stickyHeader.style.zIndex = '5';
    stickyHeader.style.height = '60px';
    stickyHeader.style.display = 'none';
    stickyHeader.style.backgroundColor = '#45265a';
    stickyHeader.style.boxShadow = '0 6px 12px rgba(0,0,0,0.35)';
    stickyHeader.style.pointerEvents = 'none';
    stickyHeader.style.opacity = '0.95';

    document.body.appendChild(stickyHeader);

    function syncSizes() {
        const tableRect = table.getBoundingClientRect();
        stickyHeader.style.left = tableRect.left + 'px';
        stickyHeader.style.width = tableRect.width + 'px';

        const origThs = thead.querySelectorAll('th');
        const cloneThs = stickyHeader.querySelectorAll('th');

        origThs.forEach((th, i) => {
            if (cloneThs[i]) {
                cloneThs[i].style.width = th.offsetWidth + 'px';
            }
        });
    }

    function onScroll() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const rect = table.getBoundingClientRect();
        const tableTop = rect.top + scrollTop;
        const tableBottom = tableTop + table.offsetHeight;
        const theadHeight = thead.offsetHeight;

        if (scrollTop > tableTop && scrollTop < tableBottom - theadHeight) {
            stickyHeader.style.display = 'table';
            syncSizes();
        } else {
            stickyHeader.style.display = 'none';
        }
    }

    window.addEventListener('scroll', onScroll);
    window.addEventListener('resize', syncSizes);

    return stickyHeader;
}

/***********************************************************
 *  ДИНАМИКА КОЛОНКИ "ВНУТР." ДЛЯ List_2
 ***********************************************************/
function initStickyHeaderLogicForList2(list_2) {
    if (!list_2) return;

    const headerRow = list_2.querySelector('tr');
    if (!headerRow) return;

    let savedInternalTh = null;
    let internalIndex = -1;
    let isInserted = false;

    // === ШАГ 1: при загрузке удаляем "внутр." ===
    [...headerRow.children].forEach((th, idx) => {
        if (th.textContent.trim().toLowerCase() === 'внутр.') {
            savedInternalTh = th.cloneNode(true);
            internalIndex = idx;
            th.remove();
        }
    });

    if (!savedInternalTh) return;

    // === ШАГ 2: отслеживаем title_branch ===
    function onScroll() {
        const stickyBottom = list_2.getBoundingClientRect().bottom;
        const rows = document.querySelectorAll('#branches-table tbody tr.title_branch');

        let activeBranchRow = null;

        for (const row of rows) {
            const r = row.getBoundingClientRect();
            if (r.top <= stickyBottom && r.bottom > stickyBottom) {
                activeBranchRow = row;
                break;
            }
        }

        if (!activeBranchRow) return;

        const needSix = activeBranchRow.classList.contains('six_columns');

        // === ВСТАВИТЬ "внутр." ===
        if (needSix && !isInserted) {
            const ref = headerRow.children[internalIndex] || null;
            headerRow.insertBefore(savedInternalTh, ref);
            isInserted = true;
        }

        // === УДАЛИТЬ "внутр." ===
        if (!needSix && isInserted) {
            savedInternalTh = headerRow.children[internalIndex];
            savedInternalTh.remove();
            isInserted = false;
        }
    }

    window.addEventListener('scroll', onScroll);
}

/***********************************************************
 *  ИНИЦИАЛИЗАЦИЯ
 ***********************************************************/
let list_1 = null;
let list_2 = null;

document.addEventListener('DOMContentLoaded', () => {
    list_1 = makeStickyHeader('main-table');
    list_2 = makeStickyHeader('branches-table');

    initStickyHeaderLogicForList2(list_2);
});
