function makeStickyHeader(tableId) {
    const table = document.getElementById(tableId);
    if (!table) return;

    const thead = table.querySelector('thead');
    if (!thead) return;

    // Клон thead
    const stickyHeader = thead.cloneNode(true);
    stickyHeader.id = tableId + '-sticky';
    stickyHeader.style.position = 'fixed';
    stickyHeader.style.top = '0';
    stickyHeader.style.height = '60px';
    stickyHeader.style.zIndex = '5';
    stickyHeader.style.borderRadius = '10px';
    stickyHeader.style.display = 'none';
    stickyHeader.style.backgroundColor = '#45265a';
    stickyHeader.style.boxShadow = '0 6px 12px rgba(0,0,0,0.35)';
    stickyHeader.style.pointerEvents = 'none'; // чтобы не мешал кликам
    stickyHeader.style.opacity = '0.95';

    document.body.appendChild(stickyHeader);

    function syncSizes() {
        const tableRect = table.getBoundingClientRect();
        stickyHeader.style.left = tableRect.left + 'px';
        stickyHeader.style.width = tableRect.width + 'px';

        // синхронизация ширины колонок
        const origThs = thead.querySelectorAll('th');
        const cloneThs = stickyHeader.querySelectorAll('th');

        origThs.forEach((th, i) => {
            cloneThs[i].style.width = th.offsetWidth + 'px';
        });
    }

    function onScroll() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        const tableRect = table.getBoundingClientRect();
        const theadHeight = thead.offsetHeight;
        const tableTop = tableRect.top + scrollTop;
        const tableBottom = tableTop + table.offsetHeight;

        if (
            scrollTop > tableTop &&
            scrollTop < tableBottom - theadHeight
        ) {
            stickyHeader.style.display = 'table';
            syncSizes();
        } else {
            stickyHeader.style.display = 'none';
        }
    }

    window.addEventListener('scroll', onScroll);
    window.addEventListener('resize', syncSizes);
}

document.addEventListener('DOMContentLoaded', () => {
    makeStickyHeader('main-table');
    makeStickyHeader('branches-table');
});