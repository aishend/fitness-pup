(function () {
    var PER_PAGE_OPTIONS = [4, 8, 16, 32];
    var DEFAULT_PER_PAGE = 8;
    var STORAGE_KEY = 'gym_per_page';

    function loadPerPage() {
        var v = localStorage.getItem(STORAGE_KEY);
        if (v === '0') return 0;
        var n = parseInt(v);
        return PER_PAGE_OPTIONS.indexOf(n) !== -1 ? n : DEFAULT_PER_PAGE;
    }

    function savePerPage(n) {
        localStorage.setItem(STORAGE_KEY, String(n));
    }

    function initPaginator(container) {
        var itemSel = container.dataset.paginatorItems || null;
        var items;

        if (itemSel) {
            items = Array.from(container.querySelectorAll(itemSel));
        } else {
            items = Array.from(container.children).filter(function (el) {
                return !el.classList.contains('paginator-controls');
            });
        }

        if (items.length === 0) return;

        var perPage = loadPerPage();
        var currentPage = 1;

        function totalPages() {
            return perPage === 0 ? 1 : Math.ceil(items.length / perPage);
        }

        function render() {
            var total = totalPages();
            if (currentPage > total) currentPage = total;
            if (currentPage < 1) currentPage = 1;

            var startIdx = perPage === 0 ? 0 : (currentPage - 1) * perPage;
            var endIdx   = perPage === 0 ? items.length : startIdx + perPage;
            var showing  = Math.min(endIdx, items.length);

            items.forEach(function (item, i) {
                item.style.display = (i >= startIdx && i < endIdx) ? '' : 'none';
            });

            if (perPage === 0 || items.length <= perPage) {
                countInfo.textContent = 'Showing all ' + items.length;
                nav.style.visibility = 'hidden';
            } else {
                countInfo.textContent = 'Showing ' + (startIdx + 1) + '–' + showing + ' of ' + items.length;
                nav.style.visibility = 'visible';
                prevBtn.disabled = currentPage <= 1;
                nextBtn.disabled = currentPage >= total;
                pageInfo.textContent = currentPage + ' / ' + total;
            }

            // sync select in case perPage changed externally
            sizeSelect.value = String(perPage);
        }

        // ── build controls ────────────────────────────────────────────────
        var controls = document.createElement('div');
        controls.className = 'paginator-controls';

        // left: count info
        var leftDiv = document.createElement('div');
        leftDiv.className = 'paginator-left';
        var countInfo = document.createElement('span');
        countInfo.className = 'paginator-info';
        leftDiv.appendChild(countInfo);

        // center: prev / page-x-of-y / next
        var nav = document.createElement('div');
        nav.className = 'paginator-nav';

        var prevBtn = document.createElement('button');
        prevBtn.type = 'button';
        prevBtn.className = 'paginator-btn';
        prevBtn.innerHTML = '← Prev';
        prevBtn.addEventListener('click', function () { currentPage--; render(); });

        var pageInfo = document.createElement('span');
        pageInfo.className = 'paginator-page-info';

        var nextBtn = document.createElement('button');
        nextBtn.type = 'button';
        nextBtn.className = 'paginator-btn';
        nextBtn.innerHTML = 'Next →';
        nextBtn.addEventListener('click', function () { currentPage++; render(); });

        nav.appendChild(prevBtn);
        nav.appendChild(pageInfo);
        nav.appendChild(nextBtn);

        // right: per-page selector
        var rightDiv = document.createElement('div');
        rightDiv.className = 'paginator-right';

        var sizeLabel = document.createElement('label');
        sizeLabel.className = 'paginator-size-label';
        sizeLabel.textContent = 'Per page: ';

        var sizeSelect = document.createElement('select');
        sizeSelect.className = 'paginator-size-select';

        PER_PAGE_OPTIONS.forEach(function (n) {
            var opt = document.createElement('option');
            opt.value = String(n);
            opt.textContent = String(n);
            if (n === perPage) opt.selected = true;
            sizeSelect.appendChild(opt);
        });

        var allOpt = document.createElement('option');
        allOpt.value = '0';
        allOpt.textContent = 'All';
        if (perPage === 0) allOpt.selected = true;
        sizeSelect.appendChild(allOpt);

        sizeSelect.addEventListener('change', function () {
            perPage = parseInt(this.value);
            savePerPage(perPage);
            currentPage = 1;
            render();
        });

        sizeLabel.appendChild(sizeSelect);
        rightDiv.appendChild(sizeLabel);

        controls.appendChild(leftDiv);
        controls.appendChild(nav);
        controls.appendChild(rightDiv);

        // insert controls after the container
        container.parentNode.insertBefore(controls, container.nextSibling);

        render();
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-paginator]').forEach(initPaginator);
    });
})();
