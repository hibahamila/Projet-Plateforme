// Configuration principale de Dropzone
document.addEventListener('DOMContentLoaded', function() {
    // Récupération des routes depuis les champs cachés
    const uploadRoute = document.getElementById('uploadRoute').value;
    const deleteRoute = document.getElementById('deleteRoute').value;
    
    // Tableau pour stocker les infos des fichiers uploadés
    let uploadedFiles = [];
    
    // Configuration de Dropzone
    Dropzone.autoDiscover = false;
    
    // Initialisation de Dropzone
    const myDropzone = new Dropzone("#multipleFilesUpload", {
        url: uploadRoute,
        paramName: "file",
        maxFilesize: 50, // 50 MB max
        addRemoveLinks: true,
        dictRemoveFile: "Supprimer",
        dictCancelUpload: "Annuler",
        dictDefaultMessage: "Déposez les fichiers ici ou cliquez pour les uploader",
        acceptedFiles: ".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.mp4,.mp3,.zip,.jpg,.jpeg,.png",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        init: function() {
            // Événement lors de l'ajout d'un fichier à la zone
            this.on("addedfile", function(file) {
                // Créer un élément de fichier personnalisé
                const fileElement = document.createElement('div');
                fileElement.className = 'file-item';
                fileElement.innerHTML = `
                    <div class="file-icon" data-file-id="${file.upload.uuid}">
                        <i class="${getFileIcon(file)}"></i>
                        <div class="file-actions">
                            <button class="btn btn-sm btn-danger delete-file" title="Supprimer">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="file-name">${file.name}</div>
                `;
                
                // Remplacer l'élément de prévisualisation par défaut
                file.previewElement.innerHTML = '';
                file.previewElement.appendChild(fileElement);
                
                // Ajouter les événements
                fileElement.querySelector('.file-icon').addEventListener('click', function() {
                    showFilePreview(file);
                });
                
                fileElement.querySelector('.delete-file').addEventListener('click', function(e) {
                    e.stopPropagation();
                    myDropzone.removeFile(file);
                });
            });
            
            // Événement lorsqu'un fichier est uploadé avec succès
            this.on("success", function(file, response) {
                // Stocker les informations du fichier
                const fileInfo = {
                    id: response.id,
                    name: file.name,
                    path: response.filepath,
                    original_name: response.original_name,
                    size: file.size,
                    type: file.type
                };
                
                uploadedFiles.push(fileInfo);
                
                // Mettre à jour le champ caché avec les infos des fichiers
                updateUploadedFilesField();
                
                // Ajouter l'ID du fichier à l'élément Dropzone pour la suppression
                file.serverFileId = response.id;
                file.serverFilePath = response.filepath;
            });
            
            // Événement lorsqu'un fichier est supprimé
            this.on("removedfile", function(file) {
                if (file.serverFilePath) {
                    // Supprimer le fichier du serveur
                    fetch(deleteRoute, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            filepath: file.serverFilePath
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Fichier temporaire supprimé avec succès');
                        } else {
                            console.error('Erreur lors de la suppression du fichier temporaire');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                    });
                }
                
                // Retirer le fichier du tableau des fichiers uploadés
                uploadedFiles = uploadedFiles.filter(f => f.id !== file.serverFileId);
                
                // Mettre à jour le champ caché
                updateUploadedFilesField();
            });
        }
    });
    
    // Mise à jour du champ caché contenant les informations des fichiers
    function updateUploadedFilesField() {
        document.getElementById('uploaded_files').value = JSON.stringify(uploadedFiles);
    }
    
    // Fonction pour obtenir l'icône appropriée pour le type de fichier
    function getFileIcon(file) {
        const type = file.type.split('/')[0];
        const extension = file.name.split('.').pop().toLowerCase();
        
        if (file.type.includes('pdf')) return 'fa fa-file-pdf-o';
        if (file.type.includes('word')) return 'fa fa-file-word-o';
        if (file.type.includes('excel') || file.type.includes('spreadsheet')) return 'fa fa-file-excel-o';
        if (file.type.includes('powerpoint') || file.type.includes('presentation')) return 'fa fa-file-powerpoint-o';
        if (file.type.includes('zip')) return 'fa fa-file-archive-o';
        if (type === 'image') return 'fa fa-file-image-o';
        if (type === 'video') return 'fa fa-file-video-o';
        if (type === 'audio') return 'fa fa-file-audio-o';
        if (extension === 'txt') return 'fa fa-file-text-o';
        return 'fa fa-file-o';
    }
    
    // Validation du formulaire
    const form = document.querySelector('form.needs-validation');
    if (form) {
        form.addEventListener('submit', function(event) {
            // Vérifier si des fichiers ont été uploadés
            if (uploadedFiles.length === 0) {
                event.preventDefault();
                event.stopPropagation();
                
                // Afficher un message d'erreur
                const dropzoneContainer = document.querySelector('.dropzone-container');
                
                // Créer un message d'erreur s'il n'existe pas déjà
                if (!document.querySelector('.dropzone-error')) {
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'alert alert-danger mt-2 dropzone-error';
                    errorMsg.innerHTML = 'Veuillez uploader au moins un fichier.';
                    dropzoneContainer.parentNode.appendChild(errorMsg);
                }
                
                // Ajouter une classe pour indiquer l'erreur
                dropzoneContainer.classList.add('dropzone-invalid');
            } else {
                // Supprimer les messages d'erreur si présents
                const errorMsg = document.querySelector('.dropzone-error');
                if (errorMsg) {
                    errorMsg.remove();
                }
                
                const dropzoneContainer = document.querySelector('.dropzone-container');
                if (dropzoneContainer) {
                    dropzoneContainer.classList.remove('dropzone-invalid');
                }
            }
        }, false);
    }
});

// Fonction pour formater la taille des fichiers
function formatBytes(bytes, decimals = 2) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}