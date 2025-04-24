function loadVideoPreview(filePath, targetElement, extension) {
    targetElement.innerHTML = `
        <div class="ratio ratio-16x9">
            <video controls class="w-100">
                <source src="${filePath}" type="video/${extension}">
                Votre navigateur ne supporte pas l'élément vidéo.
            </video>
        </div>
        <div class="mt-2 text-center">
            <button class="btn btn-sm btn-outline-secondary" onclick="toggleFullscreen(this)">
                <i class="fas fa-expand me-1"></i> Plein écran
            </button>
        </div>`;
}

function toggleFullscreen(btn) {
    const video = btn.closest('.modal-body').querySelector('video');
    if (!document.fullscreenElement) {
        if (video.requestFullscreen) {
            video.requestFullscreen();
        } else if (video.webkitRequestFullscreen) {
            video.webkitRequestFullscreen();
        } else if (video.msRequestFullscreen) {
            video.msRequestFullscreen();
        }
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    }
}