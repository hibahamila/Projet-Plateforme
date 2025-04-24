function loadPdfPreview(filePath, targetElement) {
    targetElement.innerHTML = `
        <embed src="${filePath}" type="application/pdf" width="100%" height="100%" style="min-height: 500px;" />
        <div class="text-center mt-2">
            <a href="${filePath}" class="btn btn-sm btn-outline-primary" target="_blank">
                <i class="fas fa-external-link-alt me-1"></i> Ouvrir dans un nouvel onglet
            </a>
        </div>`;
}