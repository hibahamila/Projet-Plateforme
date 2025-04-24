 // Configuration Dropzone
 document.addEventListener('DOMContentLoaded', function() {

Dropzone.autoDiscover = false;

document.addEventListener('DOMContentLoaded', function() {
    let myDropzone = new Dropzone("#multipleFilesUpload", {
        url: document.getElementById('uploadRoute').value,
        paramName: "file",
        maxFilesize: 50, // MB
        maxFiles: 10,
        addRemoveLinks: true,
        acceptedFiles: ".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.mp4,.mp3,.zip,.jpg,.jpeg,.png,.txt,.js,.css,.html,.json,.xml,.log,.csv",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        init: function() {
            this.on("success", function(file, response) {
                if (!response.filepath) {
                    console.error("Erreur: Pas de chemin retourné");
                    this.removeFile(file);
                    return;
                }
                file.previewElement.classList.add("dz-success");
                file.serverId = response.filepath;
                file.fileId = response.id;
                updateUploadedFilesList();
                
                // Ajouter une icône de suppression personnalisée sur chaque fichier
                const removeButton = document.createElement('div');
                removeButton.className = 'custom-remove-button';
                removeButton.innerHTML = '<i class="fas fa-times-circle"></i>';
                removeButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    myDropzone.removeFile(file);
                });
                file.previewElement.appendChild(removeButton);
                
                file.previewElement.addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (!e.target.classList.contains('custom-remove-button') && 
                        !e.target.closest('.custom-remove-button')) {
                        previewFile(file);
                    }
                });
            });
            
            this.on("removedfile", function(file) {
                if (file.serverId) {
                    fetch(document.getElementById('deleteRoute').value, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            filepath: file.serverId
                        })
                    });
                }
                updateUploadedFilesList();
            });
        }
    });

    function updateUploadedFilesList() {
        let files = [];
        myDropzone.files.forEach(function(file) {
            if (file.serverId) {
                files.push({
                    id: file.fileId,
                    path: file.serverId,
                    name: file.name,
                    size: file.size,
                    type: file.type
                });
            }
        });
        document.getElementById('uploaded_files').value = JSON.stringify(files);
    }
    
    // Variables pour la navigation dans les ZIP
    let currentZipFile = null;
    let currentZip = null;
    
    // Fonction pour essayer de lire un fichier comme texte
    function tryReadAsText(zipFile, fileName) {
        const encodings = ['utf-8', 'iso-8859-1', 'windows-1252'];
        let currentEncodingIndex = 0;
        
        const tryNextEncoding = () => {
            if (currentEncodingIndex >= encodings.length) {
                return Promise.reject("Tous les encodages ont échoué");
            }
            
            const encoding = encodings[currentEncodingIndex];
            currentEncodingIndex++;
            
            return zipFile.async('uint8array').then(data => {
                try {
                    const decoder = new TextDecoder(encoding);
                    return decoder.decode(data);
                } catch (e) {
                    return tryNextEncoding();
                }
            });
        };
        
        return tryNextEncoding();
    }
    
    // Fonction pour afficher le contenu texte avec bouton retour unifié
    function displayTextContent(content, fileName) {
        const body = document.getElementById('filePreviewBody');
        
        body.innerHTML = `
            <div class="file-content-header">
                <i class="fas fa-arrow-left zip-back-arrow" onclick="window.showZipContent()"></i>
                <h5 class="mb-0 ml-2">${fileName}</h5>
            </div>
            <pre class="text-content">${escapeHtml(content)}</pre>`;
    }
    
    // Fonction pour échapper le HTML
    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
    
    // Fonction pour afficher le contenu Word avec styles
    function displayWordContent(html, fileName) {
        const body = document.getElementById('filePreviewBody');
        
        body.innerHTML = `
            <div class="word-preview-container">
                <div class="word-header">
                    <i class="fas fa-file-word word-icon"></i>
                    <h4>${fileName}</h4>
                </div>
                <div class="word-content">${html}</div>
            </div>`;
    }
    
    // Fonction pour afficher le contenu Excel avec styles
    function displayExcelContent(content, fileName) {
        const body = document.getElementById('filePreviewBody');
        
        body.innerHTML = `
            <div class="excel-preview-container">
                <div class="excel-header">
                    <i class="fas fa-file-excel excel-icon"></i>
                    <h4>${fileName}</h4>
                </div>
                <div class="excel-content">
                    <pre class="text-content excel-formatted">${escapeHtml(content)}</pre>
                </div>
            </div>`;
    }
    
    // Fonction pour afficher l'arborescence ZIP
    function showZipContent() {
        if (currentZip && currentZipFile) {
            displayZipContent(currentZip, currentZipFile.name);
        }
    }
    
    // Fonction pour prévisualiser un fichier
    function previewFile(file) {
        const modal = document.getElementById('filePreviewModal');
        const title = document.getElementById('filePreviewTitle');
        const body = document.getElementById('filePreviewBody');
        
        body.innerHTML = '<div class="preview-loader"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Chargement en cours...</p></div>';
        modal.style.display = 'flex';
        
        const fileName = file.name.toLowerCase();
        const fileExt = fileName.split('.').pop();
        
        title.textContent = file.name;
        
        if (file.type.startsWith('image/') || ['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
            // Images
            const imgUrl = file.serverId ? `/storage/${file.serverId}` : URL.createObjectURL(file);
            body.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-file-image file-icon image-icon"></i>
                    <div style="max-height: 70vh; overflow: auto;">
                        <img src="${imgUrl}" class="file-media" alt="Prévisualisation">
                    </div>
                </div>`;
        } else if (file.type === 'application/pdf' || fileExt === 'pdf') {
            // PDF
            const pdfUrl = file.serverId ? `/storage/${file.serverId}#toolbar=0` : URL.createObjectURL(file);
            body.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-file-pdf file-icon pdf-icon"></i>
                    <iframe src="${pdfUrl}" class="file-iframe"></iframe>
                </div>`;
        } else if (fileExt === 'mp4') {
            // Vidéo MP4 - Ajout d'un lecteur vidéo HTML5 optimisé
            const videoUrl = file.serverId ? `/storage/${file.serverId}` : URL.createObjectURL(file);
            body.innerHTML = `
                <div class="video-container">
                    <i class="fas fa-file-video file-icon video-icon"></i>
                    <div class="video-wrapper">
                        <video controls autoplay class="video-player">
                            <source src="${videoUrl}" type="video/mp4">
                            Votre navigateur ne supporte pas la lecture vidéo.
                        </video>
                    </div>
                </div>`;
            
            // S'assurer que la vidéo se charge correctement
            const videoElement = body.querySelector('video');
            videoElement.addEventListener('error', function() {
                body.innerHTML = `
                    <div class="alert alert-warning">
                        <p>Impossible de lire la vidéo. Format non supporté ou fichier corrompu.</p>
                        <a href="${videoUrl}" download class="btn btn-primary mt-2">
                            <i class="fas fa-download"></i> Télécharger la vidéo
                        </a>
                    </div>`;
            });
        } else if (fileExt === 'zip') {
            // Archives ZIP
            currentZipFile = file;
            
            if (file.serverId) {
                fetch(`/storage/${file.serverId}`)
                    .then(response => response.blob())
                    .then(blob => {
                        JSZip.loadAsync(blob).then(zip => {
                            currentZip = zip;
                            displayZipContent(zip, file.name);
                        });
                    });
            } else {
                const reader = new FileReader();
                reader.onload = function(event) {
                    JSZip.loadAsync(event.target.result).then(zip => {
                        currentZip = zip;
                        displayZipContent(zip, file.name);
                    });
                };
                reader.readAsArrayBuffer(file);
            }
        } else if (['doc', 'docx'].includes(fileExt)) {
            // Fichiers Word - afficher avec styles
            if (file.serverId) {
                fetch(`/storage/${file.serverId}`)
                    .then(response => response.blob())
                    .then(blob => {
                        convertWordToHtml(blob).then(html => {
                            displayWordContent(html, file.name);
                        });
                    });
            } else {
                convertWordToHtml(file).then(html => {
                    displayWordContent(html, file.name);
                });
            }
        } else if (['xls', 'xlsx'].includes(fileExt)) {
            // Fichiers Excel - afficher en mode texte amélioré
            if (file.serverId) {
                fetch(`/storage/${file.serverId}`)
                    .then(response => response.text())
                    .then(text => {
                        displayExcelContent(text, file.name);
                    })
                    .catch(() => {
                        body.innerHTML = `
                            <div class="alert alert-warning">
                                <p>Impossible d'afficher le contenu du fichier Excel.</p>
                                <a href="/storage/${file.serverId}" download class="btn btn-primary mt-2">
                                    <i class="fas fa-download"></i> Télécharger
                                </a>
                            </div>`;
                    });
            } else {
                const reader = new FileReader();
                reader.onload = function(e) {
                    displayExcelContent(e.target.result, file.name);
                };
                reader.onerror = function() {
                    body.innerHTML = `
                        <div class="alert alert-warning">
                            <p>Impossible d'afficher le contenu du fichier Excel.</p>
                        </div>`;
                };
                reader.readAsText(file);
            }
        } else {
            // Pour tous les autres fichiers, essayer de les afficher comme texte
            if (file.serverId) {
                fetch(`/storage/${file.serverId}`)
                    .then(response => response.text())
                    .then(text => {
                        displayTextContent(text, file.name);
                    })
                    .catch(() => {
                        body.innerHTML = `
                            <div class="alert alert-warning">
                                <p>Impossible d'afficher le contenu du fichier.</p>
                                <a href="/storage/${file.serverId}" download class="btn btn-primary mt-2">
                                    <i class="fas fa-download"></i> Télécharger
                                </a>
                            </div>`;
                    });
            } else {
                const reader = new FileReader();
                reader.onload = function(e) {
                    displayTextContent(e.target.result, file.name);
                };
                reader.onerror = function() {
                    body.innerHTML = `
                        <div class="alert alert-warning">
                            <p>Impossible d'afficher le contenu du fichier.</p>
                        </div>`;
                };
                reader.readAsText(file);
            }
        }
    }
    
    // Fonction pour convertir Word en HTML
    function convertWordToHtml(fileOrBlob) {
        return new Promise((resolve) => {
            const reader = new FileReader();
            
            reader.onload = function(event) {
                mammoth.convertToHtml({arrayBuffer: event.target.result})
                    .then(function(result) {
                        resolve(result.value);
                    })
                    .catch(function(error) {
                        console.error("Erreur de conversion Word:", error);
                        mammoth.extractRawText({arrayBuffer: event.target.result})
                            .then(function(result) {
                                resolve(`<pre>${result.value}</pre>`);
                            })
                            .catch(function() {
                                resolve("<p>Impossible de convertir le document Word</p>");
                            });
                    });
            };
            
            if (fileOrBlob instanceof Blob) {
                reader.readAsArrayBuffer(fileOrBlob);
            } else {
                reader.readAsArrayBuffer(fileOrBlob);
            }
        });
    }
    
    // Fonction pour afficher le contenu d'une archive ZIP
    function displayZipContent(zip, zipName) {
        const body = document.getElementById('filePreviewBody');
        
        const fileTree = {};
        
        zip.forEach((relativePath, file) => {
            const pathParts = relativePath.split('/');
            let currentLevel = fileTree;
            
            for (let i = 0; i < pathParts.length; i++) {
                const part = pathParts[i];
                
                if (i === pathParts.length - 1 && !file.dir) {
                    if (!currentLevel.files) currentLevel.files = [];
                    currentLevel.files.push({
                        name: part,
                        path: relativePath,
                        fileObject: file
                    });
                } else {
                    if (!currentLevel[part]) {
                        currentLevel[part] = { isDir: true };
                    }
                    currentLevel = currentLevel[part];
                }
            }
        });
        
        const html = generateZipTreeHtml(fileTree);
        
        body.innerHTML = `
            <div class="zip-container">
                <div class="zip-header">
                    <i class="fas fa-file-archive file-icon archive-icon"></i>
                    <h4>${zipName}</h4>
                </div>
                <div class="zip-explorer">
                    ${html}
                </div>
            </div>`;
        
        document.querySelectorAll('.folder-header').forEach(header => {
            header.addEventListener('click', function() {
                const content = this.nextElementSibling;
                const icon = this.querySelector('.folder-icon');
                
                if (content.style.display === 'none' || !content.style.display) {
                    content.style.display = 'block';
                    icon.classList.remove('fa-folder');
                    icon.classList.add('fa-folder-open');
                } else {
                    content.style.display = 'none';
                    icon.classList.remove('fa-folder-open');
                    icon.classList.add('fa-folder');
                }
            });
        });
        
        document.querySelectorAll('.file-item').forEach(item => {
            item.addEventListener('click', function() {
                const filePath = this.getAttribute('data-path');
                const fileName = this.getAttribute('data-name');
                const fileExt = fileName.split('.').pop().toLowerCase();
                
                // Afficher un loader
                body.innerHTML = `
                    <div class="preview-loader">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2">Chargement en cours...</p>
                    </div>`;
                
                // Trouver le fichier dans le ZIP
                const fileEntry = findFileInTree(fileTree, filePath);
                
                if (fileEntry && fileEntry.fileObject) {
                    if (['doc', 'docx'].includes(fileExt)) {
                        // Fichier Word - conversion spéciale
                        fileEntry.fileObject.async('uint8array').then(data => {
                            const blob = new Blob([data]);
                            convertWordToHtml(blob).then(html => {
                                displayWordContent(html, fileName);
                            });
                        });
                    } else if (['xls', 'xlsx'].includes(fileExt)) {
                        // Fichier Excel - afficher en mode texte
                        fileEntry.fileObject.async('uint8array').then(data => {
                            const textDecoder = new TextDecoder('utf-8');
                            const text = textDecoder.decode(data);
                            displayExcelContent(text, fileName);
                        });
                    } else if (fileExt === 'mp4') {
                        // Gérer les fichiers vidéo dans le ZIP
                        fileEntry.fileObject.async('uint8array').then(data => {
                            const blob = new Blob([data], {type: 'video/mp4'});
                            const videoUrl = URL.createObjectURL(blob);
                            
                            body.innerHTML = `
                                <div class="video-container">
                                    <div class="video-wrapper mt-3">
                                        <video controls autoplay class="video-player">
                                            <source src="${videoUrl}" type="video/mp4">
                                            Votre navigateur ne supporte pas la lecture vidéo.
                                        </video>
                                    </div>
                                </div>`;
                        });
                    } else {
                        // Autres fichiers - essayer comme texte
                        tryReadAsText(fileEntry.fileObject, fileName)
                            .then(content => {
                                displayTextContent(content, fileName);
                            })
                            .catch(error => {
                                body.innerHTML = `
                                    <div class="file-content-header">
                                        <i class="fas fa-arrow-left zip-back-arrow" onclick="window.showZipContent()"></i>
                                        <h5 class="mb-0 ml-2">${fileName}</h5>
                                    </div>
                                    <div class="alert alert-warning">
                                        Impossible d'afficher le contenu du fichier (${error}).
                                    </div>`;
                            });
                    }
                }
            });
        });
    }
    
    // Fonction pour trouver un fichier dans l'arborescence
    function findFileInTree(tree, path) {
        const parts = path.split('/');
        let current = tree;
        
        for (let i = 0; i < parts.length; i++) {
            const part = parts[i];
            if (i === parts.length - 1) {
                if (current.files) {
                    return current.files.find(f => f.name === part);
                }
                return null;
            }
            current = current[part];
            if (!current) return null;
        }
        return null;
    }
    
    // Fonction pour générer le HTML de l'arborescence ZIP
    function generateZipTreeHtml(tree, path = '') {
        let html = '';
        
        Object.keys(tree).forEach(key => {
            if (key !== 'isDir' && key !== 'files') {
                html += `
                    <div class="folder">
                        <div class="folder-header">
                            <i class="fas fa-folder folder-icon" style="color: #ffc107; margin-right: 5px;"></i>
                            <span>${key}</span>
                        </div>
                        <div class="folder-content" style="display: none;">
                            ${generateZipTreeHtml(tree[key], path ? `${path}/${key}` : key)}
                        </div>
                    </div>`;
            }
        });
        
        if (tree.files && tree.files.length > 0) {
            tree.files.forEach(file => {
                const fileExt = file.name.split('.').pop().toLowerCase();
                let iconClass = 'fa-file-alt';
                
                if (['doc', 'docx'].includes(fileExt)) iconClass = 'fa-file-word';
                else if (['xls', 'xlsx'].includes(fileExt)) iconClass = 'fa-file-excel';
                else if (['pdf'].includes(fileExt)) iconClass = 'fa-file-pdf';
                else if (['mp4'].includes(fileExt)) iconClass = 'fa-file-video';
                
                html += `
                    <div class="file-item" data-path="${file.path}" data-name="${file.name}">
                        <i class="fas ${iconClass}" style="margin-right: 5px;"></i>
                        <span>${file.name}</span>
                    </div>`;
            });
        }
        
        return html;
    }
    
    // Fermer la modal
    document.querySelector('.file-preview-close').addEventListener('click', function() {
        const modal = document.getElementById('filePreviewModal');
        modal.style.display = 'none';
        currentZipFile = null;
        currentZip = null;
    });
    
    document.getElementById('filePreviewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
            currentZipFile = null;
            currentZip = null;
        }
    });
    
    // Exposer les fonctions au scope global
    window.currentZip = null;
    window.currentZipFile = null;
    window.displayZipContent = displayZipContent;
    window.showZipContent = showZipContent;
    
    // Ajouter un style pour l'icône de suppression personnalisée et autres éléments
    const style = document.createElement('style');
    style.textContent = `
        .custom-remove-button {
            position: absolute;
            top: 5px;
            right: 5px;
            z-index: 10;
            cursor: pointer;
            color: #dc3545;
            font-size: 16px;
        }
        .custom-remove-button:hover {
            color: #bd2130;
        }
        .video-container {
            position: relative;
            width: 100%;
            height: 100%;
        }
        .video-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-height: 70vh;
        }
        .video-player {
            max-width: 100%;
            max-height: 70vh;
        }
        .file-content-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .file-content-header h5 {
            margin: 0;
        }
        .zip-back-arrow {
            color: #6c757d;
            cursor: pointer;
            margin-right: 10px;
            font-size: 16px;
        }
        .zip-back-arrow:hover {
            color: #343a40;
        }
        .excel-formatted {
            font-family: monospace;
            white-space: pre;
            overflow-x: auto;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
        }
        .excel-icon {
            color: #217346;
            margin-right: 10px;
            font-size: 20px;
        }
        .excel-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
    `;
    document.head.appendChild(style);
});
});




// // // Configuration Dropzone
// // Dropzone.autoDiscover = false;

// // document.addEventListener('DOMContentLoaded', function() {
// //     let myDropzone = new Dropzone("#multipleFilesUpload", {
// //         url: document.getElementById('uploadRoute').value,
// //         paramName: "file", // Le nom du paramètre qui contiendra le fichier
// //         maxFilesize: 50, // MB
// //         maxFiles: 10,
// //         addRemoveLinks: true,
// //         acceptedFiles: ".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.mp4,.mp3,.zip,.jpg,.jpeg,.png,.txt,.js,.css,.html,.json,.xml,.log,.csv",
// //         headers: {
// //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
// //         },
// //         init: function() {
// //             this.on("success", function(file, response) {
// //                 if (!response.success) {
// //                     console.error("Erreur lors de l'upload:", response.message);
// //                     this.removeFile(file);
// //                     return;
// //                 }
                
// //                 file.previewElement.classList.add("dz-success");
// //                 // Stocker les informations du fichier depuis la réponse
// //                 file.fileId = response.file.id;
// //                 file.filePath = response.file.file_path;
// //                 file.fileType = response.file.file_type;
// //                 file.fileSize = response.file.file_size;
// //                 file.fileUrl = '/storage/' + response.file.file_path; // Construction de l'URL complète
                
// //                 updateUploadedFilesList();
                
// //                 // Ajouter une icône de suppression personnalisée
// //                 const removeButton = document.createElement('div');
// //                 removeButton.className = 'custom-remove-button';
// //                 removeButton.innerHTML = '<i class="fas fa-times-circle"></i>';
// //                 removeButton.addEventListener('click', function(e) {
// //                     e.preventDefault();
// //                     e.stopPropagation();
// //                     myDropzone.removeFile(file);
// //                 });
// //                 file.previewElement.appendChild(removeButton);
                
// //                 file.previewElement.addEventListener("click", function(e) {
// //                     e.preventDefault();
// //                     e.stopPropagation();
// //                     if (!e.target.classList.contains('custom-remove-button') && 
// //                         !e.target.closest('.custom-remove-button')) {
// //                         previewFile(file);
// //                     }
// //                 });
// //             });
            
// //             this.on("removedfile", function(file) {
// //                 if (file.fileId) {
// //                     fetch(document.getElementById('deleteRoute').value, {
// //                         method: 'POST',
// //                         headers: {
// //                             'Content-Type': 'application/json',
// //                             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
// //                         },
// //                         body: JSON.stringify({
// //                             file_id: file.fileId
// //                         })
// //                     }).then(response => response.json())
// //                       .then(data => {
// //                           if (!data.success) {
// //                               console.error("Erreur lors de la suppression:", data.message);
// //                           }
// //                       });
// //                 }
// //                 updateUploadedFilesList();
// //             });
            
// //             // Si vous avez des fichiers pré-existants à charger
// //             loadExistingFiles();
// //         }
// //     });

// //     function loadExistingFiles() {
// //         const existingFiles = JSON.parse(document.getElementById('uploaded_files').value || [];
        
// //         existingFiles.forEach(fileData => {
// //             // Créer un fichier factice pour Dropzone
// //             const mockFile = {
// //                 name: fileData.name,
// //                 size: fileData.file_size,
// //                 type: fileData.file_type,
// //                 fileId: fileData.id,
// //                 filePath: fileData.file_path,
// //                 fileUrl: '/storage/' + fileData.file_path,
// //                 status: Dropzone.ADDED,
// //                 accepted: true
// //             };
            
// //             // Ajouter le fichier à Dropzone
// //             myDropzone.emit("addedfile", mockFile);
// //             myDropzone.emit("complete", mockFile);
// //             myDropzone.emit("success", mockFile, {file: fileData});
            
// //             // Ajouter l'icône de suppression
// //             const removeButton = document.createElement('div');
// //             removeButton.className = 'custom-remove-button';
// //             removeButton.innerHTML = '<i class="fas fa-times-circle"></i>';
// //             removeButton.addEventListener('click', function(e) {
// //                 e.preventDefault();
// //                 e.stopPropagation();
// //                 myDropzone.removeFile(mockFile);
// //             });
            
// //             // Trouver l'élément de prévisualisation
// //             const previewElement = myDropzone.files.find(f => f.fileId === fileData.id)?.previewElement;
// //             if (previewElement) {
// //                 previewElement.appendChild(removeButton);
// //                 previewElement.addEventListener("click", function(e) {
// //                     e.preventDefault();
// //                     e.stopPropagation();
// //                     if (!e.target.classList.contains('custom-remove-button') && 
// //                         !e.target.closest('.custom-remove-button')) {
// //                         previewFile(mockFile);
// //                     }
// //                 });
// //             }
// //         });
// //     }

// //     function updateUploadedFilesList() {
// //         let files = [];
// //         myDropzone.files.forEach(function(file) {
// //             if (file.fileId) {
// //                 files.push({
// //                     id: file.fileId,
// //                     file_path: file.filePath,
// //                     name: file.name,
// //                     file_size: file.fileSize,
// //                     file_type: file.fileType
// //                 });
// //             }
// //         });
// //         document.getElementById('uploaded_files').value = JSON.stringify(files);
// //     }
    
// //     // Variables pour la navigation dans les ZIP
// //     let currentZipFile = null;
// //     let currentZip = null;
    
// //     // Fonction pour prévisualiser un fichier
// //     function previewFile(file) {
// //         const modal = document.getElementById('filePreviewModal');
// //         const title = document.getElementById('filePreviewTitle');
// //         const body = document.getElementById('filePreviewBody');
        
// //         body.innerHTML = '<div class="preview-loader"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Chargement en cours...</p></div>';
// //         modal.style.display = 'flex';
        
// //         const fileName = file.name.toLowerCase();
// //         const fileExt = fileName.split('.').pop();
        
// //         title.textContent = file.name;
        
// //         // Déterminer l'URL à utiliser pour le fichier
// //         const fileUrl = file.fileUrl || (file.filePath ? '/storage/' + file.filePath : URL.createObjectURL(file));
        
// //         if (file.type.startsWith('image/') || ['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
// //             // Images
// //             body.innerHTML = `
// //                 <div class="text-center">
// //                     <i class="fas fa-file-image file-icon image-icon"></i>
// //                     <div style="max-height: 70vh; overflow: auto;">
// //                         <img src="${fileUrl}" class="file-media" alt="Prévisualisation" onerror="handlePreviewError(this, 'image')">
// //                     </div>
// //                 </div>`;
// //         } else if (file.type === 'application/pdf' || fileExt === 'pdf') {
// //             // PDF
// //             body.innerHTML = `
// //                 <div class="text-center">
// //                     <i class="fas fa-file-pdf file-icon pdf-icon"></i>
// //                     <iframe src="${fileUrl}#toolbar=0" class="file-iframe" onerror="handlePreviewError(this, 'pdf')"></iframe>
// //                 </div>`;
// //         } else if (fileExt === 'mp4') {
// //             // Vidéo MP4
// //             body.innerHTML = `
// //                 <div class="video-container">
// //                     <i class="fas fa-file-video file-icon video-icon"></i>
// //                     <div class="video-wrapper">
// //                         <video controls autoplay class="video-player" onerror="handlePreviewError(this, 'video')">
// //                             <source src="${fileUrl}" type="video/mp4">
// //                             Votre navigateur ne supporte pas la lecture vidéo.
// //                         </video>
// //                     </div>
// //                 </div>`;
// //         } else if (fileExt === 'zip') {
// //             // Archives ZIP
// //             currentZipFile = file;
            
// //             if (file.filePath) {
// //                 fetch(fileUrl)
// //                     .then(response => {
// //                         if (!response.ok) throw new Error('Network response was not ok');
// //                         return response.blob();
// //                     })
// //                     .then(blob => {
// //                         JSZip.loadAsync(blob).then(zip => {
// //                             currentZip = zip;
// //                             displayZipContent(zip, file.name);
// //                         }).catch(error => {
// //                             console.error("Erreur lors du chargement du ZIP:", error);
// //                             showErrorPreview(file, "Impossible d'ouvrir l'archive ZIP");
// //                         });
// //                     })
// //                     .catch(error => {
// //                         console.error("Erreur lors de la récupération du ZIP:", error);
// //                         showErrorPreview(file, "Impossible de charger le fichier ZIP");
// //                     });
// //             } else {
// //                 const reader = new FileReader();
// //                 reader.onload = function(event) {
// //                     JSZip.loadAsync(event.target.result).then(zip => {
// //                         currentZip = zip;
// //                         displayZipContent(zip, file.name);
// //                     }).catch(error => {
// //                         console.error("Erreur lors du chargement du ZIP:", error);
// //                         showErrorPreview(file, "Impossible d'ouvrir l'archive ZIP");
// //                     });
// //                 };
// //                 reader.onerror = function() {
// //                     showErrorPreview(file, "Erreur lors de la lecture du fichier ZIP");
// //                 };
// //                 reader.readAsArrayBuffer(file);
// //             }
// //         } else if (['doc', 'docx'].includes(fileExt)) {
// //             // Fichiers Word
// //             if (file.filePath) {
// //                 fetch(fileUrl)
// //                     .then(response => {
// //                         if (!response.ok) throw new Error('Network response was not ok');
// //                         return response.blob();
// //                     })
// //                     .then(blob => {
// //                         convertWordToHtml(blob).then(html => {
// //                             displayWordContent(html, file.name);
// //                         }).catch(error => {
// //                             console.error("Erreur de conversion Word:", error);
// //                             showErrorPreview(file, "Impossible de convertir le document Word");
// //                         });
// //                     })
// //                     .catch(error => {
// //                         console.error("Erreur lors de la récupération du Word:", error);
// //                         showErrorPreview(file, "Impossible de charger le fichier Word");
// //                     });
// //             } else {
// //                 convertWordToHtml(file).then(html => {
// //                     displayWordContent(html, file.name);
// //                 }).catch(error => {
// //                     console.error("Erreur de conversion Word:", error);
// //                     showErrorPreview(file, "Impossible de convertir le document Word");
// //                 });
// //             }
// //         } else {
// //             // Pour les autres fichiers, essayer de les afficher comme texte
// //             if (file.filePath) {
// //                 fetch(fileUrl)
// //                     .then(response => {
// //                         if (!response.ok) throw new Error('Network response was not ok');
// //                         return response.text();
// //                     })
// //                     .then(text => {
// //                         displayTextContent(text, file.name);
// //                     })
// //                     .catch(() => {
// //                         showErrorPreview(file, "Impossible d'afficher le contenu du fichier");
// //                     });
// //             } else {
// //                 const reader = new FileReader();
// //                 reader.onload = function(e) {
// //                     displayTextContent(e.target.result, file.name);
// //                 };
// //                 reader.onerror = function() {
// //                     showErrorPreview(file, "Erreur lors de la lecture du fichier");
// //                 };
// //                 reader.readAsText(file);
// //             }
// //         }
// //     }
    
// //     // Fonction pour afficher un message d'erreur
// //     function showErrorPreview(file, message) {
// //         const body = document.getElementById('filePreviewBody');
// //         const fileUrl = file.fileUrl || (file.filePath ? '/storage/' + file.filePath : '#');
        
// //         body.innerHTML = `
// //             <div class="alert alert-warning">
// //                 <p>${message}</p>
// //                 ${file.filePath ? `<a href="${fileUrl}" download class="btn btn-primary mt-2">
// //                     <i class="fas fa-download"></i> Télécharger le fichier
// //                 </a>` : ''}
// //             </div>`;
// //     }
    
// //     // Fonction pour gérer les erreurs de prévisualisation
// //     window.handlePreviewError = function(element, type) {
// //         const container = element.parentElement;
// //         container.innerHTML = `
// //             <div class="alert alert-warning">
// //                 <p>Impossible de charger la prévisualisation ${type}</p>
// //                 <a href="${element.src || element.querySelector('source')?.src}" download class="btn btn-primary mt-2">
// //                     <i class="fas fa-download"></i> Télécharger le fichier
// //                 </a>
// //             </div>`;
// //     };
    
// //     // Fonction pour convertir Word en HTML
// //     function convertWordToHtml(fileOrBlob) {
// //         return new Promise((resolve) => {
// //             const reader = new FileReader();
            
// //             reader.onload = function(event) {
// //                 mammoth.convertToHtml({arrayBuffer: event.target.result})
// //                     .then(function(result) {
// //                         resolve(result.value);
// //                     })
// //                     .catch(function(error) {
// //                         console.error("Erreur de conversion Word:", error);
// //                         mammoth.extractRawText({arrayBuffer: event.target.result})
// //                             .then(function(result) {
// //                                 resolve(`<pre>${result.value}</pre>`);
// //                             })
// //                             .catch(function() {
// //                                 resolve("<p>Impossible de convertir le document Word</p>");
// //                             });
// //                     });
// //             };
            
// //             if (fileOrBlob instanceof Blob) {
// //                 reader.readAsArrayBuffer(fileOrBlob);
// //             } else {
// //                 reader.readAsArrayBuffer(fileOrBlob);
// //             }
// //         });
// //     }
    
// //     // Fonction pour afficher le contenu texte
// //     function displayTextContent(content, fileName) {
// //         const body = document.getElementById('filePreviewBody');
        
// //         body.innerHTML = `
// //             <div class="file-content-header">
// //                 <i class="fas fa-arrow-left zip-back-arrow" onclick="window.showZipContent()"></i>
// //                 <h5 class="mb-0 ml-2">${fileName}</h5>
// //             </div>
// //             <pre class="text-content">${escapeHtml(content)}</pre>`;
// //     }
    
// //     // Fonction pour échapper le HTML
// //     function escapeHtml(unsafe) {
// //         return unsafe
// //             .replace(/&/g, "&amp;")
// //             .replace(/</g, "&lt;")
// //             .replace(/>/g, "&gt;")
// //             .replace(/"/g, "&quot;")
// //             .replace(/'/g, "&#039;");
// //     }
    
// //     // Fonction pour afficher le contenu Word avec styles
// //     function displayWordContent(html, fileName) {
// //         const body = document.getElementById('filePreviewBody');
        
// //         body.innerHTML = `
// //             <div class="word-preview-container">
// //                 <div class="word-header">
// //                     <i class="fas fa-file-word word-icon"></i>
// //                     <h4>${fileName}</h4>
// //                 </div>
// //                 <div class="word-content">${html}</div>
// //             </div>`;
// //     }
    
// //     // Fonction pour afficher l'arborescence ZIP
// //     function showZipContent() {
// //         if (currentZip && currentZipFile) {
// //             displayZipContent(currentZip, currentZipFile.name);
// //         }
// //     }
    
// //     // Fonction pour afficher le contenu d'une archive ZIP
// //     function displayZipContent(zip, zipName) {
// //         const body = document.getElementById('filePreviewBody');
        
// //         const fileTree = {};
        
// //         zip.forEach((relativePath, file) => {
// //             const pathParts = relativePath.split('/');
// //             let currentLevel = fileTree;
            
// //             for (let i = 0; i < pathParts.length; i++) {
// //                 const part = pathParts[i];
                
// //                 if (i === pathParts.length - 1 && !file.dir) {
// //                     if (!currentLevel.files) currentLevel.files = [];
// //                     currentLevel.files.push({
// //                         name: part,
// //                         path: relativePath,
// //                         fileObject: file
// //                     });
// //                 } else {
// //                     if (!currentLevel[part]) {
// //                         currentLevel[part] = { isDir: true };
// //                     }
// //                     currentLevel = currentLevel[part];
// //                 }
// //             }
// //         });
        
// //         const html = generateZipTreeHtml(fileTree);
        
// //         body.innerHTML = `
// //             <div class="zip-container">
// //                 <div class="zip-header">
// //                     <i class="fas fa-file-archive file-icon archive-icon"></i>
// //                     <h4>${zipName}</h4>
// //                 </div>
// //                 <div class="zip-explorer">
// //                     ${html}
// //                 </div>
// //             </div>`;
        
// //         document.querySelectorAll('.folder-header').forEach(header => {
// //             header.addEventListener('click', function() {
// //                 const content = this.nextElementSibling;
// //                 const icon = this.querySelector('.folder-icon');
                
// //                 if (content.style.display === 'none' || !content.style.display) {
// //                     content.style.display = 'block';
// //                     icon.classList.remove('fa-folder');
// //                     icon.classList.add('fa-folder-open');
// //                 } else {
// //                     content.style.display = 'none';
// //                     icon.classList.remove('fa-folder-open');
// //                     icon.classList.add('fa-folder');
// //                 }
// //             });
// //         });
        
// //         document.querySelectorAll('.file-item').forEach(item => {
// //             item.addEventListener('click', function() {
// //                 const filePath = this.getAttribute('data-path');
// //                 const fileName = this.getAttribute('data-name');
// //                 const fileExt = fileName.split('.').pop().toLowerCase();
                
// //                 // Afficher un loader
// //                 body.innerHTML = `
// //                     <div class="preview-loader">
// //                         <div class="spinner-border text-primary" role="status"></div>
// //                         <p class="mt-2">Chargement en cours...</p>
// //                     </div>`;
                
// //                 // Trouver le fichier dans le ZIP
// //                 const fileEntry = findFileInTree(fileTree, filePath);
                
// //                 if (fileEntry && fileEntry.fileObject) {
// //                     if (['doc', 'docx'].includes(fileExt)) {
// //                         // Fichier Word
// //                         fileEntry.fileObject.async('uint8array').then(data => {
// //                             const blob = new Blob([data]);
// //                             convertWordToHtml(blob).then(html => {
// //                                 displayWordContent(html, fileName);
// //                             }).catch(error => {
// //                                 console.error("Erreur de conversion Word:", error);
// //                                 showErrorInZipPreview(fileName, "Impossible de convertir le document Word");
// //                             });
// //                         }).catch(error => {
// //                             console.error("Erreur de lecture du fichier Word:", error);
// //                             showErrorInZipPreview(fileName, "Erreur lors de la lecture du fichier Word");
// //                         });
// //                     } else {
// //                         // Autres fichiers texte
// //                         fileEntry.fileObject.async('text').then(content => {
// //                             displayTextContent(content, fileName);
// //                         }).catch(error => {
// //                             console.error("Erreur de lecture du fichier:", error);
// //                             showErrorInZipPreview(fileName, `Impossible d'afficher le contenu du fichier`);
// //                         });
// //                     }
// //                 } else {
// //                     showErrorInZipPreview(fileName, "Fichier introuvable dans l'archive");
// //                 }
// //             });
// //         });
// //     }
    
// //     // Fonction pour afficher une erreur dans la prévisualisation ZIP
// //     function showErrorInZipPreview(fileName, message) {
// //         const body = document.getElementById('filePreviewBody');
        
// //         body.innerHTML = `
// //             <div class="file-content-header">
// //                 <i class="fas fa-arrow-left zip-back-arrow" onclick="window.showZipContent()"></i>
// //                 <h5 class="mb-0 ml-2">${fileName}</h5>
// //             </div>
// //             <div class="alert alert-warning">
// //                 ${message}
// //             </div>`;
// //     }
    
// //     // Fonction pour trouver un fichier dans l'arborescence
// //     function findFileInTree(tree, path) {
// //         const parts = path.split('/');
// //         let current = tree;
        
// //         for (let i = 0; i < parts.length; i++) {
// //             const part = parts[i];
// //             if (i === parts.length - 1) {
// //                 if (current.files) {
// //                     return current.files.find(f => f.name === part);
// //                 }
// //                 return null;
// //             }
// //             current = current[part];
// //             if (!current) return null;
// //         }
// //         return null;
// //     }
    
// //     // Fonction pour générer le HTML de l'arborescence ZIP
// //     function generateZipTreeHtml(tree, path = '') {
// //         let html = '';
        
// //         Object.keys(tree).forEach(key => {
// //             if (key !== 'isDir' && key !== 'files') {
// //                 html += `
// //                     <div class="folder">
// //                         <div class="folder-header">
// //                             <i class="fas fa-folder folder-icon" style="color: #ffc107; margin-right: 5px;"></i>
// //                             <span>${key}</span>
// //                         </div>
// //                         <div class="folder-content" style="display: none;">
// //                             ${generateZipTreeHtml(tree[key], path ? `${path}/${key}` : key)}
// //                         </div>
// //                     </div>`;
// //             }
// //         });
        
// //         if (tree.files && tree.files.length > 0) {
// //             tree.files.forEach(file => {
// //                 const fileExt = file.name.split('.').pop().toLowerCase();
// //                 let iconClass = 'fa-file-alt';
                
// //                 if (['doc', 'docx'].includes(fileExt)) iconClass = 'fa-file-word';
// //                 else if (['xls', 'xlsx'].includes(fileExt)) iconClass = 'fa-file-excel';
// //                 else if (['pdf'].includes(fileExt)) iconClass = 'fa-file-pdf';
// //                 else if (['mp4'].includes(fileExt)) iconClass = 'fa-file-video';
                
// //                 html += `
// //                     <div class="file-item" data-path="${file.path}" data-name="${file.name}">
// //                         <i class="fas ${iconClass}" style="margin-right: 5px;"></i>
// //                         <span>${file.name}</span>
// //                     </div>`;
// //             });
// //         }
        
// //         return html;
// //     }
    
// //     // Fermer la modal
// //     document.querySelector('.file-preview-close').addEventListener('click', function() {
// //         const modal = document.getElementById('filePreviewModal');
// //         modal.style.display = 'none';
// //         currentZipFile = null;
// //         currentZip = null;
// //     });
    
// //     document.getElementById('filePreviewModal').addEventListener('click', function(e) {
// //         if (e.target === this) {
// //             this.style.display = 'none';
// //             currentZipFile = null;
// //             currentZip = null;
// //         }
// //     });
    
// //     // Exposer les fonctions au scope global
// //     window.currentZip = null;
// //     window.currentZipFile = null;
// //     window.displayZipContent = displayZipContent;
// //     window.showZipContent = showZipContent;
    
// //     // Ajouter les styles CSS nécessaires
// //     const style = document.createElement('style');
// //     style.textContent = `
// //         .custom-remove-button {
// //             position: absolute;
// //             top: 5px;
// //             right: 5px;
// //             z-index: 10;
// //             cursor: pointer;
// //             color: #dc3545;
// //             font-size: 16px;
// //         }
// //         .custom-remove-button:hover {
// //             color: #bd2130;
// //         }
// //         .file-preview-modal {
// //             display: none;
// //             position: fixed;
// //             z-index: 1050;
// //             left: 0;
// //             top: 0;
// //             width: 100%;
// //             height: 100%;
// //             background-color: rgba(0,0,0,0.5);
// //             justify-content: center;
// //             align-items: center;
// //         }
// //         .file-preview-content {
// //             background-color: #fff;
// //             border-radius: 5px;
// //             width: 80%;
// //             max-width: 900px;
// //             max-height: 90vh;
// //             overflow: auto;
// //             box-shadow: 0 4px 8px rgba(0,0,0,0.1);
// //         }
// //         .file-preview-header {
// //             padding: 15px;
// //             border-bottom: 1px solid #eee;
// //             display: flex;
// //             justify-content: space-between;
// //             align-items: center;
// //         }
// //         .file-preview-title {
// //             margin: 0;
// //             font-size: 1.25rem;
// //         }
// //         .file-preview-close {
// //             font-size: 1.5rem;
// //             cursor: pointer;
// //             color: #6c757d;
// //         }
// //         .file-preview-close:hover {
// //             color: #343a40;
// //         }
// //         .file-preview-body {
// //             padding: 20px;
// //         }
// //         .preview-loader {
// //             display: flex;
// //             flex-direction: column;
// //             align-items: center;
// //             justify-content: center;
// //             height: 200px;
// //         }
// //         .file-icon {
// //             font-size: 48px;
// //             margin-bottom: 15px;
// //         }
// //         .image-icon {
// //             color: #17a2b8;
// //         }
// //         .pdf-icon {
// //             color: #dc3545;
// //         }
// //         .video-icon {
// //             color: #28a745;
// //         }
// //         .archive-icon {
// //             color: #ffc107;
// //         }
// //         .file-media {
// //             max-width: 100%;
// //             max-height: 70vh;
// //         }
// //         .file-iframe {
// //             width: 100%;
// //             height: 70vh;
// //             border: none;
// //         }
// //         .text-content {
// //             white-space: pre-wrap;
// //             font-family: monospace;
// //             background-color: #f8f9fa;
// //             padding: 15px;
// //             border-radius: 4px;
// //             overflow-x: auto;
// //         }
// //         .zip-container {
// //             width: 100%;
// //             height: 100%;
// //         }
// //         .zip-header {
// //             display: flex;
// //             align-items: center;
// //             margin-bottom: 15px;
// //         }
// //         .zip-explorer {
// //             max-height: 70vh;
// //             overflow-y: auto;
// //             border: 1px solid #eee;
// //             border-radius: 4px;
// //             padding: 10px;
// //         }
// //         .folder {
// //             margin-bottom: 5px;
// //         }
// //         .folder-header {
// //             cursor: pointer;
// //             padding: 5px;
// //             border-radius: 3px;
// //         }
// //         .folder-header:hover {
// //             background-color: #f8f9fa;
// //         }
// //         .folder-content {
// //             padding-left: 20px;
// //             margin-top: 5px;
// //         }
// //         .file-item {
// //             cursor: pointer;
// //             padding: 5px;
// //             border-radius: 3px;
// //             margin-left: 20px;
// //         }
// //         .file-item:hover {
// //             background-color: #f8f9fa;
// //         }
// //         .word-preview-container {
// //             width: 100%;
// //             height: 100%;
// //             overflow: auto;
// //         }
// //         .word-header {
// //             display: flex;
// //             align-items: center;
// //             margin-bottom: 15px;
// //         }
// //         .word-icon {
// //             color: #2b579a;
// //             margin-right: 10px;
// //             font-size: 20px;
// //         }
// //         .word-content {
// //             background-color: #fff;
// //             padding: 20px;
// //             border: 1px solid #ddd;
// //             border-radius: 4px;
// //         }
// //         .alert {
// //             padding: 15px;
// //             margin-bottom: 20px;
// //             border: 1px solid transparent;
// //             border-radius: 4px;
// //         }
// //         .alert-warning {
// //             color: #856404;
// //             background-color: #fff3cd;
// //             border-color: #ffeeba;
// //         }
// //         .btn {
// //             display: inline-block;
// //             font-weight: 400;
// //             text-align: center;
// //             white-space: nowrap;
// //             vertical-align: middle;
// //             user-select: none;
// //             border: 1px solid transparent;
// //             padding: 0.375rem 0.75rem;
// //             font-size: 1rem;
// //             line-height: 1.5;
// //             border-radius: 0.25rem;
// //             transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
// //         }
// //         .btn-primary {
// //             color: #fff;
// //             background-color: #007bff;
// //             border-color: #007bff;
// //         }
// //         .btn-primary:hover {
// //             color: #fff;
// //             background-color: #0069d9;
// //             border-color: #0062cc;
// //         }
// //         .mt-2 {
// //             margin-top: 0.5rem !important;
// //         }
// //         .text-center {
// //             text-align: center !important;
// //         }
// //         .file-content-header {
// //             display: flex;
// //             align-items: center;
// //             margin-bottom: 15px;
// //         }
// //         .file-content-header h5 {
// //             margin: 0;
// //         }
// //         .zip-back-arrow {
// //             color: #6c757d;
// //             cursor: pointer;
// //             margin-right: 10px;
// //             font-size: 16px;
// //         }
// //         .zip-back-arrow:hover {
// //             color: #343a40;
// //         }
// //         .video-container {
// //             position: relative;
// //             width: 100%;
// //             height: 100%;
// //         }
// //         .video-wrapper {
// //             display: flex;
// //             justify-content: center;
// //             align-items: center;
// //             width: 100%;
// //             max-height: 70vh;
// //         }
// //         .video-player {
// //             max-width: 100%;
// //             max-height: 70vh;
// //         }
// //     `;
// //     document.head.appendChild(style);
// // });





// Attendre que le DOM soit chargé
// document.addEventListener('DOMContentLoaded', function() {
//     // Configuration Dropzone
//     Dropzone.autoDiscover = false;

//     // Initialisation de Select2
//     $('.select2-chapitre').select2({
//         placeholder: "Sélectionnez un chapitre",
//         allowClear: true
//     });

//     // Variables globales
//     let uploadedFiles = [];
//     const allowedFileTypes = {
//         'application/pdf': { icon: 'fas fa-file-pdf pdf-icon', type: 'pdf' },
//         'application/msword': { icon: 'fas fa-file-word word-icon', type: 'word' },
//         'application/vnd.openxmlformats-officedocument.wordprocessingml.document': { icon: 'fas fa-file-word word-icon', type: 'word' },
//         'application/vnd.ms-excel': { icon: 'fas fa-file-excel excel-icon', type: 'excel' },
//         'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': { icon: 'fas fa-file-excel excel-icon', type: 'excel' },
//         'application/vnd.ms-powerpoint': { icon: 'fas fa-file-powerpoint powerpoint-icon', type: 'powerpoint' },
//         'application/vnd.openxmlformats-officedocument.presentationml.presentation': { icon: 'fas fa-file-powerpoint powerpoint-icon', type: 'powerpoint' },
//         'image/jpeg': { icon: 'fas fa-file-image image-icon', type: 'image' },
//         'image/png': { icon: 'fas fa-file-image image-icon', type: 'image' },
//         'image/gif': { icon: 'fas fa-file-image image-icon', type: 'image' },
//         'video/mp4': { icon: 'fas fa-file-video video-icon', type: 'video' },
//         'video/webm': { icon: 'fas fa-file-video video-icon', type: 'video' },
//         'audio/mpeg': { icon: 'fas fa-file-audio audio-icon', type: 'audio' },
//         'audio/wav': { icon: 'fas fa-file-audio audio-icon', type: 'audio' },
//         'application/zip': { icon: 'fas fa-file-archive archive-icon', type: 'zip' },
//         'application/x-zip-compressed': { icon: 'fas fa-file-archive archive-icon', type: 'zip' },
//         'text/plain': { icon: 'fas fa-file-alt text-icon', type: 'text' },
//         'text/html': { icon: 'fas fa-file-code text-icon', type: 'html' }
//     };
    
//     // Cache pour les contenus extraits de fichiers ZIP
//     const zipContentCache = {};
//     let currentZipPath = [];
    
//     // Initialiser Dropzone
//     const myDropzone = new Dropzone("#multipleFilesUpload", {
//         url: document.getElementById('uploadRoute').value,
//         paramName: "file",
//         maxFilesize: 50, // MB
//         chunking: true,
//         chunkSize: 2000000, // 2MB
//         parallelChunkUploads: true,
//         acceptedFiles: ".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.mp4,.mp3,.zip,.jpg,.jpeg,.png,.gif,.txt,.html",
//         addRemoveLinks: true,
//         dictRemoveFile: "Supprimer",
//         headers: {
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//         },
//         init: function() {
//             const dropzone = this;
            
//             // Lorsqu'un fichier est ajouté
//             this.on("addedfile", function(file) {
//                 // Ajouter une icône au fichier selon son type
//                 addFileIcon(file);
                
//                 // Ajouter un événement de clic pour prévisualiser le fichier
//                 file.previewElement.addEventListener("click", function(e) {
//                     // Si on clique sur le bouton de suppression, ne pas prévisualiser
//                     if (e.target.classList.contains('dz-remove') || e.target.closest('.dz-remove')) {
//                         return;
//                     }
//                     previewFile(file);
//                 });
//             });
            
//             // Quand un fichier est supprimé
//             this.on("removedfile", function(file) {
//                 if (file.status === 'success' && file.serverFileName) {
//                     // Supprimer le fichier du serveur
//                     deleteFileFromServer(file.serverFileName);
                    
//                     // Mettre à jour la liste des fichiers téléchargés
//                     uploadedFiles = uploadedFiles.filter(f => f.serverFileName !== file.serverFileName);
//                     updateUploadedFilesList();
//                 }
//             });
            
//             // Quand un fichier est téléchargé avec succès
//             this.on("success", function(file, response) {
//                 if (response.success) {
//                     file.serverFileName = response.fileName;
//                     file.serverPath = response.path;
//                     file.originalName = file.name;
                    
//                     // Ajouter le fichier à notre liste
//                     uploadedFiles.push({
//                         serverFileName: response.fileName,
//                         serverPath: response.path,
//                         originalName: file.name,
//                         type: getFileType(file),
//                         size: file.size
//                     });
                    
//                     updateUploadedFilesList();
//                 } else {
//                     console.error("Erreur lors de l'upload:", response.message);
//                     dropzone.removeFile(file);
//                     alert("Erreur lors de l'upload: " + response.message);
//                 }
//             });
            
//             // En cas d'erreur
//             this.on("error", function(file, errorMessage) {
//                 console.error("Erreur Dropzone:", errorMessage);
//                 if (typeof errorMessage === 'string') {
//                     alert("Erreur: " + errorMessage);
//                 } else if (errorMessage.message) {
//                     alert("Erreur: " + errorMessage.message);
//                 }
//             });
//         }
//     });
    
//     // Ajouter une icône au fichier en fonction de son type
//     function addFileIcon(file) {
//         const fileType = file.type;
//         const fileTypeInfo = allowedFileTypes[fileType] || { icon: 'fas fa-file default-icon', type: 'default' };
        
//         // Trouver l'élément .dz-details
//         const dzDetails = file.previewElement.querySelector('.dz-details');
        
//         // Créer l'élément d'icône
//         const iconElement = document.createElement('div');
//         iconElement.classList.add('file-icon');
//         iconElement.innerHTML = `<i class="${fileTypeInfo.icon}"></i>`;
        
//         // Insérer l'icône au début de .dz-details
//         if (dzDetails) {
//             dzDetails.insertBefore(iconElement, dzDetails.firstChild);
//         }
//     }
    
//     // Obtenir le type de fichier (pour la classification)
//     function getFileType(file) {
//         const fileType = file.type;
//         if (allowedFileTypes[fileType]) {
//             return allowedFileTypes[fileType].type;
//         }
        
//         // Si le type n'est pas dans notre liste, essayons de déduire à partir de l'extension
//         const extension = file.name.split('.').pop().toLowerCase();
        
//         switch (extension) {
//             case 'pdf': return 'pdf';
//             case 'doc': case 'docx': return 'word';
//             case 'xls': case 'xlsx': return 'excel';
//             case 'ppt': case 'pptx': return 'powerpoint';
//             case 'jpg': case 'jpeg': case 'png': case 'gif': return 'image';
//             case 'mp4': case 'webm': case 'avi': return 'video';
//             case 'mp3': case 'wav': return 'audio';
//             case 'zip': case 'rar': return 'zip';
//             case 'txt': return 'text';
//             case 'html': case 'htm': return 'html';
//             default: return 'default';
//         }
//     }
    
//     // Mettre à jour la liste des fichiers téléchargés (champ caché)
//     function updateUploadedFilesList() {
//         document.getElementById('uploaded_files').value = JSON.stringify(uploadedFiles);
//     }
    
//     // Supprimer un fichier du serveur
//     function deleteFileFromServer(fileName) {
//         fetch(document.getElementById('deleteRoute').value, {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//             },
//             body: JSON.stringify({ fileName: fileName })
//         })
//         .then(response => response.json())
//         .then(data => {
//             if (!data.success) {
//                 console.error("Erreur lors de la suppression du fichier:", data.message);
//             }
//         })
//         .catch(error => {
//             console.error("Erreur lors de la suppression du fichier:", error);
//         });
//     }
    
//     // -------------- FONCTIONS DE PRÉVISUALISATION --------------
    
//     // Fonction principale de prévisualisation des fichiers
//     function previewFile(file) {
//         const filePreviewModal = document.getElementById('filePreviewModal');
//         const filePreviewTitle = document.getElementById('filePreviewTitle');
//         const filePreviewBody = document.getElementById('filePreviewBody');
        
//         // Vider le contenu précédent
//         filePreviewBody.innerHTML = `
//             <div class="preview-loader">
//                 <div class="spinner-border text-primary" role="status">
//                     <span class="visually-hidden">Chargement...</span>
//                 </div>
//             </div>
//         `;
        
//         // Mettre à jour le titre
//         filePreviewTitle.textContent = file.originalName || file.name;
        
//         // Afficher la modal
//         filePreviewModal.style.display = 'flex';
        
//         // Prévisualiser le fichier selon son type
//         const fileType = getFileType(file);
//         switch (fileType) {
//             case 'pdf':
//                 previewPDF(file, filePreviewBody);
//                 break;
//             case 'word':
//                 previewWord(file, filePreviewBody);
//                 break;
//             case 'excel':
//                 previewExcel(file, filePreviewBody);
//                 break;
//             case 'powerpoint':
//                 previewPowerPoint(file, filePreviewBody);
//                 break;
//             case 'image':
//                 previewImage(file, filePreviewBody);
//                 break;
//             case 'video':
//                 previewVideo(file, filePreviewBody);
//                 break;
//             case 'audio':
//                 previewAudio(file, filePreviewBody);
//                 break;
//             case 'zip':
//                 previewZip(file, filePreviewBody);
//                 break;
//             case 'text':
//                 previewText(file, filePreviewBody);
//                 break;
//             case 'html':
//                 previewHTML(file, filePreviewBody);
//                 break;
//             default:
//                 filePreviewBody.innerHTML = `
//                     <div class="alert alert-info">
//                         <i class="fas fa-info-circle"></i> 
//                         La prévisualisation n'est pas disponible pour ce type de fichier.
//                     </div>
//                     <div class="text-center mt-3">
//                         <i class="fas fa-file default-icon" style="font-size: 5rem;"></i>
//                         <p class="mt-3">${file.originalName || file.name}</p>
//                     </div>
//                 `;
//         }
//     }
    
//     // Prévisualisation des PDF
//     function previewPDF(file, container) {
//         container.innerHTML = `
//             <iframe src="${file.serverPath}" class="file-iframe"></iframe>
//         `;
//     }
    
//     // Prévisualisation des documents Word
//     function previewWord(file, container) {
//         // Nous allons simuler la conversion Word -> HTML
//         // Dans un environnement réel, vous auriez besoin d'un service de conversion côté serveur
        
//         fetch(`/api/convert-word?path=${encodeURIComponent(file.serverPath)}`)
//             .then(response => response.json())
//             .then(data => {
//                 if (data.success) {
//                     container.innerHTML = `
//                         <div class="word-content">
//                             ${data.html}
//                         </div>
//                     `;
//                 } else {
//                     container.innerHTML = `
//                         <div class="alert alert-warning">
//                             <i class="fas fa-exclamation-triangle"></i> 
//                             La conversion du document Word a échoué.
//                         </div>
//                         <p class="text-center">
//                             <a href="${file.serverPath}" class="btn btn-primary" target="_blank">
//                                 <i class="fas fa-download"></i> Télécharger le document
//                             </a>
//                         </p>
//                     `;
//                 }
//             })
//             .catch(error => {
//                 console.error("Erreur lors de la conversion du document:", error);
//                 container.innerHTML = `
//                     <div class="alert alert-danger">
//                         <i class="fas fa-exclamation-circle"></i> 
//                         Une erreur s'est produite lors de la conversion du document.
//                     </div>
//                     <p class="text-center">
//                         <a href="${file.serverPath}" class="btn btn-primary" target="_blank">
//                             <i class="fas fa-download"></i> Télécharger le document
//                         </a>
//                     </p>
//                 `;
//             });
//     }
    
//     // Prévisualisation des fichiers Excel
//     function previewExcel(file, container) {
//         container.innerHTML = `
//             <div class="alert alert-info">
//                 <i class="fas fa-info-circle"></i> 
//                 La prévisualisation des fichiers Excel n'est pas disponible directement.
//             </div>
//             <p class="text-center">
//                 <a href="${file.serverPath}" class="btn btn-primary" target="_blank">
//                     <i class="fas fa-download"></i> Télécharger le fichier Excel
//                 </a>
//             </p>
//         `;
//     }
    
//     // Prévisualisation PowerPoint
//     function previewPowerPoint(file, container) {
//         container.innerHTML = `
//             <div class="alert alert-info">
//                 <i class="fas fa-info-circle"></i> 
//                 La prévisualisation des fichiers PowerPoint n'est pas disponible directement.
//             </div>
//             <p class="text-center">
//                 <a href="${file.serverPath}" class="btn btn-primary" target="_blank">
//                     <i class="fas fa-download"></i> Télécharger la présentation
//                 </a>
//             </p>
//         `;
//     }
    
//     // Prévisualisation des images
//     function previewImage(file, container) {
//         container.innerHTML = `
//             <div class="text-center">
//                 <img src="${file.serverPath}" class="img-fluid" alt="${file.originalName || file.name}" style="max-height: 70vh;">
//             </div>
//         `;
//     }
    
//     // Prévisualisation des vidéos
//     function previewVideo(file, container) {
//         container.innerHTML = `
//             <div class="text-center">
//                 <video class="file-media" controls>
//                     <source src="${file.serverPath}" type="${file.type}">
//                     Votre navigateur ne supporte pas la lecture de vidéos.
//                 </video>
//             </div>
//         `;
//     }
    
//     // Prévisualisation audio
//     function previewAudio(file, container) {
//         container.innerHTML = `
//             <div class="text-center">
//                 <audio class="w-100" controls>
//                     <source src="${file.serverPath}" type="${file.type}">
//                     Votre navigateur ne supporte pas la lecture audio.
//                 </audio>
//                 <div class="mt-3">
//                     <i class="fas fa-file-audio audio-icon" style="font-size: 5rem;"></i>
//                     <p class="mt-3">${file.originalName || file.name}</p>
//                 </div>
//             </div>
//         `;
//     }
    
//     // Prévisualisation des fichiers texte
//     function previewText(file, container) {
//         fetch(file.serverPath)
//             .then(response => response.text())
//             .then(text => {
//                 container.innerHTML = `
//                     <div class="file-content-header">
//                         <i class="fas fa-file-alt text-icon" style="font-size: 1.5rem; margin-right: 10px;"></i>
//                         <h6 class="mb-0">${file.originalName || file.name}</h6>
//                     </div>
//                     <pre class="text-content">${escapeHTML(text)}</pre>
//                 `;
//             })
//             .catch(error => {
//                 console.error("Erreur lors de la lecture du fichier texte:", error);
//                 container.innerHTML = `
//                     <div class="alert alert-danger">
//                         <i class="fas fa-exclamation-circle"></i> 
//                         Une erreur s'est produite lors de la lecture du fichier texte.
//                     </div>
//                 `;
//             });
//     }
    
//     // Prévisualisation HTML
//     function previewHTML(file, container) {
//         fetch(file.serverPath)
//             .then(response => response.text())
//             .then(html => {
//                 container.innerHTML = `
//                     <div class="file-content-header">
//                         <i class="fas fa-file-code text-icon" style="font-size: 1.5rem; margin-right: 10px;"></i>
//                         <h6 class="mb-0">${file.originalName || file.name}</h6>
//                     </div>
//                     <div class="mb-3">
//                         <ul class="nav nav-tabs" role="tablist">
//                             <li class="nav-item" role="presentation">
//                                 <button class="nav-link active" id="rendered-tab" data-bs-toggle="tab" 
//                                         data-bs-target="#rendered" type="button" role="tab">Rendu</button>
//                             </li>
//                             <li class="nav-item" role="presentation">
//                                 <button class="nav-link" id="source-tab" data-bs-toggle="tab" 
//                                         data-bs-target="#source" type="button" role="tab">Source</button>
//                             </li>
//                         </ul>
//                         <div class="tab-content">
//                             <div class="tab-pane fade show active" id="rendered" role="tabpanel">
//                                 <iframe srcdoc="${escapeHTML(html)}" class="file-iframe" sandbox="allow-same-origin"></iframe>
//                             </div>
//                             <div class="tab-pane fade" id="source" role="tabpanel">
//                                 <pre class="text-content">${escapeHTML(html)}</pre>
//                             </div>
//                         </div>
//                     </div>
//                 `;
//             })
//             .catch(error => {
//                 console.error("Erreur lors de la lecture du fichier HTML:", error);
//                 container.innerHTML = `
//                     <div class="alert alert-danger">
//                         <i class="fas fa-exclamation-circle"></i> 
//                         Une erreur s'est produite lors de la lecture du fichier HTML.
//                     </div>
//                 `;
//             });
//     }
    
//     // Prévisualisation ZIP
//     function previewZip(file, container) {
//         // On vérifie si on a déjà extrait le contenu de ce ZIP
//         if (zipContentCache[file.serverFileName]) {
//             renderZipExplorer(zipContentCache[file.serverFileName], container, file);
//             return;
//         }
        
//         // Simuler une requête pour obtenir le contenu du ZIP
//         fetch(`/api/extract-zip?path=${encodeURIComponent(file.serverPath)}`)
//             .then(response => response.json())
//             .then(data => {
//                 if (data.success) {
//                     // Stocker les données dans le cache
//                     zipContentCache[file.serverFileName] = data.files;
                    
//                     // Afficher l'explorateur ZIP
//                     renderZipExplorer(data.files, container, file);
//                 } else {
//                     container.innerHTML = `
//                         <div class="alert alert-warning">
//                             <i class="fas fa-exclamation-triangle"></i> 
//                             L'extraction du fichier ZIP a échoué.
//                         </div>
//                         <p class="text-center">
//                             <a href="${file.serverPath}" class="btn btn-primary" target="_blank">
//                                 <i class="fas fa-download"></i> Télécharger l'archive
//                             </a>
//                         </p>
//                     `;
//                 }
//             })
//             .catch(error => {
//                 console.error("Erreur lors de l'extraction du ZIP:", error);
//                 container.innerHTML = `
//                     <div class="alert alert-danger">
//                         <i class="fas fa-exclamation-circle"></i> 
//                         Une erreur s'est produite lors de l'extraction de l'archive.
//                     </div>
//                     <p class="text-center">
//                         <a href="${file.serverPath}" class="btn btn-primary" target="_blank">
//                             <i class="fas fa-download"></i> Télécharger l'archive
//                         </a>
//                     </p>
//                 `;
//             });
//     }
    
//     // Rendre l'explorateur ZIP
//     function renderZipExplorer(files, container, zipFile) {
//         // Réinitialiser le chemin actuel si nécessaire
//         if (currentZipPath.length === 0) {
//             currentZipPath = ['/']; // On commence à la racine
//         }
        
//         const currentPath = currentZipPath.join('/').replace(/\/\//g, '/');
        
//         // Construire l'arborescence du répertoire actuel
//         const currentFiles = getFilesInPath(files, currentPath);
//         const sortedFiles = sortFilesByTypeAndName(currentFiles);
        
//         // Construire le HTML de navigation
//         let html = `
//             <div class="file-content-header">
//                 <i class="fas fa-file-archive archive-icon" style="font-size: 1.5rem; margin-right: 10px;"></i>
//                 <h6 class="mb-0">${zipFile.originalName || zipFile.name}</h6>
//             </div>
//             <div class="zip-explorer">
//                 <nav aria-label="breadcrumb">
//                     <ol class="breadcrumb">
//         `;
        
//         // Fil d'Ariane pour la navigation
//         html += `<li class="breadcrumb-item"><a href="#" class="zip-nav-link" data-path="/">Racine</a></li>`;
//         let breadcrumbPath = '/';
        
//         for (let i = 1; i < currentZipPath.length; i++) {
//             const segment = currentZipPath[i];
//             if (segment) {
//                 breadcrumbPath += segment + '/';
//                 html += `
//                     <li class="breadcrumb-item">
//                         <a href="#" class="zip-nav-link" data-path="${breadcrumbPath}">${segment}</a>
//                     </li>
//                 `;
//             }
//         }
        
//         html += `
//                     </ol>
//                 </nav>
//                 <div class="list-group">
//         `;
        
//         // Bouton pour remonter d'un niveau si on n'est pas à la racine
//         if (currentZipPath.length > 1) {
//             html += `
//                 <a href="#" class="list-group-item list-group-item-action zip-nav-link" data-path="${getParentPath(currentPath)}">
//                     <i class="fas fa-arrow-up"></i> Remonter
//                 </a>
//             `;
//         }
        
//         // Liste des dossiers
//         sortedFiles.folders.forEach(folder => {
//             html += `
//                 <a href="#" class="list-group-item list-group-item-action zip-nav-link" data-path="${currentPath}${folder}/">
//                     <i class="fas fa-folder text-warning"></i> ${folder}
//                 </a>
//             `;
//         });
        
//         // Liste des fichiers
//         sortedFiles.files.forEach(file => {
//             const fileType = getFileTypeFromName(file);
//             const icon = getFileIconByType(fileType);
//             const isPreviewable = ['text', 'image', 'pdf', 'html'].includes(fileType);
//             const action = isPreviewable ? 'preview' : 'download';
            
//             html += `
//                 <a href="#" class="list-group-item list-group-item-action zip-file-link" 
//                    data-path="${currentPath}${file}" data-action="${action}" data-type="${fileType}" data-zip-file="${zipFile.serverFileName}">
//                     <i class="${icon}"></i> ${file}
//                 </a>
//             `;
//         });
        
//         html += `
//                 </div>
//             </div>
//         `;
        
//         container.innerHTML = html;
        
//         // Ajouter les écouteurs d'événements
//         container.querySelectorAll('.zip-nav-link').forEach(link => {
//             link.addEventListener('click', function(e) {
//                 e.preventDefault();
//                 const path = this.getAttribute('data-path');
//                 navigateZipFolder(path, container, zipFile);
//             });
//         });
        
//         container.querySelectorAll('.zip-file-link').forEach(link => {
//             link.addEventListener('click', function(e) {
//                 e.preventDefault();
//                 const path = this.getAttribute('data-path');
//                 const action = this.getAttribute('data-action');
//                 const fileType = this.getAttribute('data-type');
//                 const zipFileName = this.getAttribute('data-zip-file');
                
//                 if (action === 'preview') {
//                     previewZipFile(path, fileType, zipFileName, container, zipFile);
//                 } else {
//                     // Télécharger le fichier (simulé)
//                     alert(`Téléchargement du fichier: ${path}`);
//                 }
//             });
//         });
//     }
    
//     // Navigation dans un dossier ZIP
//     function navigateZipFolder(path, container, zipFile) {
//         // Mettre à jour le chemin actuel
//         currentZipPath = path.split('/').filter(segment => segment);
//         currentZipPath.unshift('/');
        
//         // Rendre à nouveau l'explorateur
//         renderZipExplorer(zipContentCache[zipFile.serverFileName], container, zipFile);
//     }
    
//     // Prévisualiser un fichier dans le ZIP
//     function previewZipFile(path, fileType, zipFileName, container, zipFile) {
//         // Simuler une requête pour obtenir le contenu du fichier ZIP
//         fetch(`/api/get-zip-file?zipPath=${encodeURIComponent(zipFile.serverPath)}&filePath=${encodeURIComponent(path)}`)
//             .then(response => response.text())
//             .then(content => {
//                 const fileName = path.split('/').pop();
                
//                 let previewHTML = `
//                     <div class="file-content-header">
//                         <button class="back-button" id="backToZipExplorer">
//                             <i class="fas fa-arrow-left"></i>
//                         </button>
//                         <i class="${getFileIconByType(fileType)}" style="margin-right: 10px;"></i>
//                         <h6 class="mb-0">${fileName}</h6>
//                     </div>
//                 `;
                
//                 switch (fileType) {
//                     case 'text':
//                         previewHTML += `<pre class="text-content">${escapeHTML(content)}</pre>`;
//                         break;
//                     case 'image':
//                         previewHTML += `
//                             <div class="text-center">
//                                 <img src="data:image/jpeg;base64,${content}" class="img-fluid" alt="${fileName}">
//                             </div>
//                         `;
//                         break;
//                     case 'pdf':
//                         previewHTML += `
//                             <iframe src="data:application/pdf;base64,${content}" class="file-iframe"></iframe>
//                         `;
//                         break;
//                     case 'html':
//                         previewHTML += `
//                             <div class="mb-3">
//                                 <ul class="nav nav-tabs" role="tablist">
//                                     <li class="nav-item" role="presentation">
//                                         <button class="nav-link active" id="rendered-tab" data-bs-toggle="tab" 
//                                                 data-bs-target="#rendered" type="button" role="tab">Rendu</button>
//                                     </li>
//                                     <li class="nav-item" role="presentation">
//                                         <button class="nav-link" id="source-tab" data-bs-toggle="tab" 
//                                                 data-bs-target="#source" type="button" role="tab">Source</button>
//                                     </li>
//                                 </ul>
//                                 <div class="tab-content">
//                                     <div class="tab-pane fade show active" id="rendered" role="tabpanel">
//                                         <iframe srcdoc="${escapeHTML(content)}" class="file-iframe" sandbox="allow-same-origin"></iframe>
//                                     </div>
//                                     <div class="tab-pane fade" id="source" role="tabpanel">
//                                         <pre class="text-content">${escapeHTML(content)}</pre>
//                                     </div>
//                                 </div>
//                             </div>
//                         `;
//                         break;
//                     default:
//                         previewHTML += `
//                             <div class="alert alert-info">
//                                 <i class="fas fa-info-circle"></i> 
//                                 La prévisualisation n'est pas disponible pour ce type de fichier.
//                             </div>
//                         `;
//                 }
                
//                 container.innerHTML = previewHTML;
                
//                 // Ajouter un écouteur pour le bouton de retour
//                 document.getElementById('backToZipExplorer').addEventListener('click', function() {
//                     renderZipExplorer(zipContentCache[zipFileName], container, zipFile);
//                 });
//             })
//             .catch(error => {
//                 console.error("Erreur lors de la prévisualisation du fichier ZIP:", error);
//                 container.innerHTML = `
//                     <div class="alert alert-danger">
//                         <i class="fas fa-exclamation-circle"></i> 
//                         Une erreur s'est produite lors de la prévisualisation du fichier<div class="alert alert-danger">
//                         <i class="fas fa-exclamation-circle"></i> 
//                         Une erreur s'est produite lors de la prévisualisation du fichier.
//                     </div>
//                     <div class="text-center mt-3">
//                         <button class="btn btn-primary" id="backToZipExplorer">
//                             <i class="fas fa-arrow-left"></i> Retour à l'explorateur
//                         </button>
//                     </div>
//                 `;
                
//                 // Ajouter un écouteur pour le bouton de retour
//                 document.getElementById('backToZipExplorer').addEventListener('click', function() {
//                     renderZipExplorer(zipContentCache[zipFileName], container, zipFile);
//                 });
//             });
//     }
    
//     // Obtenir les fichiers dans un chemin spécifique
//     function getFilesInPath(files, path) {
//         const result = {
//             folders: new Set(),
//             files: []
//         };
        
//         // Normaliser le chemin
//         if (!path.endsWith('/')) {
//             path += '/';
//         }
        
//         for (const filePath of files) {
//             // Vérifier si le fichier est dans le chemin actuel
//             if (filePath.startsWith(path) && filePath !== path) {
//                 // Obtenir le chemin relatif à partir du chemin actuel
//                 const relativePath = filePath.substring(path.length);
                
//                 // Si le chemin relatif contient un '/', c'est un dossier
//                 if (relativePath.includes('/')) {
//                     // Ajouter seulement le premier segment comme dossier
//                     const folderName = relativePath.split('/')[0];
//                     if (folderName) {
//                         result.folders.add(folderName);
//                     }
//                 } else {
//                     // C'est un fichier dans le dossier actuel
//                     result.files.push(relativePath);
//                 }
//             }
//         }
        
//         return result;
//     }
    
//     // Trier les fichiers par type puis par nom
//     function sortFilesByTypeAndName(filesObj) {
//         return {
//             folders: Array.from(filesObj.folders).sort(),
//             files: filesObj.files.sort()
//         };
//     }
    
//     // Obtenir le chemin parent
//     function getParentPath(path) {
//         // Supprimer le dernier '/' s'il existe
//         if (path.endsWith('/')) {
//             path = path.slice(0, -1);
//         }
        
//         // Trouver le dernier '/'
//         const lastSlashIndex = path.lastIndexOf('/');
        
//         // Si pas de '/', retourner la racine
//         if (lastSlashIndex === -1) {
//             return '/';
//         }
        
//         // Retourner le chemin jusqu'au dernier '/'
//         return path.substring(0, lastSlashIndex + 1);
//     }
    
//     // Obtenir le type de fichier à partir du nom
//     function getFileTypeFromName(fileName) {
//         const extension = fileName.split('.').pop().toLowerCase();
        
//         switch (extension) {
//             case 'pdf': return 'pdf';
//             case 'doc': case 'docx': return 'word';
//             case 'xls': case 'xlsx': return 'excel';
//             case 'ppt': case 'pptx': return 'powerpoint';
//             case 'jpg': case 'jpeg': case 'png': case 'gif': return 'image';
//             case 'mp4': case 'webm': case 'avi': return 'video';
//             case 'mp3': case 'wav': return 'audio';
//             case 'zip': case 'rar': return 'zip';
//             case 'txt': return 'text';
//             case 'html': case 'htm': return 'html';
//             default: return 'default';
//         }
//     }
    
//     // Obtenir l'icône pour un type de fichier
//     function getFileIconByType(fileType) {
//         switch (fileType) {
//             case 'pdf': return 'fas fa-file-pdf pdf-icon';
//             case 'word': return 'fas fa-file-word word-icon';
//             case 'excel': return 'fas fa-file-excel excel-icon';
//             case 'powerpoint': return 'fas fa-file-powerpoint powerpoint-icon';
//             case 'image': return 'fas fa-file-image image-icon';
//             case 'video': return 'fas fa-file-video video-icon';
//             case 'audio': return 'fas fa-file-audio audio-icon';
//             case 'zip': return 'fas fa-file-archive archive-icon';
//             case 'text': return 'fas fa-file-alt text-icon';
//             case 'html': return 'fas fa-file-code text-icon';
//             default: return 'fas fa-file default-icon';
//         }
//     }
    
//     // Échapper les caractères HTML pour éviter les injections XSS
//     function escapeHTML(str) {
//         return str
//             .replace(/&/g, '&amp;')
//             .replace(/</g, '&lt;')
//             .replace(/>/g, '&gt;')
//             .replace(/"/g, '&quot;')
//             .replace(/'/g, '&#039;');
//     }
    
//     // Gérer la fermeture de la modal
//     document.querySelector('.file-preview-close').addEventListener('click', function() {
//         document.getElementById('filePreviewModal').style.display = 'none';
//     });
    
//     // Fermer la modal si on clique en dehors du contenu
//     window.addEventListener('click', function(event) {
//         const modal = document.getElementById('filePreviewModal');
//         if (event.target === modal) {
//             modal.style.display = 'none';
//         }
//     });
    
//     // Validation du formulaire
//     (function () {
//         'use strict'
    
//         // Rechercher tous les formulaires qui nécessitent une validation
//         const forms = document.querySelectorAll('.needs-validation')
    
//         // Boucler sur eux et empêcher la soumission
//         Array.from(forms).forEach(form => {
//             form.addEventListener('submit', event => {
//                 // Vérifier s'il y a des fichiers téléchargés
//                 const uploadedFilesInput = document.getElementById('uploaded_files');
//                 const uploadedFilesValue = uploadedFilesInput.value;
//                 const hasUploadedFiles = uploadedFilesValue && JSON.parse(uploadedFilesValue).length > 0;
                
//                 if (!hasUploadedFiles) {
//                     // S'il n'y a pas de fichiers téléchargés, ajouter une classe d'erreur à la dropzone
//                     document.getElementById('multipleFilesUpload').classList.add('dropzone-error');
                    
//                     // Afficher un message d'erreur
//                     let errorMsg = document.querySelector('#multipleFilesUpload + .invalid-feedback');
//                     if (!errorMsg) {
//                         errorMsg = document.createElement('div');
//                         errorMsg.classList.add('invalid-feedback');
//                         errorMsg.textContent = 'Veuillez télécharger au moins un fichier.';
//                         errorMsg.style.display = 'block';
//                         document.getElementById('multipleFilesUpload').parentNode.appendChild(errorMsg);
//                     }
                    
//                     event.preventDefault();
//                     event.stopPropagation();
//                 } else {
//                     // S'il y a des fichiers, supprimer la classe d'erreur
//                     document.getElementById('multipleFilesUpload').classList.remove('dropzone-error');
                    
//                     // Supprimer le message d'erreur s'il existe
//                     const errorMsg = document.querySelector('#multipleFilesUpload + .invalid-feedback');
//                     if (errorMsg) {
//                         errorMsg.remove();
//                     }
//                 }
                
//                 if (!form.checkValidity()) {
//                     event.preventDefault();
//                     event.stopPropagation();
//                 }
                
//                 form.classList.add('was-validated');
//             }, false);
//         });
//     })();
    
//     // Ajouter une classe d'erreur personnalisée pour Dropzone
//     document.head.insertAdjacentHTML('beforeend', `
//         <style>
//             .dropzone-error .dz-message {
//                 border-color: #dc3545 !important;
//             }
            
//             .dropzone-error .dz-message .icon-cloud-up {
//                 color: #dc3545 !important;
//             }
//         </style>
//     `);
// });