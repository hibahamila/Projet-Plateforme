document.addEventListener('DOMContentLoaded', function () {
    console.log("Document ready");

    // Vérifiez que les éléments existent
    const uploadRoute = document.getElementById('uploadRoute');
    const deleteRoute = document.getElementById('deleteRoute');
    const csrfToken = document.querySelector('meta[name="csrf-token"]');

    if (!uploadRoute || !deleteRoute || !csrfToken) {
        console.error("Required elements not found");
        return;
    }

    console.log("Upload route:", uploadRoute.value);
    console.log("Delete route:", deleteRoute.value);
    console.log("CSRF token:", csrfToken.content);

    // Variables
    var uploadedFiles = [];
    let treeHtml = ''; // Variable pour stocker l'arborescence du fichier ZIP

    // Définir les couleurs pour chaque type de fichier
    const fileColors = {
        pdf: '#f40f02',        // Rouge
        word: '#295092',       // Bleu
        excel: '#1d6f42',      // Vert
        powerpoint: '#d24625', // Orange
        image: '#ffb300',      // Or
        video: '#7c2d9b',      // Violet
        audio: '#f4783d',      // Orange-rouge
        archive: '#a05c41',    // Marron
        text: '#333333',       // Noir pour les fichiers texte
        default: '#666666'     // Gris
    };
    

    // Fonction pour lire le contenu d'un fichier texte
    function readTextFile(file, callback) {
        const reader = new FileReader();
        reader.onload = function (event) {
            callback(event.target.result);
        };
        reader.onerror = function (error) {
            console.error("Erreur lors de la lecture du fichier texte :", error);
            callback(null, error);
        };
        reader.readAsText(file);
    }

    // Fonction pour convertir un fichier Word en HTML avec Mammoth.js
    function convertWordToHtml(file, callback) {
        const reader = new FileReader();
        reader.onload = function (event) {
            const arrayBuffer = event.target.result;
            mammoth.convertToHtml({ arrayBuffer: arrayBuffer })
                .then(function (result) {
                    callback(result.value); // Renvoie le HTML généré
                })
                .catch(function (error) {
                    console.error("Erreur de conversion Word :", error);
                    callback(null, error);
                });
        };
        reader.readAsArrayBuffer(file);
    }

    // Fonction pour convertir un fichier Excel en HTML avec SheetJS
    function convertExcelToHtml(file, callback) {
        const reader = new FileReader();
        reader.onload = function (event) {
            const arrayBuffer = event.target.result;
            const workbook = XLSX.read(arrayBuffer, { type: 'array' });
            let htmlContent = '';

            // Parcourir chaque feuille du fichier Excel
            workbook.SheetNames.forEach(sheetName => {
                const worksheet = workbook.Sheets[sheetName];
                htmlContent += `<h4>${sheetName}</h4>`;
                htmlContent += XLSX.utils.sheet_to_html(worksheet);
            });

            callback(htmlContent);
        };
        reader.onerror = function (error) {
            console.error("Erreur lors de la lecture du fichier Excel :", error);
            callback(null, error);
        };
        reader.readAsArrayBuffer(file);
    }

    // Fonction pour convertir un fichier PPTX en HTML avec Pptx2Html
    function convertPptxToHtml(file, callback) {
        const reader = new FileReader();
        reader.onload = function (event) {
            const arrayBuffer = event.target.result;

            // Convertir le ArrayBuffer en Blob
            const blob = new Blob([arrayBuffer], { type: file.type });

            // Utiliser Pptx2Html pour convertir le fichier PPTX en HTML
            pptx2html(blob)
                .then(htmlContent => {
                    callback(htmlContent);
                })
                .catch(error => {
                    console.error("Erreur lors de la conversion du fichier PPTX :", error);
                    callback(null, error);
                });
        };
        reader.onerror = function (error) {
            console.error("Erreur lors de la lecture du fichier PPTX :", error);
            callback(null, error);
        };
        reader.readAsArrayBuffer(file);
    }

    // Fonction pour afficher le contenu d'un fichier texte avec un bouton de retour
    function displayTextFileContent(content, filePath, filePreviewContent, zip, zipFileName) {
        filePreviewContent.innerHTML = `
            <div class="text-center p-5">
                <div class="mb-4" style="position: relative;">
                    <!-- Bouton de retour avec icône à gauche -->
                    <button id="backButton" style="position: absolute; left: 0; top: 0; background: none; border: none; cursor: pointer;">
                        <i class="fas fa-arrow-left" style="font-size: 24px; color: #666666;"></i>
                    </button>
                    <i class="fas fa-file-alt fa-5x" style="color: ${fileColors.text};"></i>
                    <h4 class="mt-3">${filePath}</h4>
                </div>
                <div class="text-preview" style="text-align: left; white-space: pre-wrap; background-color: #f8f9fa; padding: 15px; border-radius: 5px;">
                    ${content}
                </div>
            </div>`;

        // Ajouter un écouteur d'événements pour le bouton de retour
        const backButton = document.getElementById('backButton');
        if (backButton) {
            backButton.addEventListener('click', function () {
                // Restaurer l'affichage de l'iframe principale avec le nom du fichier ZIP
                filePreviewContent.innerHTML = `
                    <div class="text-center p-5">
                        <div class="mb-4">
                            <i class="fas fa-file-archive fa-5x" style="color: ${fileColors.archive};"></i>
                            <h4 class="mt-3">${zipFileName}</h4> <!-- Afficher le nom du fichier ZIP -->
                            <p class="text-muted">Archive ZIP</p>
                        </div>
                        <div class="zip-content">
                            <div class="file-explorer">
                                ${treeHtml} <!-- Réafficher l'arborescence du ZIP -->
                            </div>
                        </div>
                    </div>`;

                // Réappliquer les écouteurs d'événements pour les dossiers et fichiers
                document.querySelectorAll('.folder-header').forEach(header => {
                    header.addEventListener('click', function () {
                        const content = this.nextElementSibling;
                        const icon = this.querySelector('.fas.fa-chevron-down, .fas.fa-chevron-right');
                        if (content.style.display === 'none') {
                            content.style.display = 'block';
                            if (icon) {
                                icon.classList.remove('fa-chevron-right');
                                icon.classList.add('fa-chevron-down');
                            }
                        } else {
                            content.style.display = 'none';
                            if (icon) {
                                icon.classList.remove('fa-chevron-down');
                                icon.classList.add('fa-chevron-right');
                            }
                        }
                    });
                });

                document.querySelectorAll('.file-item').forEach(fileItem => {
                    fileItem.addEventListener('click', function () {
                        const filePath = this.getAttribute('data-path');
                        const file = zip.file(filePath);
                        if (file) {
                            file.async('text').then(content => {
                                displayTextFileContent(content, filePath, filePreviewContent, zip, zipFileName);
                            });
                        }
                    });
                });
            });
        }
    }

    // Fonction améliorée pour afficher le contenu ZIP de manière hiérarchique
    function displayZipContent(zip, fileColor, fileIcon, fileName, fileTypeText) {
        console.log("Fichier ZIP chargé avec succès", zip);

        // Structure de données hiérarchique pour organiser les fichiers
        const fileTree = {};

        // Parcourir tous les fichiers et créer l'arborescence
        zip.forEach((relativePath, file) => {
            if (!file.dir) { // Ignorer les dossiers
                const segments = relativePath.split('/');

                // Ignorer les fichiers cachés (commençant par '.')
                if (segments[0].startsWith('.')) return;

                // Construire l'arborescence
                let currentLevel = fileTree;

                // Parcourir les segments sauf le dernier (qui est le nom du fichier)
                for (let i = 0; i < segments.length - 1; i++) {
                    const folder = segments[i];
                    if (!folder) continue; // Ignorer les segments vides

                    if (!currentLevel[folder]) {
                        currentLevel[folder] = { _files: [] };
                    }
                    currentLevel = currentLevel[folder];
                }

                // Ajouter le fichier au niveau actuel
                const fileName = segments[segments.length - 1];
                if (fileName) {
                    currentLevel._files.push(fileName);
                }
            }
        });

        // Fonction pour générer le HTML de l'arborescence
        function generateTreeHtml(tree, path = '') {
            let html = '<ul class="folder-tree" style="list-style-type: none; padding-left: 15px;">';

            // Traiter d'abord les dossiers
            Object.keys(tree).sort().forEach(key => {
                if (key !== '_files') {
                    const folderPath = path ? `${path}/${key}` : key;
                    html += `
                        <li class="folder-item" data-path="${folderPath}">
                            <div class="folder-header" style="cursor: pointer; padding: 5px 0;">
                                <i class="fas fa-folder" style="color: #ffc107; margin-right: 5px;"></i>
                                <span>${key}</span>
                                <i class="fas fa-chevron-down" style="margin-left: 5px; font-size: 0.8em;"></i>
                            </div>
                            <div class="folder-content" style="display: none;">
                                ${generateTreeHtml(tree[key], folderPath)}
                            </div>
                        </li>
                    `;
                }
            });

            // Puis les fichiers
            if (tree._files && tree._files.length > 0) {
                tree._files.sort().forEach(file => {
                    const filePath = path ? `${path}/${file}` : file;
                    const fileExtension = file.split('.').pop().toLowerCase();
                    const isTextFile = ['txt', 'log', 'csv', 'json', 'xml'].includes(fileExtension);

                    html += `
                        <li class="file-item" style="padding: 5px 0;" data-path="${filePath}">
                            <i class="fas fa-file${isTextFile ? '-alt' : ''}" style="color: ${isTextFile ? fileColors.text : '#6c757d'}; margin-right: 5px;"></i>
                            <span>${file}</span>
                        </li>
                    `;
                });
            }

            html += '</ul>';
            return html;
        }

        // Générer le HTML final
        treeHtml = generateTreeHtml(fileTree);

        // Construire le contenu complet à afficher
        const contentHtml = `
            <div class="text-center p-5">
                <div class="mb-4">
                    <i class="${fileIcon} fa-5x" style="color: ${fileColor};"></i>
                    <h4 class="mt-3">${fileName}</h4>
                    <p class="text-muted">${fileTypeText}</p>
                </div>
                <div class="zip-content">
                    <div class="file-explorer">
                        ${Object.keys(fileTree).length > 0 ? treeHtml : '<div class="alert alert-warning">Aucun contenu trouvé dans ce fichier ZIP.</div>'}
                    </div>
                </div>
            </div>`;

        return contentHtml;
    }

    // Initialisation de Dropzone
    Dropzone.autoDiscover = false;
    const myDropzone = new Dropzone("#multipleFilesUpload", {
        autoProcessQueue: true, // Upload automatique
        uploadMultiple: false, // Upload un fichier à la fois

        url: uploadRoute.value,
        paramName: "file",
        maxFilesize: 10, // Taille maximale en MB
        maxFiles: null, // Nombre maximal de fichiers
        acceptedFiles: ".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.mp4,.mp3,.zip,.jpg,.jpeg,.png,.txt,.log,.csv,.json,.xml",
        addRemoveLinks: false,
        headers: {
            'X-CSRF-TOKEN': csrfToken.content
        },
        init: function () {
            console.log("Dropzone initialized");
            const dzInstance = this;

            // Événement lors de l'ajout d'un fichier
            this.on("addedfile", function (file) {
                console.log("File added:", file.name);

                // Ajouter un bouton de suppression personnalisé
                const removeButton = Dropzone.createElement('<i class="fas fa-times-circle custom-remove" title="Supprimer"></i>');
                file.previewElement.appendChild(removeButton);
                removeButton.addEventListener("click", function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dzInstance.removeFile(file);
                });

                // Agrandir le cadre du fichier uploadé
                file.previewElement.classList.add('dz-file-preview-large');

                // Ajouter un écouteur d'événement pour le clic sur le fichier
                file.previewElement.addEventListener("click", function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Afficher un loader pendant le chargement
                    const filePreviewContainer = document.getElementById('filePreviewContainer');
                    const filePreviewContent = document.getElementById('filePreviewContent');
                    filePreviewContent.innerHTML = '<div class="text-center p-5"><div class="spinner-border" role="status"><span class="visually-hidden">Chargement...</span></div><p class="mt-3">Chargement du fichier en cours...</p></div>';
                    filePreviewContainer.style.display = 'block';

                    const fileName = file.name.toLowerCase();

                    // Déterminer l'icône et la couleur en fonction du type de fichier
                    let fileIcon = 'fas fa-file'; // Icône par défaut
                    let fileTypeText = 'Fichier';
                    let fileColor = fileColors.default;

                    if (fileName.endsWith('.pdf')) {
                        fileIcon = 'fas fa-file-pdf';
                        fileTypeText = 'Document PDF';
                        fileColor = fileColors.pdf;
                    } else if (fileName.endsWith('.doc') || fileName.endsWith('.docx')) {
                        fileIcon = 'fas fa-file-word';
                        fileTypeText = 'Document Word';
                        fileColor = fileColors.word;
                    } else if (fileName.endsWith('.xls') || fileName.endsWith('.xlsx')) {
                        fileIcon = 'fas fa-file-excel';
                        fileTypeText = 'Feuille de calcul Excel';
                        fileColor = fileColors.excel;
                    } else if (fileName.endsWith('.ppt') || fileName.endsWith('.pptx')) {
                        fileIcon = 'fas fa-file-powerpoint';
                        fileTypeText = 'Présentation PowerPoint';
                        fileColor = fileColors.powerpoint;
                    } else if (file.type.startsWith('image/')) {
                        fileIcon = 'fas fa-file-image';
                        fileTypeText = 'Image';
                        fileColor = fileColors.image;
                    } else if (file.type.includes('video/')) {
                        fileIcon = 'fas fa-file-video';
                        fileTypeText = 'Vidéo';
                        fileColor = fileColors.video;
                    } else if (file.type.includes('audio/')) {
                        fileIcon = 'fas fa-file-audio';
                        fileTypeText = 'Audio';
                        fileColor = fileColors.audio;
                    } else if (fileName.endsWith('.zip')) {
                        fileIcon = 'fas fa-file-archive';
                        fileTypeText = 'Archive ZIP';
                        fileColor = fileColors.archive;

                        // Traitement spécial pour les fichiers ZIP
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            const arrayBuffer = event.target.result;

                            // Charger le fichier ZIP avec JSZip
                            JSZip.loadAsync(arrayBuffer)
                                .then(zip => {
                                    // Utiliser la nouvelle fonction pour afficher le contenu
                                    const zipContentHtml = displayZipContent(zip, fileColor, fileIcon, file.name, fileTypeText);
                                    filePreviewContent.innerHTML = zipContentHtml;

                                    // Ajouter l'écouteur d'événements pour développer/réduire les dossiers
                                    document.querySelectorAll('.folder-header').forEach(header => {
                                        header.addEventListener('click', function() {
                                            const content = this.nextElementSibling;
                                            const icon = this.querySelector('.fas.fa-chevron-down, .fas.fa-chevron-right');

                                            if (content.style.display === 'none') {
                                                content.style.display = 'block';
                                                if (icon) {
                                                    icon.classList.remove('fa-chevron-right');
                                                    icon.classList.add('fa-chevron-down');
                                                }
                                            } else {
                                                content.style.display = 'none';
                                                if (icon) {
                                                    icon.classList.remove('fa-chevron-down');
                                                    icon.classList.add('fa-chevron-right');
                                                }
                                            }
                                        });
                                    });

                                    // Ajouter l'écouteur d'événements pour les fichiers texte
                                    document.querySelectorAll('.file-item').forEach(fileItem => {
                                        fileItem.addEventListener('click', function() {
                                            const filePath = this.getAttribute('data-path');
                                            const file = zip.file(filePath);
                                            if (file) {
                                                file.async('text').then(content => {
                                                    displayTextFileContent(content, filePath, filePreviewContent, zip, fileName);
                                                });
                                            }
                                        });
                                    });
                                })
                                .catch(error => {
                                    console.error("Erreur lors de la lecture du fichier ZIP :", error);
                                    filePreviewContent.innerHTML = `
                                        <div class="alert alert-danger">
                                            <h4>Erreur lors de la lecture du fichier ZIP</h4>
                                            <p>${error.message || 'Impossible de lire le fichier ZIP'}</p>
                                        </div>`;
                                });
                        };
                        reader.onerror = function(error) {
                            console.error("Erreur lors de la lecture du fichier :", error);
                            filePreviewContent.innerHTML = `
                                <div class="alert alert-danger">
                                    <h4>Erreur lors de la lecture du fichier</h4>
                                    <p>${error.message || 'Impossible de lire le fichier'}</p>
                                </div>`;
                        };

                        reader.readAsArrayBuffer(file);
                        return; // On arrête le traitement ici pour les fichiers ZIP
                    }

                    // Pour les autres types de fichiers
                    if (file.filepath) {
                        // Le fichier a déjà été uploadé, on utilise fetch pour le récupérer
                        fetch(`/get-file?filepath=${encodeURIComponent(file.filepath)}`, {
                            method: "GET",
                            headers: {
                                'X-CSRF-TOKEN': csrfToken.content
                            }
                        })
                        .then(response => {
                            if (response.ok) {
                                // Détermine le type de contenu
                                const contentType = response.headers.get('content-type');
                                if (contentType && contentType.includes('application/json')) {
                                    // Si c'est une erreur JSON
                                    return response.json().then(data => {
                                        throw new Error(data.error || 'Error retrieving file');
                                    });
                                }
                                return response.blob();
                            } else {
                                throw new Error('File not found');
                            }
                        })
                        .then(blob => {
                            // Créer un URL pour le fichier
                            const fileURL = URL.createObjectURL(blob);

                            // Afficher le fichier dans le conteneur en fonction de son type
                            if (file.type.startsWith('image/')) {
                                // Images - Centrer l'image dans l'iframe avec fond sombre
                                filePreviewContent.innerHTML = `
                                    <div style="display: flex; justify-content: center; align-items: center; height: 100%; background-color: rgba(0, 0, 0, 0.8);">
                                        <img src="${fileURL}" class="img-fluid" alt="Aperçu de l'image" style="max-width: 100%; max-height: 100%;">
                                    </div>`;
                            } else if (file.type === 'application/pdf' || fileName.endsWith('.pdf')) {
                                // PDF - Afficher le PDF dans un iframe intégré
                                filePreviewContent.innerHTML = `
                                    <div class="pdf-preview-container" style="width: 100%; height: 80vh; overflow: auto; position: relative;">
                                        <div class="preview-header bg-light p-3" style="position: sticky; top: 0; z-index: 999;">
                                            <h5 class="mb-0">Aperçu du PDF : ${file.name}</h5>
                                        </div>
                                        <iframe src="${fileURL}" type="application/pdf" width="100%" height="100%" style="border: none;"></iframe>
                                    </div>`;
                            } else if (file.type.includes('video/')) {
                                // Vidéos - Lire la vidéo dans la même fenêtre
                                filePreviewContent.innerHTML = `
                                    <div class="text-center p-5">
                                        <div class="mb-4">
                                            <i class="${fileIcon} fa-5x" style="color: ${fileColor};"></i>
                                            <h4 class="mt-3">${file.name}</h4>
                                        </div>
                                        <div class="ratio ratio-16x9">
                                            <video controls width="100%"><source src="${fileURL}" type="${file.type}">
                                            Votre navigateur ne supporte pas les vidéos.</video>
                                        </div>
                                    </div>`;
                            } else if (fileName.endsWith('.doc') || fileName.endsWith('.docx')) {
                                // Fichiers Word - Conversion en HTML avec Mammoth.js
                                const wordFile = new File([blob], file.name, { type: file.type });
                                convertWordToHtml(wordFile, function (html, error) {
                                    if (error) {
                                        filePreviewContent.innerHTML = `
                                            <div class="alert alert-danger">
                                                <h4>Erreur lors de la conversion</h4>
                                                <p>${error.message || 'Impossible de convertir le fichier Word en HTML'}</p>
                                            </div>`;
                                    } else {
                                        filePreviewContent.innerHTML = `
                                            <div class="text-center p-5">
                                                <div class="mb-4">
                                                    <i class="${fileIcon} fa-5x" style="color: ${fileColor};"></i>
                                                    <h4 class="mt-3">${file.name}</h4>
                                                    <p class="text-muted">${fileTypeText}</p>
                                                </div>
                                                <div class="office-preview">
                                                    ${html}
                                                </div>
                                            </div>`;
                                    }
                                });
                            } else if (fileName.endsWith('.xls') || fileName.endsWith('.xlsx')) {
                                // Fichiers Excel - Conversion en HTML avec SheetJS
                                const excelFile = new File([blob], file.name, { type: file.type });
                                convertExcelToHtml(excelFile, function (html) {
                                    filePreviewContent.innerHTML = `
                                        <div class="text-center p-5">
                                            <div class="mb-4">
                                                <i class="${fileIcon} fa-5x" style="color: ${fileColor};"></i>
                                                <h4 class="mt-3">${file.name}</h4>
                                                <p class="text-muted">${fileTypeText}</p>
                                            </div>
                                            <div class="office-preview">
                                                ${html}
                                            </div>
                                        </div>`;
                                });
                            } else if (fileName.endsWith('.ppt') || fileName.endsWith('.pptx')) {
                                // Fichiers PowerPoint - Conversion en HTML avec Pptx2Html
                                const pptFile = new File([blob], file.name, { type: file.type });
                                convertPptxToHtml(pptFile, function (htmlContent, error) {
                                    if (error) {
                                        console.error("Erreur lors de la conversion en HTML :", error);
                                        filePreviewContent.innerHTML = `
                                            <div class="alert alert-danger">
                                                <h4>Erreur lors de la conversion</h4>
                                                <p>${error.message || 'Impossible de convertir le fichier PowerPoint en HTML'}</p>
                                                <p>Détails : ${error.toString()}</p>
                                            </div>`;
                                    } else {
                                        filePreviewContent.innerHTML = `
                                            <div class="text-center p-5">
                                                <div class="mb-4">
                                                    <i class="${fileIcon} fa-5x" style="color: ${fileColor};"></i>
                                                    <h4 class="mt-3">${file.name}</h4>
                                                    <p class="text-muted">${fileTypeText}</p>
                                                </div>
                                                <div class="office-preview">
                                                    ${htmlContent}
                                                </div>
                                            </div>`;
                                    }
                                });
                            } else if (fileName.endsWith('.txt') || fileName.endsWith('.log') || fileName.endsWith('.csv') || fileName.endsWith('.json') || fileName.endsWith('.xml')) {
                                // Fichiers texte - Afficher le contenu directement
                                const textFile = new File([blob], file.name, { type: file.type });
                                readTextFile(textFile, function (content, error) {
                                    if (error) {
                                        filePreviewContent.innerHTML = `
                                            <div class="alert alert-danger">
                                                <h4>Erreur lors de la lecture du fichier texte</h4>
                                                <p>${error.message || 'Impossible de lire le fichier texte'}</p>
                                            </div>`;
                                    } else {
                                        displayTextFileContent(content, file.name, filePreviewContent, zip, file.name);
                                    }
                                });
                            } else {
                                // Autres types de fichiers
                                filePreviewContent.innerHTML = `
                                    <div class="text-center p-5">
                                        <div class="mb-4">
                                            <i class="${fileIcon} fa-5x" style="color: ${fileColor};"></i>
                                            <h4 class="mt-3">${file.name}</h4>
                                            <p class="text-muted">${fileTypeText}</p>
                                        </div>
                                        <div class="alert alert-warning">
                                            <p>Ce type de fichier ne peut pas être prévisualisé directement dans le navigateur.</p>
                                            <p>Veuillez télécharger le fichier pour le consulter.</p>
                                        </div>
                                        <a href="${fileURL}" download="${file.name}" class="btn btn-success me-2">
                                            <i class="fas fa-download" style="color: white;"></i> Télécharger
                                        </a>
                                    </div>`;
                            }
                        })
                        .catch(error => {
                            console.error("Error fetching file:", error);
                            filePreviewContent.innerHTML = `
                                <div class="alert alert-danger">
                                    <h4>Erreur lors de la prévisualisation</h4>
                                    <p>${error.message || 'Impossible d\'ouvrir le fichier'}</p>
                                </div>`;
                        });
                    } else {
                        // Le fichier n'a pas encore été uploadé, prévisualiser directement
                        const fileURL = URL.createObjectURL(file);

                        // Traitement des différents types de fichiers
                        if (file.type.startsWith('image/')) {
                            filePreviewContent.innerHTML = `
                                <div style="display: flex; justify-content: center; align-items: center; height: 100%; background-color: rgba(0, 0, 0, 0.8);">
                                    <img src="${fileURL}" class="img-fluid" alt="Aperçu de l'image" style="max-width: 100%; max-height: 100%;">
                                </div>`;
                        } else if (file.type === 'application/pdf' || fileName.endsWith('.pdf')) {
                            filePreviewContent.innerHTML = `
                                <div class="pdf-preview-container" style="width: 100%; height: 80vh; overflow: auto; position: relative;">
                                    <div class="preview-header bg-light p-3" style="position: sticky; top: 0; z-index: 999;">
                                        <h5 class="mb-0">Aperçu du PDF : ${file.name}</h5>
                                    </div>
                                    <iframe src="${fileURL}" type="application/pdf" width="100%" height="100%" style="border: none;"></iframe>
                                </div>`;
                        } else if (file.type.includes('video/')) {
                            filePreviewContent.innerHTML = `
                                <div class="text-center p-5">
                                    <div class="mb-4">
                                        <i class="${fileIcon} fa-5x" style="color: ${fileColor};"></i>
                                        <h4 class="mt-3">${file.name}</h4>
                                    </div>
                                    <div class="ratio ratio-16x9">
                                        <video controls width="100%"><source src="${fileURL}" type="${file.type}">
                                        Votre navigateur ne supporte pas les vidéos.</video>
                                    </div>
                                </div>`;
                        } else if (fileName.endsWith('.doc') || fileName.endsWith('.docx')) {
                            convertWordToHtml(file, function(html, error) {
                                if (error) {
                                    filePreviewContent.innerHTML = `
                                        <div class="alert alert-danger">
                                            <h4>Erreur lors de la conversion</h4>
                                            <p>${error.message || 'Impossible de convertir le fichier Word en HTML'}</p>
                                        </div>`;
                                } else {
                                    filePreviewContent.innerHTML = `
                                        <div class="text-center p-5">
                                            <div class="mb-4">
                                                <i class="${fileIcon} fa-5x" style="color: ${fileColor};"></i>
                                                <h4 class="mt-3">${file.name}</h4>
                                                <p class="text-muted">${fileTypeText}</p>
                                            </div>
                                            <div class="office-preview">
                                                ${html}
                                            </div>
                                        </div>`;
                                }
                            });
                        } else if (fileName.endsWith('.xls') || fileName.endsWith('.xlsx')) {
                            convertExcelToHtml(file, function(html) {
                                filePreviewContent.innerHTML = `
                                    <div class="text-center p-5">
                                        <div class="mb-4">
                                            <i class="${fileIcon} fa-5x" style="color: ${fileColor};"></i>
                                            <h4 class="mt-3">${file.name}</h4>
                                            <p class="text-muted">${fileTypeText}</p>
                                        </div>
                                        <div class="office-preview">
                                            ${html}
                                        </div>
                                    </div>`;
                            });
                        } else if (fileName.endsWith('.ppt') || fileName.endsWith('.pptx')) {
                            convertPptxToHtml(file, function(htmlContent, error) {
                                if (error) {
                                    console.error("Erreur lors de la conversion en HTML :", error);
                                    filePreviewContent.innerHTML = `
                                        <div class="alert alert-danger">
                                            <h4>Erreur lors de la conversion</h4>
                                            <p>${error.message || 'Impossible de convertir le fichier PowerPoint en HTML'}</p>
                                            <p>Détails : ${error.toString()}</p>
                                        </div>`;
                                } else {
                                    filePreviewContent.innerHTML = `
                                        <div class="text-center p-5">
                                            <div class="mb-4">
                                                <i class="${fileIcon} fa-5x" style="color: ${fileColor};"></i>
                                                <h4 class="mt-3">${file.name}</h4>
                                                <p class="text-muted">${fileTypeText}</p>
                                            </div>
                                            <div class="office-preview">
                                                ${htmlContent}
                                            </div>
                                        </div>`;
                                }
                            });
                        } else if (fileName.endsWith('.txt') || fileName.endsWith('.log') || fileName.endsWith('.csv') || fileName.endsWith('.json') || fileName.endsWith('.xml')) {
                            // Fichiers texte - Afficher le contenu directement
                            readTextFile(file, function (content, error) {
                                if (error) {
                                    filePreviewContent.innerHTML = `
                                        <div class="alert alert-danger">
                                            <h4>Erreur lors de la lecture du fichier texte</h4>
                                            <p>${error.message || 'Impossible de lire le fichier texte'}</p>
                                        </div>`;
                                } else {
                                    displayTextFileContent(content, file.name, filePreviewContent, zip, file.name);
                                }
                            });
                        } else {
                            filePreviewContent.innerHTML = `
                                <div class="text-center p-5">
                                    <div class="mb-4">
                                        <i class="${fileIcon} fa-5x" style="color: ${fileColor};"></i>
                                        <h4 class="mt-3">${file.name}</h4>
                                        <p class="text-muted">${fileTypeText}</p>
                                    </div>
                                    <div class="alert alert-warning">
                                        <p>Ce type de fichier ne peut pas être prévisualisé directement dans le navigateur.</p>
                                        <p>Veuillez télécharger le fichier pour le consulter.</p>
                                    </div>
                                    <a href="${fileURL}" download="${file.name}" class="btn btn-success me-2">
                                        <i class="fas fa-download" style="color: white;"></i> Télécharger
                                    </a>
                                </div>`;
                        }
                    }
                });
            });

            // Événement lors de la suppression d'un fichier
            this.on("removedfile", function (file) {
                if (file.upload_id) {
                    uploadedFiles = uploadedFiles.filter(f => f.id !== file.upload_id);
                    document.getElementById('uploaded_files').value = JSON.stringify(uploadedFiles);
                    fetch(deleteRoute.value, {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken.content
                        },
                        body: JSON.stringify({
                            filepath: file.filepath
                        })
                    })
                        .then(response => response.json())
                        .then(data => console.log("File deleted:", data))
                        .catch(error => console.error("Delete error:", error));
                }
            });

            // Événement lors de l'upload réussi
            this.on("success", function (file, response) {
                console.log("Upload success:", response);

                // Afficher l'icône de succès
                const successMark = file.previewElement.querySelector('.dz-success-mark');
                if (successMark) {
                    successMark.style.opacity = '1';
                }

                // Stocker les informations du fichier (notamment le chemin)
                file.upload_id = response.id;
                file.filepath = response.filepath; // Ajout du chemin du fichier comme propriété de l'objet file
                uploadedFiles.push({
                    name: file.name,
                    size: file.size,
                    path: response.filepath,
                    id: response.id
                });

                // Mettre à jour le champ caché
                document.getElementById('uploaded_files').value = JSON.stringify(uploadedFiles);
            });

            this.on("error", function (file, errorMessage) {
                console.error("Upload error:", errorMessage);
                alert('Erreur lors de l\'upload: ' + errorMessage);
            });
        }
    });

    // Fermer le prévisualiseur de fichier lorsqu'on clique sur le bouton fermer
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('close-preview') || e.target.closest('.close-preview')) {
            document.getElementById('filePreviewContainer').style.display = 'none';
            // Arrêter toutes les vidéos ou audios en cours
            const videos = document.querySelectorAll('#filePreviewContent video, #filePreviewContent audio');
            videos.forEach(media => {
                if (!media.paused) {
                    media.pause();
                }
            });
        }
    });

    // Validation du formulaire
    const form = document.querySelector('form.needs-validation');
    if (form) {
        form.addEventListener('submit', function (e) {
            if (uploadedFiles.length === 0) {
                e.preventDefault();
                alert('Veuillez télécharger au moins un fichier.');
            }
        });
    } else {
        console.error("Form not found");
    }
});























