Dropzone.autoDiscover = false;
$(document).ready(function() {
   
    let deletedFiles = [];
    let uploadedFiles = [];
    let currentPreviewFileId = null;

    // Fonction pour afficher un fichier dans l'iframe de prévisualisation
    function showFilePreview(fileUrl, fileName, fileType) {
        const previewContainer = $('#filePreviewContainer');
        const previewContent = $('#filePreviewContent');
        const previewTitle = $('#filePreviewTitle');
        
        previewTitle.text(fileName);
        previewContainer.fadeIn();
        
        // Masquer tous les contenus de prévisualisation
        previewContent.children().hide();
        
        // Déterminer le type de fichier et afficher le contenu approprié
        const fileExt = fileName.split('.').pop().toLowerCase();
        
        if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
            previewContent.html(`<img src="${fileUrl}" class="img-fluid" alt="Prévisualisation">`);
        } 
        else if (fileExt === 'pdf') {
            previewContent.html(`
                <iframe src="${fileUrl}" width="100%" height="600px" style="border: none;"></iframe>
            `);
        }
        else if (['mp4', 'webm', 'ogg'].includes(fileExt)) {
            previewContent.html(`
                <video controls width="100%">
                    <source src="${fileUrl}" type="video/${fileExt}">
                    Votre navigateur ne supporte pas la lecture vidéo.
                </video>
            `);
        }
        else if (fileExt === 'mp3' || fileExt === 'wav') {
            previewContent.html(`
                <audio controls style="width: 100%">
                    <source src="${fileUrl}" type="audio/${fileExt === 'mp3' ? 'mpeg' : 'wav'}">
                    Votre navigateur ne supporte pas la lecture audio.
                </audio>
            `);
        }
        else if (['doc', 'docx'].includes(fileExt)) {
            // Conversion Word en HTML avec Mammoth.js
            previewContent.html('<div class="text-center my-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Chargement...</span></div><p>Conversion du document Word en cours...</p></div>');
            
            fetch(fileUrl)
                .then(response => response.arrayBuffer())
                .then(arrayBuffer => {
                    mammoth.extractRawText({arrayBuffer: arrayBuffer})
                        .then(result => {
                            const html = result.value.replace(/\n/g, '<br>');
                            previewContent.html(`
                                <div class="word-preview p-3" style="background: white; border: 1px solid #ddd; border-radius: 5px;">
                                    ${html}
                                </div>
                            `);
                        })
                        .catch(error => {
                            previewContent.html(`
                                <div class="alert alert-warning">
                                    Impossible de prévisualiser ce document Word.
                                </div>
                            `);
                            console.error(error);
                        });
                })
                .catch(error => {
                    previewContent.html(`
                        <div class="alert alert-danger">
                            Erreur lors du chargement du fichier: ${error.message}
                        </div>
                    `);
                });
        }
        else if (['xls', 'xlsx'].includes(fileExt)) {
            // Conversion Excel en HTML avec SheetJS
            previewContent.html('<div class="text-center my-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Chargement...</span></div><p>Conversion du tableau Excel en cours...</p></div>');
            
            fetch(fileUrl)
                .then(response => response.arrayBuffer())
                .then(arrayBuffer => {
                    const workbook = XLSX.read(arrayBuffer, {type: 'array'});
                    const firstSheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[firstSheetName];
                    const html = XLSX.utils.sheet_to_html(worksheet);
                    
                    previewContent.html(`
                        <div class="table-responsive">
                            ${html}
                        </div>
                    `);
                })
                .catch(error => {
                    previewContent.html(`
                        <div class="alert alert-warning">
                            Impossible de prévisualiser ce tableau Excel.
                        </div>
                    `);
                    console.error(error);
                });
        }
        else if (fileExt === 'zip') {
            // Affichage de l'arborescence ZIP (simulée - en réalité besoin d'un backend)
            previewContent.html(`
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> La prévisualisation des archives ZIP nécessite un traitement côté serveur.
                </div>
                <div id="zipContent" class="mt-3"></div>
            `);
            
            // Simuler le chargement de l'arborescence
            setTimeout(() => {
                $('#zipContent').html(`
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-folder me-2"></i> dossier1/</span>
                            <span class="badge bg-secondary rounded-pill">3 fichiers</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-file-pdf me-2"></i> document.pdf</span>
                            <span class="badge bg-secondary rounded-pill">2.4 MB</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-file-word me-2"></i> rapport.docx</span>
                            <span class="badge bg-secondary rounded-pill">1.1 MB</span>
                        </li>
                    </ul>
                    <div class="text-center mt-3">
                        <button class="btn btn-outline-primary" onclick="loadRealZipContent('${fileUrl}')">
                            <i class="fas fa-sync-alt"></i> Charger la vraie arborescence
                        </button>
                    </div>
                `);
            }, 1000);
        }
        else {
            // Pour les autres types de fichiers, essayer d'afficher le contenu texte
            previewContent.html('<div class="text-center my-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Chargement...</span></div><p>Chargement du fichier...</p></div>');
            
            fetch(fileUrl)
                .then(response => {
                    if (response.headers.get('content-type').includes('text')) {
                        return response.text();
                    }
                    throw new Error('Type de fichier non supporté');
                })
                .then(text => {
                    previewContent.html(`
                        <pre class="p-3 bg-light" style="max-height: 500px; overflow: auto;">${text}</pre>
                    `);
                })
                .catch(error => {
                    previewContent.html(`
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Prévisualisation non disponible pour ce type de fichier.
                        </div>
                    `);
                });
        }
    }

    // Gestion de la fermeture de la prévisualisation
    $(document).on('click', '.close-preview', function() {
        $('#filePreviewContainer').fadeOut();
    });

    // Gestion du clic sur les fichiers existants
    $(document).on('click', '.existing-file', function(e) {
        e.preventDefault();
        const fileUrl = $(this).data('file-url');
        const fileName = $(this).data('file-name');
        const fileType = $(this).data('file-type');
        
        showFilePreview(fileUrl, fileName, fileType);
    });

    // Initialisation de Dropzone
    let myDropzone = new Dropzone("#fileUploadDropzone", {
        url: $("#uploadRoute").val(),
        paramName: "file",
        maxFilesize: 50, // MB
        maxFiles: 10,
        addRemoveLinks: true,
        acceptedFiles: ".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.mp4,.mp3,.zip,.jpg,.jpeg,.png,.txt",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        init: function() {
            this.on("sending", function(file, xhr, formData) {
                formData.append("original_name", file.name);
            });
            this.on("success", function(file, response) {
                file.serverId = response.filepath;
                file.previewElement.classList.add("dz-success");
                
                uploadedFiles.push({
                    path: response.filepath,
                    name: response.original_name,
                    type: response.file_type,
                    size: file.size
                });
                updateUploadedFilesInput();
                
                // Ajouter un bouton de prévisualisation
                const previewButton = Dropzone.createElement(`
                    <button class="btn btn-sm btn-info preview-btn" 
                            data-file-url="${response.filepath}" 
                            data-file-name="${response.original_name}"
                            data-file-type="${response.file_type}">
                        <i class="fas fa-eye"></i> Prévisualiser
                    </button>
                `);
                
                file.previewElement.querySelector(".dz-remove").parentNode.appendChild(previewButton);
                
                previewButton.addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    showFilePreview(response.filepath, response.original_name, response.file_type);
                });
            });
            this.on("removedfile", function(file) {
                if (file.serverId) {
                    uploadedFiles = uploadedFiles.filter(f => f.path !== file.serverId);
                    $.ajax({
                        url: $("#deleteRoute").val(),
                        type: 'POST',
                        data: {
                            filepath: file.serverId,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                }
                updateUploadedFilesInput();
            });
            this.on("error", function(file, errorMessage) {
                Swal.fire('Erreur', errorMessage, 'error');
                this.removeFile(file);
            });
        }
    });

    // Gestion de la suppression des fichiers existants
    $(document).on('click', '.delete-existing-file', function(e) {
        e.preventDefault();
        const fileId = $(this).data('file-id');
        const fileCard = $(this).closest('.file-card');
        
        Swal.fire({
            title: 'Confirmer la suppression',
            text: "Êtes-vous sûr de vouloir supprimer ce fichier?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                if (!deletedFiles.includes(fileId)) {
                    deletedFiles.push(fileId);
                }
                updateDeletedFilesInput();
                
                fileCard.addClass('bg-danger').fadeOut(300, function() {
                    $(this).remove();
                    if ($('#existing-files-container .file-card').length === 0) {
                        $('#existing-files-container').html('<div class="alert alert-info">Aucun fichier existant</div>');
                    }
                });
                
                Swal.fire(
                    'Supprimé!',
                    'Le fichier sera définitivement supprimé après enregistrement.',
                    'success'
                );
            }
        });
    });

    // Fonctions utilitaires
    function updateUploadedFilesInput() {
        $('#uploaded_files').val(JSON.stringify(uploadedFiles));
    }

    function updateDeletedFilesInput() {
        $('#deleted_files').val(JSON.stringify(deletedFiles));
    }

    // Validation du formulaire
    $('#lesson-form').on('submit', function(e) {
        updateUploadedFilesInput();
        updateDeletedFilesInput();
        
        let valid = true;
        $('input[name="links[]"]').each(function() {
            if (!this.value) {
                valid = false;
                $(this).addClass('is-invalid');
            }
        });

        if (!valid) {
            e.preventDefault();
            Swal.fire('Erreur', 'Veuillez remplir tous les champs de lien', 'error');
        }
        
        return valid;
    });

    // Gestion des liens
    $('#add-link').click(function() {
        $('#links-container').append(`
            <div class="link-item input-group mb-2">
                <input type="url" name="links[]" class="form-control" placeholder="https://example.com">
                <button type="button" class="btn btn-danger remove-link">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `);
    });

    $(document).on('click', '.remove-link', function() {
        $(this).closest('.link-item').remove();
    });
});

// Fonction globale pour charger le vrai contenu ZIP (à implémenter côté serveur)
function loadRealZipContent(zipUrl) {
    Swal.fire({
        title: 'Chargement en cours',
        html: 'Cette fonctionnalité nécessite une implémentation côté serveur pour extraire l\'arborescence réelle du ZIP.',
        icon: 'info',
        confirmButtonText: 'OK'
    });
}