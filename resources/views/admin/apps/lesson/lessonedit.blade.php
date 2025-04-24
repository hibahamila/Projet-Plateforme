
 @extends('layouts.admin.master')

 @section('title') 
     Modifier une Leçon 
 @endsection
 
 @push('css')
 <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/MonCss/dropzone.css') }}">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
 <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
 <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
 <style>
     .file-card {
         border: 1px solid #ddd;
         border-radius: 5px;
         padding: 10px;
         margin-bottom: 10px;
         background: #f9f9f9;
     }
     .file-preview {
         margin-top: 10px;
         padding: 10px;
         border-top: 1px solid #eee;
     }
     .dz-preview .dz-image img {
         width: 100%;
         height: 100%;
         object-fit: cover;
     }
     #existing-files-container {
         max-height: 300px;
         overflow-y: auto;
     }
     .link-item {
         margin-bottom: 5px;
     }
     .file-name {
         max-width: 200px;
         white-space: nowrap;
         overflow: hidden;
         text-overflow: ellipsis;
         display: inline-block;
         vertical-align: middle;
     }
     .file-name:hover {
         white-space: normal;
         overflow: visible;
         text-overflow: unset;
         background-color: #f8f9fa;
         z-index: 1000;
         position: relative;
     }
     .file-actions {
         display: flex;
         gap: 5px;
     }
 </style>
 @endpush
 
 @section('content')
 <div class="container-fluid">
     <div class="row">
         <div class="col-sm-12">
             <div class="card">
                 <div class="card-header pb-0">
                     <h5>Modifier une Leçon</h5>
                     <span>Mettez à jour les informations de la leçon</span>
                 </div>
                 <div class="card-body">
                     @if ($errors->any())
                         <div class="alert alert-danger">
                             <ul class="mb-0">
                                 @foreach ($errors->all() as $error)
                                     <li>{{ $error }}</li>
                                 @endforeach
                             </ul>
                         </div>
                     @endif
 
                     <form action="{{ route('lessonupdate', $lesson->id) }}" method="POST" enctype="multipart/form-data" id="lesson-form">
                         @csrf
                         @method('PUT')
                         <input type="hidden" id="uploadRoute" value="{{ route('upload.temp') }}">
                         <input type="hidden" id="deleteRoute" value="{{ route('delete.temp') }}">
                         <input type="hidden" name="deleted_files" id="deleted_files" value="">
 
                         <!-- Titre -->
                         <div class="mb-3 row">
                             <label class="col-sm-2 col-form-label">Titre <span class="text-danger">*</span></label>
                             <div class="col-sm-10">
                                 <input class="form-control" type="text" name="title" 
                                        value="{{ old('title', $lesson->title) }}" required>
                             </div>
                         </div>
 
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Description <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <div class="input-group" style="flex-wrap: nowrap;">
                                    <div class="input-group-text d-flex align-items-stretch" style="height: auto;">
                                        <i class="fa fa-align-left align-self-center"></i>
                                    </div>
                                    <textarea class="form-control" id="description" name="description" placeholder="Description" required>{{ old('description',$lesson->description) }}</textarea>
                                </div>
                                <div class="invalid-feedback">Veuillez entrer une description valide.</div>
                            </div>
                        </div>

                         <!-- Durée -->
                         <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Durée (HH:mm:ss) <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" name="duration" 
                                       value="{{ old('duration', $lesson->duration) }}" 
                                       pattern="\d{2}:\d{2}:\d{2}" required>
                            </div>
                        </div>
                         <!-- Chapitre -->
                         <div class="mb-3 row">
                             <label class="col-sm-2 col-form-label">Chapitre <span class="text-danger">*</span></label>
                             <div class="col-sm-10">
                                 <select class="form-select select2-chapitre" name="chapter_id" required>
                                     <option value="">Sélectionnez un chapitre</option>
                                     @foreach($chapitres as $chapitre)
                                         <option value="{{ $chapitre->id }}" 
                                             {{ old('chapter_id', $lesson->chapter_id) == $chapitre->id ? 'selected' : '' }}>
                                             {{ $chapitre->title }}
                                         </option>
                                     @endforeach
                                 </select>
                             </div>
                         </div>
 
                         <!-- Fichiers existants -->
                         <div class="mb-3 row">
                             <label class="col-sm-2 col-form-label">Fichiers existants</label>
                             <div class="col-sm-10">
                                 <div class="existing-files" id="existing-files-container">
                                     @foreach($lesson->files as $file)
                                         <div class="file-card" id="file-{{ $file->id }}">
                                             <div class="d-flex justify-content-between align-items-center">
                                                 <div>
                                                     <i class="fas {{ getFileIcon($file->file_type) }} me-2"></i>
                                                     <span class="file-name" title="{{ $file->name }} ({{ formatFileSize($file->file_size) }})">
                                                         {{ $file->name }}
                                                     </span>
                                                     <small class="text-muted ms-2">{{ formatFileSize($file->file_size) }}</small>
                                                 </div>
                                                 <div class="file-actions">
                                                     {{-- <a href="{{ Storage::url($file->file_path) }}" 
                                                        target="_blank"
                                                        class="btn btn-sm btn-info"
                                                        download="{{ $file->name }}"
                                                        title="Télécharger">
                                                         <i class="fas fa-download"></i>
                                                     </a> --}}
                                                     <button type="button" class="btn btn-sm btn-danger delete-existing-file" 
                                                             data-file-id="{{ $file->id }}"
                                                             title="Supprimer">
                                                         <i class="fas fa-trash"></i>
                                                     </button>
                                                 </div>
                                             </div>
                                         </div>
                                     @endforeach
                                     @if($lesson->files->isEmpty())
                                         <div class="alert alert-info">Aucun fichier existant</div>
                                     @endif
                                 </div>
                             </div>
                         </div>
 
                         <!-- Nouveaux fichiers -->
                         <div class="mb-3 row">
                             <label class="col-sm-2 col-form-label">Ajouter des fichiers</label>
                             <div class="col-sm-10">
                                 <div class="dropzone" id="fileUploadDropzone"></div>
                                 <input type="hidden" name="uploaded_files" id="uploaded_files" value="">
                             </div>
                         </div>

                         <!-- Conteneur pour la prévisualisation des fichiers -->
<div id="filePreviewContainer" style="display:none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.9); z-index: 9999; padding: 20px; overflow: auto;">
    <div class="card" style="max-width: 90%; margin: 20px auto; max-height: 90vh; overflow: hidden;">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <h5 class="mb-0" id="filePreviewTitle">Aperçu du fichier</h5>
            <button type="button" class="btn-close btn-close-white close-preview" aria-label="Close"></button>
        </div>
        <div class="card-body" id="filePreviewContent" style="max-height: calc(90vh - 60px); overflow: auto; background: #f8f9fa;">
            <!-- Le contenu sera inséré ici dynamiquement -->
        </div>
    </div>
</div>
 
                         <!-- Liens -->
                         <div class="mb-3 row">
                             <label class="col-sm-2 col-form-label">Liens <span class="text-danger">*</span></label>
                             <div class="col-sm-10">
                                 <div id="links-container">
                                     @php
                                         $links = json_decode($lesson->link) ?? [];
                                         if (json_last_error() !== JSON_ERROR_NONE) {
                                             $links = [$lesson->link];
                                         }
                                     @endphp
                                     @foreach($links as $index => $link)
                                         <div class="link-item input-group mb-2">
                                             <input type="url" name="links[]" class="form-control" 
                                                    value="{{ old('links.'.$index, $link) }}" 
                                                    placeholder="https://example.com">
                                             @if($index > 0)
                                                 <button type="button" class="btn btn-danger remove-link">
                                                     <i class="fas fa-times"></i>
                                                 </button>
                                             @endif
                                         </div>
                                     @endforeach
                                 </div>
                                 <button type="button" id="add-link" class="btn btn-sm btn-primary mt-2">
                                     <i class="fas fa-plus"></i> Ajouter un lien
                                 </button>
                             </div>
                         </div>
 
                         <!-- Boutons -->
                         <div class="row mt-4">
                             <div class="col text-end">
                                 <button type="submit" class="btn btn-primary">
                                     <i class="fas fa-save"></i> Enregistrer
                                 </button>
                                 <a href="{{ route('lessons') }}" class="btn btn-danger">
                                     <i class="fas fa-times"></i> Annuler
                                 </a>
                             </div>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>
 </div>
 @endsection
 
 @push('scripts')
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
 <script src="{{ asset('assets/js/MonJs/lecons/lecon-edit.js') }}"></script>
 <script src="{{ asset('assets/js/MonJs/select2-init/single-select.js') }}"></script>
<script src="{{ asset('assets/js/MonJs/description/description.js') }}"></script>
<script src="{{ asset('assets/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="https://cdn.tiny.cloud/1/cwjxs6s7k08kvxb3t6udodzrwpomhxtehiozsu4fem2igekf/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>


 @endpush
 
 @php
 function getFileIcon($type) {
     $icons = [
         'pdf' => 'fa-file-pdf',
         'doc' => 'fa-file-word',
         'docx' => 'fa-file-word',
         'xls' => 'fa-file-excel',
         'xlsx' => 'fa-file-excel',
         'ppt' => 'fa-file-powerpoint',
         'pptx' => 'fa-file-powerpoint',
         'zip' => 'fa-file-archive',
         'mp3' => 'fa-file-audio',
         'mp4' => 'fa-file-video',
         'jpg' => 'fa-file-image',
         'jpeg' => 'fa-file-image',
         'png' => 'fa-file-image',
     ];
     return $icons[strtolower($type)] ?? 'fa-file';
 }
 
 function formatFileSize($bytes) {
     if ($bytes >= 1073741824) {
         return number_format($bytes / 1073741824, 2) . ' GB';
     } elseif ($bytes >= 1048576) {
         return number_format($bytes / 1048576, 2) . ' MB';
     } elseif ($bytes >= 1024) {
         return number_format($bytes / 1024, 2) . ' KB';
     } elseif ($bytes > 1) {
         return $bytes . ' bytes';
     } elseif ($bytes == 1) {
         return '1 byte';
     } else {
         return '0 bytes';
     }
 }
 @endphp 
















{{-- 
 @extends('layouts.admin.master')

@section('title') 
    Modifier une Leçon 
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/MonCss/dropzone.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .file-card {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
        background: #f9f9f9;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .file-card:hover {
        background-color: #f1f1f1;
    }
    .file-card.active {
        background-color: #e9f7fe;
        border-color: #62b5e5;
    }
    .file-preview {
        margin-top: 10px;
        padding: 10px;
        border-top: 1px solid #eee;
    }
    .dz-preview .dz-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    #existing-files-container {
        max-height: 300px;
        overflow-y: auto;
    }
    .link-item {
        margin-bottom: 5px;
    }
    .file-name {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
        vertical-align: middle;
    }
    .file-name:hover {
        white-space: normal;
        overflow: visible;
        text-overflow: unset;
        background-color: #f8f9fa;
        z-index: 1000;
        position: relative;
    }
    .file-actions {
        display: flex;
        gap: 5px;
    }
    .file-icon {
        font-size: 1.2rem;
        width: 24px;
        text-align: center;
    }
    .file-icon.pdf { color: #e74c3c; }
    .file-icon.doc, .file-icon.docx { color: #2b579a; }
    .file-icon.xls, .file-icon.xlsx { color: #217346; }
    .file-icon.ppt, .file-icon.pptx { color: #d24726; }
    .file-icon.mp4, .file-icon.avi { color: #9b59b6; }
    .file-icon.mp3, .file-icon.wav { color: #3498db; }
    .file-icon.zip, .file-icon.rar { color: #f39c12; }
    .file-icon.jpg, .file-icon.jpeg, .file-icon.png { color: #1abc9c; }
    
    .preview-container {
        height: 500px;
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
        position: relative;
        margin-bottom: 20px;
    }
    .preview-iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
    .preview-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        flex-direction: column;
        color: #777;
        background-color: #f9f9f9;
    }
    .preview-placeholder i {
        font-size: 48px;
        margin-bottom: 10px;
    }
    .zip-explorer {
        height: 100%;
        background: white;
        padding: 15px;
        display: none;
    }
    .zip-breadcrumb {
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    .zip-folder {
        cursor: pointer;
        padding: 8px;
        border-radius: 3px;
    }
    .zip-folder:hover {
        background-color: #f1f1f1;
    }
    .zip-file {
        padding: 8px;
        cursor: pointer;
    }
    .zip-file:hover {
        background-color: #f1f1f1;
    }
    .zip-content {
        height: calc(100% - 50px);
        overflow: auto;
    }
    .back-to-files {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 100;
        background: rgba(255,255,255,0.8);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        border: none;
    }
    .back-to-files:hover {
        background: rgba(255,255,255,1);
    }
    .rotate-icon {
        animation: rotate 1s linear infinite;
    }
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Modifier une Leçon</h5>
                    <span>Mettez à jour les informations de la leçon</span>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('lessonupdate', $lesson->id) }}" method="POST" enctype="multipart/form-data" id="lesson-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="uploadRoute" value="{{ route('upload.temp') }}">
                        <input type="hidden" id="deleteRoute" value="{{ route('delete.temp') }}">
                        <input type="hidden" id="filePreviewRoute" value="{{ route('file.preview') }}">
                        <input type="hidden" name="deleted_files" id="deleted_files" value="">

                        <!-- Titre -->
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Titre <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" name="title" 
                                       value="{{ old('title', $lesson->title) }}" required>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Description <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="description" rows="5" required>{{ old('description', $lesson->description) }}</textarea>
                            </div>
                        </div>

                        <!-- Durée -->
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Durée (HH:mm) <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" name="duration" 
                                       value="{{ old('duration', $lesson->duration) }}" 
                                       pattern="\d{2}:\d{2}" required>
                            </div>
                        </div>

                        <!-- Chapitre -->
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Chapitre <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <select class="form-select select2-chapitre" name="chapitre_id" required>
                                    <option value="">Sélectionnez un chapitre</option>
                                    @foreach($chapitres as $chapitre)
                                        <option value="{{ $chapitre->id }}" 
                                            {{ old('chapitre_id', $lesson->chapitre_id) == $chapitre->id ? 'selected' : '' }}>
                                            {{ $chapitre->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Aperçu de fichier -->
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Aperçu de fichier</label>
                            <div class="col-sm-10">
                                <div class="preview-container" id="file-preview-container">
                                    <div class="preview-placeholder" id="preview-placeholder">
                                        <i class="fas fa-file-alt"></i>
                                        <p>Sélectionnez un fichier pour afficher l'aperçu</p>
                                    </div>
                                    <iframe class="preview-iframe" id="preview-iframe" style="display: none;"></iframe>
                                    <div class="zip-explorer" id="zip-explorer">
                                        <div class="zip-breadcrumb" id="zip-breadcrumb">
                                            <i class="fas fa-folder-open"></i> <span id="zip-current-path">Root</span>
                                        </div>
                                        <div class="zip-content" id="zip-content"></div>
                                    </div>
                                    <button type="button" class="back-to-files" id="back-to-files" style="display: none;">
                                        <i class="fas fa-arrow-left"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Fichiers existants -->
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Fichiers existants</label>
                            <div class="col-sm-10">
                                <div class="existing-files" id="existing-files-container">
                                    @foreach($lesson->files as $file)
                                        <div class="file-card" id="file-{{ $file->id }}" data-file-id="{{ $file->id }}" data-file-path="{{ Storage::url($file->file_path) }}" data-file-type="{{ $file->file_type }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <i class="file-icon {{ strtolower($file->file_type) }} fas {{ getFileIcon($file->file_type) }} me-2"></i>
                                                    <span class="file-name" title="{{ $file->name }} ({{ formatFileSize($file->file_size) }})">
                                                        {{ $file->name }}
                                                    </span>
                                                    <small class="text-muted ms-2">{{ formatFileSize($file->file_size) }}</small>
                                                </div>
                                                <div class="file-actions">
                                                    <a href="{{ Storage::url($file->file_path) }}" 
                                                       target="_blank"
                                                       class="btn btn-sm btn-info"
                                                       download="{{ $file->name }}"
                                                       title="Télécharger">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger delete-existing-file" 
                                                            data-file-id="{{ $file->id }}"
                                                            title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($lesson->files->isEmpty())
                                        <div class="alert alert-info">Aucun fichier existant</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Nouveaux fichiers -->
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Ajouter des fichiers</label>
                            <div class="col-sm-10">
                                <div class="dropzone" id="fileUploadDropzone"></div>
                                <input type="hidden" name="uploaded_files" id="uploaded_files" value="">
                            </div>
                        </div>

                        <!-- Liens -->
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Liens <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <div id="links-container">
                                    @php
                                        $links = json_decode($lesson->link) ?? [];
                                        if (json_last_error() !== JSON_ERROR_NONE) {
                                            $links = [$lesson->link];
                                        }
                                    @endphp
                                    @foreach($links as $index => $link)
                                        <div class="link-item input-group mb-2">
                                            <input type="url" name="links[]" class="form-control" 
                                                   value="{{ old('links.'.$index, $link) }}" 
                                                   placeholder="https://example.com">
                                            @if($index > 0)
                                                <button type="button" class="btn btn-danger remove-link">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" id="add-link" class="btn btn-sm btn-primary mt-2">
                                    <i class="fas fa-plus"></i> Ajouter un lien
                                </button>
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="row mt-4">
                            <div class="col text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Enregistrer
                                </button>
                                <a href="{{ route('lessons') }}" class="btn btn-danger">
                                    <i class="fas fa-times"></i> Annuler
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.21/mammoth.browser.min.js"></script>
<script>
Dropzone.autoDiscover = false;

$(document).ready(function() {
    $('.select2-chapitre').select2({
        placeholder: "Sélectionnez un chapitre",
        allowClear: true
    });

    let deletedFiles = [];
    let uploadedFiles = [];
    let currentActiveFile = null;
    let zipCurrentPath = [];
    let zipFileStructure = null;

    // Initialiser Dropzone
    let myDropzone = new Dropzone("#fileUploadDropzone", {
        url: $("#uploadRoute").val(),
        paramName: "file",
        maxFilesize: 50,
        maxFiles: 10,
        addRemoveLinks: true,
        acceptedFiles: ".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.mp4,.mp3,.zip,.jpg,.jpeg,.png",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        init: function() {
            this.on("sending", function(file, xhr, formData) {
                formData.append("original_name", file.name); // Envoyer le nom original
            });
            this.on("success", function(file, response) {
                file.serverId = response.filepath;
                uploadedFiles.push({
                    path: response.filepath,
                    name: response.original_name, // Utiliser le nom original
                    original_name: response.original_name,
                    size: file.size,
                    type: getFileExtension(response.original_name)
                });
                updateUploadedFilesInput();
                
                // Ajouter un événement pour prévisualiser le fichier
                file.previewElement.addEventListener('click', function() {
                    const fileType = getFileExtension(response.original_name);
                    previewFile(response.filepath, fileType, response.original_name);
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
        }
    });

    // Gestion suppression fichiers existants
    $(document).on('click', '.delete-existing-file', function(e) {
        e.stopPropagation();
        const fileId = $(this).data('file-id');
        const fileCard = $(`#file-${fileId}`);
        
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

    // Prévisualiser les fichiers existants lorsqu'on clique dessus
    $(document).on('click', '.file-card', function(e) {
        if ($(e.target).closest('.file-actions').length === 0) {
            const fileId = $(this).data('file-id');
            const filePath = $(this).data('file-path');
            const fileType = $(this).data('file-type').toLowerCase();
            const fileName = $(this).find('.file-name').text().trim();
            
            $('.file-card').removeClass('active');
            $(this).addClass('active');
            
            previewFile(filePath, fileType, fileName);
        }
    });

    // Fonction pour prévisualiser un fichier
    function previewFile(filePath, fileType, fileName) {
        const previewIframe = $('#preview-iframe');
        const previewPlaceholder = $('#preview-placeholder');
        const zipExplorer = $('#zip-explorer');
        const backButton = $('#back-to-files');
        
        // Réinitialiser l'affichage
        previewIframe.hide();
        previewPlaceholder.hide();
        zipExplorer.hide();
        backButton.show();
        
        // Afficher le loader
        previewPlaceholder.html('<i class="fas fa-spinner rotate-icon"></i><p>Chargement...</p>').show();
        
        fileType = fileType.toLowerCase();
        
        // Traiter selon le type de fichier
        switch(fileType) {
            case 'pdf':
                previewIframe.attr('src', filePath).show();
                previewPlaceholder.hide();
                break;
                
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                previewIframe.attr('src', `data:text/html;charset=utf-8,
                    <html>
                        <head>
                            <style>
                                body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f0f0f0; }
                                img { max-width: 100%; max-height: 100%; object-fit: contain; }
                            </style>
                        </head>
                        <body>
                            <img src="${filePath}" alt="${fileName}">
                        </body>
                    </html>
                `).show();
                previewPlaceholder.hide();
                break;
                
            case 'mp4':
            case 'webm':
            case 'ogg':
                previewIframe.attr('src', `data:text/html;charset=utf-8,
                    <html>
                        <head>
                            <style>
                                body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #000; }
                                video { max-width: 100%; max-height: 100%; }
                            </style>
                        </head>
                        <body>
                            <video controls autoplay>
                                <source src="${filePath}" type="video/${fileType}">
                                Votre navigateur ne supporte pas la lecture de vidéos.
                            </video>
                        </body>
                    </html>
                `).show();
                previewPlaceholder.hide();
                break;
                
            case 'mp3':
            case 'wav':
                previewIframe.attr('src', `data:text/html;charset=utf-8,
                    <html>
                        <head>
                            <style>
                                body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f0f0f0; }
                                .audio-container { width: 80%; text-align: center; }
                                audio { width: 100%; }
                                h3 { font-family: Arial, sans-serif; color: #333; }
                            </style>
                        </head>
                        <body>
                            <div class="audio-container">
                                <h3>${fileName}</h3>
                                <audio controls autoplay>
                                    <source src="${filePath}" type="audio/${fileType}">
                                    Votre navigateur ne supporte pas la lecture audio.
                                </audio>
                            </div>
                        </body>
                    </html>
                `).show();
                previewPlaceholder.hide();
                break;
                
            case 'doc':
            case 'docx':
                // Utiliser Mammoth.js pour convertir Word en HTML
                $.ajax({
                    url: filePath,
                    type: 'GET',
                    xhrFields: {
                        responseType: 'arraybuffer'
                    },
                    success: function(data) {
                        mammoth.convertToHtml({arrayBuffer: data})
                            .then(function(result) {
                                const html = result.value;
                                previewIframe.attr('src', `data:text/html;charset=utf-8,
                                    <html>
                                        <head>
                                            <style>
                                                body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; }
                                                h1, h2, h3, h4, h5, h6 { color: #333; }
                                                table { border-collapse: collapse; width: 100%; }
                                                th, td { border: 1px solid #ddd; padding: 8px; }
                                                th { background-color: #f2f2f2; }
                                            </style>
                                        </head>
                                        <body>
                                            ${html}
                                        </body>
                                    </html>
                                `).show();
                                previewPlaceholder.hide();
                            })
                            .catch(function(error) {
                                previewPlaceholder.html(`
                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                    <p>Impossible de convertir le document Word. Erreur: ${error.message}</p>
                                `).show();
                            });
                    },
                    error: function() {
                        previewPlaceholder.html(`
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                            <p>Impossible de charger le document Word.</p>
                        `).show();
                    }
                });
                break;
                
            case 'zip':
                // Explorer le contenu du ZIP
                previewPlaceholder.html('<i class="fas fa-spinner rotate-icon"></i><p>Extraction du contenu...</p>').show();
                
                $.ajax({
                    url: filePath,
                    type: 'GET',
                    xhrFields: {
                        responseType: 'arraybuffer'
                    },
                    success: function(data) {
                        JSZip.loadAsync(data).then(function(zip) {
                            zipFileStructure = buildZipFileStructure(zip);
                            zipCurrentPath = [];
                            renderZipDirectory(zipFileStructure);
                            previewPlaceholder.hide();
                            zipExplorer.show();
                        }).catch(function(error) {
                            previewPlaceholder.html(`
                                <i class="fas fa-exclamation-triangle text-warning"></i>
                                <p>Impossible d'extraire l'archive ZIP. Erreur: ${error.message}</p>
                            `).show();
                        });
                    },
                    error: function() {
                        previewPlaceholder.html(`
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                            <p>Impossible de charger l'archive ZIP.</p>
                        `).show();
                    }
                });
                break;
                
            default:
                previewPlaceholder.html(`
                    <i class="fas fa-file"></i>
                    <p>L'aperçu n'est pas disponible pour ce type de fichier (${fileType}).<br>
                    Vous pouvez le télécharger pour le visualiser.</p>
                `).show();
                break;
        }
    }

    // Bouton de retour à la liste des fichiers
    $('#back-to-files').click(function() {
        $('#preview-iframe').hide();
        $('#zip-explorer').hide();
        $('#preview-placeholder').html('<i class="fas fa-file-alt"></i><p>Sélectionnez un fichier pour afficher l\'aperçu</p>').show();
        $(this).hide();
        $('.file-card').removeClass('active');
    });

    // Fonctions pour gérer l'explorateur ZIP
    function buildZipFileStructure(zip) {
        const structure = {
            files: [],
            directories: {}
        };
        
        // Parcourir tous les fichiers de l'archive
        zip.forEach(function(relativePath, zipEntry) {
            if (zipEntry.dir) {
                // C'est un répertoire, ignore
            } else {
                // C'est un fichier
                const pathParts = relativePath.split('/');
                const fileName = pathParts.pop();
                let currentLevel = structure;
                
                // Construire l'arborescence des répertoires
                for (let i = 0; i < pathParts.length; i++) {
                    const part = pathParts[i];
                    if (!part) continue;
                    
                    if (!currentLevel.directories[part]) {
                        currentLevel.directories[part] = {
                            files: [],
                            directories: {}
                        };
                    }
                    currentLevel = currentLevel.directories[part];
                }
                
                // Ajouter le fichier au niveau courant
                currentLevel.files.push({
                    name: fileName,
                    path: relativePath,
                    zipEntry: zipEntry
                });
            }}
    });
    
    return structure;
}

function renderZipDirectory(structure) {
    const zipContent = $('#zip-content');
    zipContent.empty();
    
    // Mettre à jour le chemin actuel
    $('#zip-current-path').text(zipCurrentPath.length > 0 ? zipCurrentPath.join(' / ') : 'Root');
    
    // Ajouter le bouton de retour si nécessaire
    if (zipCurrentPath.length > 0) {
        zipContent.append(`
            <div class="zip-folder" data-action="back">
                <i class="fas fa-arrow-up"></i> ..
            </div>
        `);
    }
    
    // Parcourir l'arborescence pour atteindre le répertoire courant
    let currentDir = structure;
    for (const dir of zipCurrentPath) {
        currentDir = currentDir.directories[dir];
    }
    
    // Ajouter les répertoires
    Object.keys(currentDir.directories).sort().forEach(dir => {
        zipContent.append(`
            <div class="zip-folder" data-dir="${dir}">
                <i class="fas fa-folder"></i> ${dir}
            </div>
        `);
    });
    
    // Ajouter les fichiers
    currentDir.files.sort((a, b) => a.name.localeCompare(b.name)).forEach(file => {
        const fileType = getFileExtension(file.name);
        const iconClass = getFileIconClass(fileType);
        zipContent.append(`
            <div class="zip-file" data-file="${file.path}">
                <i class="fas ${iconClass}"></i> ${file.name}
            </div>
        `);
    });
    
    // Gestion des clics sur les dossiers
    $('.zip-folder').click(function() {
        const dir = $(this).data('dir');
        const action = $(this).data('action');
        
        if (action === 'back') {
            zipCurrentPath.pop();
        } else {
            zipCurrentPath.push(dir);
        }
        
        renderZipDirectory(zipFileStructure);
    });
    
    // Gestion des clics sur les fichiers
    $('.zip-file').click(function() {
        const filePath = $(this).data('file');
        const fileName = $(this).text().trim();
        const fileType = getFileExtension(fileName);
        
        // Trouver le fichier dans la structure
        let currentDir = zipFileStructure;
        for (const dir of zipCurrentPath) {
            currentDir = currentDir.directories[dir];
        }
        
        const fileObj = currentDir.files.find(f => f.path === filePath);
        if (!fileObj) return;
        
        // Prévisualiser le contenu selon le type de fichier
        previewZipFile(fileObj, fileType);
    });
}

function previewZipFile(fileObj, fileType) {
    const previewIframe = $('#preview-iframe');
    const previewPlaceholder = $('#preview-placeholder');
    const zipExplorer = $('#zip-explorer');
    
    previewPlaceholder.html('<i class="fas fa-spinner rotate-icon"></i><p>Chargement...</p>').show();
    zipExplorer.hide();
    
    fileObj.zipEntry.async('blob').then(function(content) {
        const blobUrl = URL.createObjectURL(content);
        
        fileType = fileType.toLowerCase();
        
        // Afficher selon le type de fichier
        switch(fileType) {
            case 'txt':
            case 'html':
            case 'css':
            case 'js':
            case 'json':
            case 'xml':
                const reader = new FileReader();
                reader.onload = function(e) {
                    const textContent = e.target.result;
                    previewIframe.attr('src', `data:text/html;charset=utf-8,
                        <html>
                            <head>
                                <style>
                                    body { font-family: monospace; white-space: pre-wrap; padding: 20px; line-height: 1.5; }
                                </style>
                            </head>
                            <body>${textContent.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</body>
                        </html>
                    `).show();
                    previewPlaceholder.hide();
                };
                reader.readAsText(content);
                break;
                
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                previewIframe.attr('src', `data:text/html;charset=utf-8,
                    <html>
                        <head>
                            <style>
                                body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f0f0f0; }
                                img { max-width: 100%; max-height: 100%; object-fit: contain; }
                            </style>
                        </head>
                        <body>
                            <img src="${blobUrl}" alt="${fileObj.name}">
                        </body>
                    </html>
                `).show();
                previewPlaceholder.hide();
                break;
                
            case 'pdf':
                previewIframe.attr('src', blobUrl).show();
                previewPlaceholder.hide();
                break;
                
            default:
                previewPlaceholder.html(`
                    <i class="fas fa-file"></i>
                    <p>L'aperçu n'est pas disponible pour ce type de fichier (${fileType}).<br>
                    Vous devez extraire l'archive ZIP pour voir ce fichier.</p>
                `).show();
                break;
        }
    }).catch(function(error) {
        previewPlaceholder.html(`
            <i class="fas fa-exclamation-triangle text-warning"></i>
            <p>Impossible d'extraire le fichier de l'archive. Erreur: ${error.message}</p>
        `).show();
    });
}

function updateUploadedFilesInput() {
    $('#uploaded_files').val(JSON.stringify(uploadedFiles));
}

function updateDeletedFilesInput() {
    $('#deleted_files').val(JSON.stringify(deletedFiles));
}

function getFileExtension(filename) {
    return filename.split('.').pop().toLowerCase();
}

function getFileIconClass(fileType) {
    const icons = {
        'pdf': 'fa-file-pdf',
        'doc': 'fa-file-word',
        'docx': 'fa-file-word',
        'xls': 'fa-file-excel',
        'xlsx': 'fa-file-excel',
        'ppt': 'fa-file-powerpoint',
        'pptx': 'fa-file-powerpoint',
        'zip': 'fa-file-archive',
        'rar': 'fa-file-archive',
        'mp3': 'fa-file-audio',
        'wav': 'fa-file-audio',
        'mp4': 'fa-file-video',
        'avi': 'fa-file-video',
        'jpg': 'fa-file-image',
        'jpeg': 'fa-file-image',
        'png': 'fa-file-image',
        'txt': 'fa-file-alt',
        'html': 'fa-file-code',
        'css': 'fa-file-code',
        'js': 'fa-file-code',
    };
    return icons[fileType.toLowerCase()] || 'fa-file';
}

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
</script>
@endpush

@php
function getFileIcon($type) {
    $icons = [
        'pdf' => 'fa-file-pdf',
        'doc' => 'fa-file-word',
        'docx' => 'fa-file-word',
        'xls' => 'fa-file-excel',
        'xlsx' => 'fa-file-excel',
        'ppt' => 'fa-file-powerpoint',
        'pptx' => 'fa-file-powerpoint',
        'zip' => 'fa-file-archive',
        'mp3' => 'fa-file-audio',
        'mp4' => 'fa-file-video',
        'jpg' => 'fa-file-image',
        'jpeg' => 'fa-file-image',
        'png' => 'fa-file-image',
    ];
    return $icons[strtolower($type)] ?? 'fa-file';
}

function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        return $bytes . ' bytes';
    } elseif ($bytes == 1) {
        return '1 byte';
    } else {
        return '0 bytes';
    }
}
@endphp --}}