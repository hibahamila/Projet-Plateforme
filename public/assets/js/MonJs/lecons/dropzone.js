// // Configuration principale de Dropzone
// document.addEventListener('DOMContentLoaded', function() {
//     // Récupération des routes depuis les champs cachés
//     const uploadRoute = document.getElementById('uploadRoute').value;
//     const deleteRoute = document.getElementById('deleteRoute').value;
    
//     // Tableau pour stocker les infos des fichiers uploadés
//     let uploadedFiles = [];
    
//     // Configuration de Dropzone
//     Dropzone.autoDiscover = false;
    
//     // Initialisation de Dropzone
//     const myDropzone = new Dropzone("#multipleFilesUpload", {
//         url: uploadRoute,
//         paramName: "file",
//         maxFilesize: 50, // 50 MB max
//         addRemoveLinks: true,
//         dictRemoveFile: "",  // On va utiliser une croix personnalisée
//         dictCancelUpload: "Annuler",
//         dictDefaultMessage: "Déposez les fichiers ici ou cliquez pour les uploader",
//         acceptedFiles: ".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.mp4,.mp3,.zip,.jpg,.jpeg,.png,.txt",
//         headers: {
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//         },
//         init: function() {
//             // Événement lors de l'ajout d'un fichier à la zone
//             this.on("addedfile", function(file) {
//                 // Personnalisation de l'icône de suppression (croix rouge)
//                 const removeButton = file.previewElement.querySelector('.dz-remove');
//                 removeButton.innerHTML = '<i class="fas fa-times-circle" style="color: red;"></i>';
//                 removeButton.style.position = 'absolute';
//                 removeButton.style.top = '5px';
//                 removeButton.style.right = '5px';
//                 removeButton.style.fontSize = '18px';
                
//                 // Ajout d'un gestionnaire de clic sur tout l'élément de prévisualisation
//                 file.previewElement.addEventListener('click', function(e) {
//                     // Éviter de déclencher si on clique sur le bouton de suppression
//                     if (!e.target.closest('.dz-remove')) {
//                         showFilePreview(file);
//                     }
//                 });
                
//                 // Rendre l'élément cliquable visuellement
//                 file.previewElement.style.cursor = 'pointer';
//             });
            
//             // Événement lorsqu'un fichier est uploadé avec succès
//             this.on("success", function(file, response) {
//                 // Stocker les informations du fichier
//                 const fileInfo = {
//                     id: response.id,
//                     name: file.name,
//                     path: response.filepath,
//                     original_name: response.original_name,
//                     size: file.size,
//                     type: file.type
//                 };
                
//                 uploadedFiles.push(fileInfo);
                
//                 // Mettre à jour le champ caché avec les infos des fichiers
//                 updateUploadedFilesField();
                
//                 // Ajouter l'ID du fichier à l'élément Dropzone pour la suppression
//                 file.serverFileId = response.id;
//                 file.serverFilePath = response.filepath;
//             });
            
//             // Événement lorsqu'un fichier est supprimé
//             this.on("removedfile", function(file) {
//                 if (file.serverFilePath) {
//                     // Supprimer le fichier du serveur
//                     fetch(deleteRoute, {
//                         method: 'POST',
//                         headers: {
//                             'Content-Type': 'application/json',
//                             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//                         },
//                         body: JSON.stringify({
//                             filepath: file.serverFilePath
//                         })
//                     })
//                     .then(response => response.json())
//                     .then(data => {
//                         if (data.success) {
//                             console.log('Fichier temporaire supprimé avec succès');
//                         } else {
//                             console.error('Erreur lors de la suppression du fichier temporaire');
//                         }
//                     })
//                     .catch(error => {
//                         console.error('Erreur:', error);
//                     });
//                 }
                
//                 // Retirer le fichier du tableau des fichiers uploadés
//                 uploadedFiles = uploadedFiles.filter(f => f.id !== file.serverFileId);
                
//                 // Mettre à jour le champ caché
//                 updateUploadedFilesField();
//             });
//         }
//     });
    
//     // Mise à jour du champ caché contenant les informations des fichiers
//     function updateUploadedFilesField() {
//         document.getElementById('uploaded_files').value = JSON.stringify(uploadedFiles);
//     }
    
//     // Validation du formulaire
//     const form = document.querySelector('form.needs-validation');
//     if (form) {
//         form.addEventListener('submit', function(event) {
//             // Vérifier si des fichiers ont été uploadés
//             if (uploadedFiles.length === 0) {
//                 event.preventDefault();
//                 event.stopPropagation();
                
//                 // Afficher un message d'erreur
//                 const dropzoneContainer = document.querySelector('.dropzone-container');
                
//                 // Créer un message d'erreur s'il n'existe pas déjà
//                 if (!document.querySelector('.dropzone-error')) {
//                     const errorMsg = document.createElement('div');
//                     errorMsg.className = 'alert alert-danger mt-2 dropzone-error';
//                     errorMsg.innerHTML = 'Veuillez uploader au moins un fichier.';
//                     dropzoneContainer.parentNode.appendChild(errorMsg);
//                 }
                
//                 // Ajouter une classe pour indiquer l'erreur
//                 dropzoneContainer.classList.add('dropzone-invalid');
//             } else {
//                 // Supprimer les messages d'erreur si présents
//                 const errorMsg = document.querySelector('.dropzone-error');
//                 if (errorMsg) {
//                     errorMsg.remove();
//                 }
                
//                 const dropzoneContainer = document.querySelector('.dropzone-container');
//                 if (dropzoneContainer) {
//                     dropzoneContainer.classList.remove('dropzone-invalid');
//                 }
//             }
//         }, false);
//     }
// });

// // Fonction pour formater la taille des fichiers
// function formatBytes(bytes, decimals = 2) {
//     if (bytes === 0) return '0 Bytes';
    
//     const k = 1024;
//     const dm = decimals < 0 ? 0 : decimals;
//     const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    
//     const i = Math.floor(Math.log(bytes) / Math.log(k));
    
//     return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
// }









// // Configuration principale de Dropzone
// document.addEventListener('DOMContentLoaded', function() {
//     // Récupération des routes depuis les champs cachés
//     const uploadRoute = document.getElementById('uploadRoute').value;
//     const deleteRoute = document.getElementById('deleteRoute').value;
    
//     // Tableau pour stocker les infos des fichiers uploadés
//     let uploadedFiles = [];
    
//     // Configuration de Dropzone
//     Dropzone.autoDiscover = false;
    
//     // Initialisation de Dropzone
//     const myDropzone = new Dropzone("#multipleFilesUpload", {
//         url: uploadRoute,
//         paramName: "file",
//         maxFilesize: 50, // 50 MB max
//         addRemoveLinks: true,
//         dictRemoveFile: "",  // On va utiliser une croix personnalisée
//         dictCancelUpload: "Annuler",
//         dictDefaultMessage: "Déposez les fichiers ici ou cliquez pour les uploader",
//         acceptedFiles: ".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.mp4,.mp3,.zip,.jpg,.jpeg,.png,.txt",
//         headers: {
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//         },
//         init: function() {
//             // Événement lors de l'ajout d'un fichier à la zone
//             this.on("addedfile", function(file) {
//                 // Personnalisation de l'icône de suppression (croix rouge)
//                 const removeButton = file.previewElement.querySelector('.dz-remove');
//                 removeButton.innerHTML = '<i class="fas fa-times-circle" style="color: red;"></i>';
//                 removeButton.style.position = 'absolute';
//                 removeButton.style.top = '5px';
//                 removeButton.style.right = '5px';
//                 removeButton.style.fontSize = '18px';
                
//                 // Ajout d'un gestionnaire de clic sur tout l'élément de prévisualisation
//                 file.previewElement.addEventListener('click', function(e) {
//                     // Éviter de déclencher si on clique sur le bouton de suppression
//                     if (!e.target.closest('.dz-remove')) {
//                         showFilePreview(file);
//                     }
//                 });
                
//                 // Rendre l'élément cliquable visuellement
//                 file.previewElement.style.cursor = 'pointer';
                
//                 // Ajouter une icône colorée selon le type de fichier
//                 const fileTypeIcon = getFileTypeIcon(file.name);
//                 const iconContainer = document.createElement('div');
//                 iconContainer.className = 'file-type-icon';
//                 iconContainer.innerHTML = fileTypeIcon;
//                 iconContainer.style.position = 'absolute';
//                 iconContainer.style.top = '30px';
//                 iconContainer.style.left = '50%';
//                 iconContainer.style.transform = 'translateX(-50%)';
//                 iconContainer.style.fontSize = '30px';
//                 file.previewElement.appendChild(iconContainer);
//             });
            
//             // Événement lorsqu'un fichier est uploadé avec succès
//             this.on("success", function(file, response) {
//                 // Stocker les informations du fichier
//                 const fileInfo = {
//                     id: response.id,
//                     name: file.name,
//                     path: response.filepath,
//                     original_name: response.original_name,
//                     size: file.size,
//                     type: file.type
//                 };
                
//                 uploadedFiles.push(fileInfo);
                
//                 // Mettre à jour le champ caché avec les infos des fichiers
//                 updateUploadedFilesField();
                
//                 // Ajouter l'ID du fichier à l'élément Dropzone pour la suppression
//                 file.serverFileId = response.id;
//                 file.serverFilePath = response.filepath;
//             });
            
//             // Événement lorsqu'un fichier est supprimé
//             this.on("removedfile", function(file) {
//                 if (file.serverFilePath) {
//                     // Supprimer le fichier du serveur
//                     fetch(deleteRoute, {
//                         method: 'POST',
//                         headers: {
//                             'Content-Type': 'application/json',
//                             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//                         },
//                         body: JSON.stringify({
//                             filepath: file.serverFilePath
//                         })
//                     })
//                     .then(response => response.json())
//                     .then(data => {
//                         if (data.success) {
//                             console.log('Fichier temporaire supprimé avec succès');
//                         } else {
//                             console.error('Erreur lors de la suppression du fichier temporaire');
//                         }
//                     })
//                     .catch(error => {
//                         console.error('Erreur:', error);
//                     });
//                 }
                
//                 // Retirer le fichier du tableau des fichiers uploadés
//                 uploadedFiles = uploadedFiles.filter(f => f.id !== file.serverFileId);
                
//                 // Mettre à jour le champ caché
//                 updateUploadedFilesField();
//             });
//         }
//     });
    
//     // Mise à jour du champ caché contenant les informations des fichiers
//     function updateUploadedFilesField() {
//         document.getElementById('uploaded_files').value = JSON.stringify(uploadedFiles);
//     }
    
//     // Validation du formulaire
//     const form = document.querySelector('form.needs-validation');
//     if (form) {
//         form.addEventListener('submit', function(event) {
//             // Vérifier si des fichiers ont été uploadés
//             if (uploadedFiles.length === 0) {
//                 event.preventDefault();
//                 event.stopPropagation();
                
//                 // Afficher un message d'erreur
//                 const dropzoneContainer = document.querySelector('.dropzone-container');
                
//                 // Créer un message d'erreur s'il n'existe pas déjà
//                 if (!document.querySelector('.dropzone-error')) {
//                     const errorMsg = document.createElement('div');
//                     errorMsg.className = 'alert alert-danger mt-2 dropzone-error';
//                     errorMsg.innerHTML = 'Veuillez uploader au moins un fichier.';
//                     dropzoneContainer.parentNode.appendChild(errorMsg);
//                 }
                
//                 // Ajouter une classe pour indiquer l'erreur
//                 dropzoneContainer.classList.add('dropzone-invalid');
//             } else {
//                 // Supprimer les messages d'erreur si présents
//                 const errorMsg = document.querySelector('.dropzone-error');
//                 if (errorMsg) {
//                     errorMsg.remove();
//                 }
                
//                 const dropzoneContainer = document.querySelector('.dropzone-container');
//                 if (dropzoneContainer) {
//                     dropzoneContainer.classList.remove('dropzone-invalid');
//                 }
//             }
//         }, false);
//     }
    
//     // Ajout du modal au body pour la prévisualisation des fichiers
//     if (!document.getElementById('filePreviewModal')) {
//         const modal = `
//         <div class="modal fade" id="filePreviewModal" tabindex="-1" aria-labelledby="filePreviewModalLabel" aria-hidden="true">
//             <div class="modal-dialog modal-lg">
//                 <div class="modal-content">
//                     <div class="modal-header">
//                         <h5 class="modal-title" id="filePreviewModalLabel">Prévisualisation</h5>
//                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
//                     </div>
//                     <div class="modal-body text-center">
//                         <div class="file-icon-container mb-3">
//                             <span id="modalFileIcon" style="font-size: 48px;"></span>
//                             <h5 id="modalFileName" class="mt-2"></h5>
//                         </div>
//                         <div id="fileContent" class="p-3"></div>
//                     </div>
//                     <div class="modal-footer">
//                         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
//                         <a id="downloadBtn" href="#" class="btn btn-primary" download>Télécharger</a>
//                     </div>
//                 </div>
//             </div>
//         </div>`;
        
//         document.body.insertAdjacentHTML('beforeend', modal);
//     }
// });

// // Fonction pour déterminer l'icône selon le type de fichier
// function getFileTypeIcon(filename) {
//     const extension = filename.split('.').pop().toLowerCase();
//     let iconClass = '';
//     let iconColor = '';
    
//     switch (extension) {
//         case 'pdf':
//             iconClass = 'fa-file-pdf';
//             iconColor = '#dd4b39';
//             break;
//         case 'doc':
//         case 'docx':
//             iconClass = 'fa-file-word';
//             iconColor = '#2a5699';
//             break;
//         case 'xls':
//         case 'xlsx':
//             iconClass = 'fa-file-excel';
//             iconColor = '#207245';
//             break;
//         case 'ppt':
//         case 'pptx':
//             iconClass = 'fa-file-powerpoint';
//             iconColor = '#d24726';
//             break;
//         case 'jpg':
//         case 'jpeg':
//         case 'png':
//         case 'gif':
//             iconClass = 'fa-file-image';
//             iconColor = '#f1c40f';
//             break;
//         case 'mp4':
//         case 'avi':
//         case 'mov':
//             iconClass = 'fa-file-video';
//             iconColor = '#9b59b6';
//             break;
//         case 'mp3':
//         case 'wav':
//             iconClass = 'fa-file-audio';
//             iconColor = '#3498db';
//             break;
//         case 'zip':
//         case 'rar':
//             iconClass = 'fa-file-archive';
//             iconColor = '#7f8c8d';
//             break;
//         case 'txt':
//             iconClass = 'fa-file-alt';
//             iconColor = '#34495e';
//             break;
//         default:
//             iconClass = 'fa-file';
//             iconColor = '#95a5a6';
//     }
    
//     return `<i class="fas ${iconClass}" style="color: ${iconColor};"></i>`;
// }

// // Fonction pour formater la taille des fichiers
// function formatBytes(bytes, decimals = 2) {
//     if (bytes === 0) return '0 Bytes';
    
//     const k = 1024;
//     const dm = decimals < 0 ? 0 : decimals;
//     const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    
//     const i = Math.floor(Math.log(bytes) / Math.log(k));
    
//     return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
// }

// // Fonction pour afficher la prévisualisation du fichier dans le modal
// function showFilePreview(file) {
//     const modal = new bootstrap.Modal(document.getElementById('filePreviewModal'));
//     const modalTitle = document.getElementById('filePreviewModalLabel');
//     const modalFileIcon = document.getElementById('modalFileIcon');
//     const modalFileName = document.getElementById('modalFileName');
//     const fileContent = document.getElementById('fileContent');
//     const downloadBtn = document.getElementById('downloadBtn');
    
//     // Vider le contenu précédent
//     fileContent.innerHTML = '';
    
//     // Mettre à jour le titre du modal
//     modalTitle.textContent = 'Prévisualisation: ' + file.name;
    
//     // Afficher l'icône du type de fichier
//     modalFileIcon.innerHTML = getFileTypeIcon(file.name);
    
//     // Afficher le nom du fichier
//     modalFileName.textContent = file.name;
    
//     // Configurer le bouton de téléchargement
//     if (file.serverFilePath) {
//         downloadBtn.href = file.serverFilePath;
//         downloadBtn.setAttribute('download', file.name);
//     } else {
//         downloadBtn.style.display = 'none';
//     }
    
//     // Préparer l'affichage du contenu selon le type de fichier
//     const extension = file.name.split('.').pop().toLowerCase();
    
//     // Afficher le contenu en fonction du type de fichier
//     if (file.serverFilePath) {
//         switch (extension) {
//             case 'pdf':
//                 // Afficher le PDF intégré
//                 fileContent.innerHTML = `<embed src="${file.serverFilePath}" type="application/pdf" width="100%" height="500px" />`;
//                 break;
                
//             case 'jpg':
//             case 'jpeg':
//             case 'png':
//             case 'gif':
//                 // Afficher l'image
//                 fileContent.innerHTML = `<img src="${file.serverFilePath}" class="img-fluid" alt="${file.name}" />`;
//                 break;
                
//             case 'mp4':
//             case 'webm':
//                 // Afficher la vidéo
//                 fileContent.innerHTML = `
//                     <video controls width="100%" class="mb-3">
//                         <source src="${file.serverFilePath}" type="video/${extension}">
//                         Votre navigateur ne supporte pas l'élément vidéo.
//                     </video>`;
//                 break;
                
//             case 'mp3':
//             case 'wav':
//                 // Afficher l'audio
//                 fileContent.innerHTML = `
//                     <audio controls class="w-100 mb-3">
//                         <source src="${file.serverFilePath}" type="audio/${extension}">
//                         Votre navigateur ne supporte pas l'élément audio.
//                     </audio>`;
//                 break;
                
//             case 'doc':
//             case 'docx':
//                 // Pour les documents Word, nous allons essayer de les afficher via l'API Office Online ou proposer un téléchargement
//                 loadDocumentPreview(file.serverFilePath, fileContent);
//                 break;
                
//             case 'txt':
//                 // Charger et afficher le contenu du fichier texte
//                 loadTextFileContent(file.serverFilePath, fileContent);
//                 break;
                
//             default:
//                 // Pour les autres types, afficher un message
//                 fileContent.innerHTML = `
//                     <div class="alert alert-info">
//                         La prévisualisation n'est pas disponible pour ce type de fichier.
//                         Veuillez le télécharger pour le consulter.
//                     </div>`;
//         }
//     } else {
//         fileContent.innerHTML = `
//             <div class="alert alert-warning">
//                 Le fichier n'est pas encore complètement chargé.
//                 Veuillez attendre la fin du téléchargement.
//             </div>`;
//     }
    
//     // Afficher le modal
//     modal.show();
// }

// // Fonction pour charger le contenu d'un fichier texte
// function loadTextFileContent(filePath, targetElement) {
//     fetch(filePath)
//         .then(response => response.text())
//         .then(text => {
//             targetElement.innerHTML = `<pre class="text-start p-3 border rounded bg-light">${text}</pre>`;
//         })
//         .catch(error => {
//             targetElement.innerHTML = `
//                 <div class="alert alert-danger">
//                     Erreur lors du chargement du fichier: ${error.message}
//                 </div>`;
//         });
// }

// // Fonction pour essayer de prévisualiser les documents Word/Office
// function loadDocumentPreview(filePath, targetElement) {
//     // Vérifier si l'API Office Online Viewer est disponible
//     const officeOnlineUrl = `https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(window.location.origin + filePath)}`;
    
//     // Tentative d'utilisation de l'API Office Online
//     targetElement.innerHTML = `
//         <div class="mb-3">
//             <iframe src="${officeOnlineUrl}" width="100%" height="500px" frameborder="0">
//                 Votre navigateur ne supporte pas les iframes.
//             </iframe>
//         </div>
//         <div class="alert alert-info">
//             Si le document ne s'affiche pas correctement, veuillez utiliser le bouton de téléchargement.
//         </div>`;
// }




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
        dictRemoveFile: "",  // On va utiliser une croix personnalisée
        dictCancelUpload: "Annuler",
        dictDefaultMessage: "Déposez les fichiers ici ou cliquez pour les uploader",
        acceptedFiles: ".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.mp4,.mp3,.zip,.jpg,.jpeg,.png,.txt",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        init: function() {
            // Événement lors de l'ajout d'un fichier à la zone
            this.on("addedfile", function(file) {
                // Personnalisation de l'icône de suppression (croix rouge)
                const removeButton = file.previewElement.querySelector('.dz-remove');
                removeButton.innerHTML = '<i class="fas fa-times-circle" style="color: red;"></i>';
                removeButton.style.position = 'absolute';
                removeButton.style.top = '5px';
                removeButton.style.right = '5px';
                removeButton.style.fontSize = '18px';
                removeButton.style.zIndex = '1000';
                
                // Ajout d'un gestionnaire de clic sur tout l'élément de prévisualisation
                file.previewElement.addEventListener('click', function(e) {
                    // Éviter de déclencher si on clique sur le bouton de suppression
                    if (!e.target.closest('.dz-remove')) {
                        showFilePreview(file);
                    }
                });
                
                // Rendre l'élément cliquable visuellement
                file.previewElement.style.cursor = 'pointer';
                file.previewElement.style.position = 'relative';
                
                // Ajouter une icône colorée selon le type de fichier
                const fileTypeIcon = getFileTypeIcon(file.name);
                const iconContainer = document.createElement('div');
                iconContainer.className = 'file-type-icon';
                iconContainer.innerHTML = fileTypeIcon;
                iconContainer.style.position = 'absolute';
                iconContainer.style.top = '30px';
                iconContainer.style.left = '50%';
                iconContainer.style.transform = 'translateX(-50%)';
                iconContainer.style.fontSize = '30px';
                file.previewElement.appendChild(iconContainer);
                
                // Ajouter le nom du fichier sous l'icône
                const fileNameElement = document.createElement('div');
                fileNameElement.className = 'file-name';
                fileNameElement.textContent = file.name.length > 15 ? file.name.substring(0, 12) + '...' : file.name;
                fileNameElement.style.position = 'absolute';
                fileNameElement.style.bottom = '10px';
                fileNameElement.style.left = '0';
                fileNameElement.style.right = '0';
                fileNameElement.style.textAlign = 'center';
                fileNameElement.style.fontSize = '12px';
                fileNameElement.style.wordBreak = 'break-word';
                file.previewElement.appendChild(fileNameElement);
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
                            console.log('Fichier supprimé avec succès');
                        } else {
                            console.error('Erreur lors de la suppression du fichier');
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
    
    // Ajout du modal au body pour la prévisualisation des fichiers
    if (!document.getElementById('filePreviewModal')) {
        const modal = `
        <div class="modal fade" id="filePreviewModal" tabindex="-1" aria-labelledby="filePreviewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filePreviewModalLabel">Prévisualisation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="file-icon-container d-flex align-items-center">
                                <span id="modalFileIcon" style="font-size: 32px;"></span>
                                <h5 id="modalFileName" class="ms-2 mb-0"></h5>
                            </div>
                            <div class="file-actions">
                                <a id="downloadBtn" href="#" class="btn btn-sm btn-primary" download>
                                    <i class="fas fa-download me-1"></i> Télécharger
                                </a>
                            </div>
                        </div>
                        <div id="fileContent" class="p-3 border rounded" style="min-height: 500px; max-height: 70vh; overflow: auto;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>`;
        
        document.body.insertAdjacentHTML('beforeend', modal);
    }
});

// Fonction pour déterminer l'icône selon le type de fichier
function getFileTypeIcon(filename) {
    const extension = filename.split('.').pop().toLowerCase();
    let iconClass = '';
    let iconColor = '';
    
    switch (extension) {
        case 'pdf':
            iconClass = 'fa-file-pdf';
            iconColor = '#dd4b39';
            break;
        case 'doc':
        case 'docx':
            iconClass = 'fa-file-word';
            iconColor = '#2a5699';
            break;
        case 'xls':
        case 'xlsx':
            iconClass = 'fa-file-excel';
            iconColor = '#207245';
            break;
        case 'ppt':
        case 'pptx':
            iconClass = 'fa-file-powerpoint';
            iconColor = '#d24726';
            break;
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
            iconClass = 'fa-file-image';
            iconColor = '#f1c40f';
            break;
        case 'mp4':
        case 'avi':
        case 'mov':
            iconClass = 'fa-file-video';
            iconColor = '#9b59b6';
            break;
        case 'mp3':
        case 'wav':
            iconClass = 'fa-file-audio';
            iconColor = '#3498db';
            break;
        case 'zip':
        case 'rar':
            iconClass = 'fa-file-archive';
            iconColor = '#7f8c8d';
            break;
        case 'txt':
            iconClass = 'fa-file-alt';
            iconColor = '#34495e';
            break;
        default:
            iconClass = 'fa-file';
            iconColor = '#95a5a6';
    }
    
    return `<i class="fas ${iconClass}" style="color: ${iconColor};"></i>`;
}

// Fonction pour afficher la prévisualisation du fichier dans le modal
function showFilePreview(file) {
    const modal = new bootstrap.Modal(document.getElementById('filePreviewModal'));
    const modalTitle = document.getElementById('filePreviewModalLabel');
    const modalFileIcon = document.getElementById('modalFileIcon');
    const modalFileName = document.getElementById('modalFileName');
    const fileContent = document.getElementById('fileContent');
    const downloadBtn = document.getElementById('downloadBtn');
    
    // Vider le contenu précédent
    fileContent.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Chargement...</span></div><p class="mt-2">Chargement du fichier...</p></div>';
    
    // Mettre à jour le titre du modal
    modalTitle.textContent = 'Prévisualisation: ' + file.name;
    
    // Afficher l'icône du type de fichier
    modalFileIcon.innerHTML = getFileTypeIcon(file.name);
    
    // Afficher le nom du fichier
    modalFileName.textContent = file.name;
    
    // Configurer le bouton de téléchargement
    if (file.serverFilePath) {
        downloadBtn.href = file.serverFilePath;
        downloadBtn.setAttribute('download', file.name);
        downloadBtn.style.display = 'inline-block';
    } else {
        downloadBtn.style.display = 'none';
    }
    
    // Afficher le modal immédiatement
    modal.show();
    
    // Charger le contenu selon le type de fichier
    if (file.serverFilePath) {
        const extension = file.name.split('.').pop().toLowerCase();
        
        // Délai minimal pour montrer l'animation de chargement
        setTimeout(() => {
            switch (extension) {
                case 'pdf':
                    loadPdfPreview(file.serverFilePath, fileContent);
                    break;
                    
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                    loadImagePreview(file.serverFilePath, fileContent, file.name);
                    break;
                    
                case 'mp4':
                case 'webm':
                case 'mov':
                    loadVideoPreview(file.serverFilePath, fileContent, extension);
                    break;
                    
                case 'mp3':
                case 'wav':
                    loadAudioPreview(file.serverFilePath, fileContent, extension);
                    break;
                    
                case 'doc':
                case 'docx':
                    loadWordPreview(file.serverFilePath, fileContent, file.name);
                    break;
                    
                case 'txt':
                    loadTextPreview(file.serverFilePath, fileContent);
                    break;
                    
                default:
                    showUnsupportedPreview(fileContent);
            }
        }, 300);
    } else {
        fileContent.innerHTML = `
            <div class="alert alert-warning">
                Le fichier n'est pas encore complètement chargé.
                Veuillez attendre la fin du téléchargement.
            </div>`;
    }
}