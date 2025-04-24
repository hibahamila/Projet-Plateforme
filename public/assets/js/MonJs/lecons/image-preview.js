function loadImagePreview(filePath, targetElement, fileName) {
    targetElement.innerHTML = `
        <div class="text-center">
            <img src="${filePath}" class="img-fluid" style="max-height: 65vh;" alt="${fileName}" />
        </div>
        <div class="text-center mt-3">
            <button class="btn btn-sm btn-outline-secondary" onclick="zoomImage(this)">
                <i class="fas fa-search-plus me-1"></i> Zoom
            </button>
        </div>`;
}

function zoomImage(btn) {
    const img = btn.closest('.text-center').querySelector('img');
    if (img.style.cursor === 'zoom-out') {
        img.style.maxWidth = '100%';
        img.style.maxHeight = '65vh';
        img.style.cursor = 'zoom-in';
        btn.innerHTML = '<i class="fas fa-search-plus me-1"></i> Zoom';
    } else {
        img.style.maxWidth = 'none';
        img.style.maxHeight = 'none';
        img.style.cursor = 'zoom-out';
        btn.innerHTML = '<i class="fas fa-search-minus me-1"></i> Réduire';
    }
}