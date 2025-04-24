function loadWordPreview(filePath, targetElement, fileName) {
    // Essayer d'utiliser l'API Office Online Viewer
    const officeOnlineUrl = `https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(filePath)}`;
    
    targetElement.innerHTML = `
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> 
            Les documents Word sont affichés via Microsoft Office Online Viewer.
            Si le document ne s'affiche pas, veuillez le télécharger.
        </div>
        <div style="height: 500px;">
            <iframe src="${officeOnlineUrl}" width="100%" height="100%" frameborder="0">
                Votre navigateur ne supporte pas les iframes.
            </iframe>
        </div>`;
}