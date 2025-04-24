function loadTextPreview(filePath, targetElement) {
    targetElement.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>';
    
    fetch(filePath)
        .then(response => {
            if (!response.ok) throw new Error('Erreur de chargement');
            return response.text();
        })
        .then(text => {
            // Échapper le HTML pour la sécurité
            const escapedText = text.replace(/</g, '&lt;').replace(/>/g, '&gt;');
            targetElement.innerHTML = `
                <div class="d-flex justify-content-end mb-2">
                    <button class="btn btn-sm btn-outline-secondary" onclick="toggleWrap(this)">
                        <i class="fas fa-text-width me-1"></i> Retour à la ligne
                    </button>
                </div>
                <pre class="p-3 bg-light border rounded" style="white-space: pre; overflow-x: auto;">${escapedText}</pre>`;
        })
        .catch(error => {
            targetElement.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Erreur lors du chargement du fichier: ${error.message}
                </div>`;
        });
}

function toggleWrap(btn) {
    const pre = btn.closest('.modal-body').querySelector('pre');
    if (pre.style.whiteSpace === 'pre') {
        pre.style.whiteSpace = 'pre-wrap';
        btn.innerHTML = '<i class="fas fa-text-width me-1"></i> Pas de retour';
    } else {
        pre.style.whiteSpace = 'pre';
        btn.innerHTML = '<i class="fas fa-text-width me-1"></i> Retour à la ligne';
    }
}