/***********************************************************
 *  ЛИПКИЙ THEAD Этот скрипт создан для динамического хедера у таблиц, чтобы он появлялся во время скрола
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
 *  ИНИЦИАЛИЗАЦИЯ
 ***********************************************************/
let list_1 = null;
let list_2 = null;

document.addEventListener('DOMContentLoaded', () => {
    list_1 = makeStickyHeader('main-table');
    list_2 = makeStickyHeader('branches-table');
});
