<?php $__env->startSection('title'); ?> Ajouter une Leçon <?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/MonCss/dropzone.css')); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<!-- Mammoth.js pour les fichiers Word -->
<script src="https://unpkg.com/mammoth@1.4.8/mammoth.browser.min.js"></script>
<!-- SheetJS pour les fichiers Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>

<?php $__env->stopPush(); ?>
<?php
    // Récupérer l'ID du chapitre depuis la requête
    $chapitreId = request()->query('chapitre_id');
?>
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Nouvelle Leçon</h5>
                        <span>Complétez les informations pour créer une nouvelle leçon</span>
                    </div>
                    <div class="card-body">
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <div class="form theme-form">
                            <form action="<?php echo e(route('lessonstore')); ?>" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
                                <?php echo csrf_field(); ?>
                                <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

                                <!-- Champ caché pour la route d'upload -->
                                <input type="hidden" id="uploadRoute" value="<?php echo e(route('upload.temp')); ?>">

                                <!-- Champ caché pour la route de suppression -->
                                <input type="hidden" id="deleteRoute" value="<?php echo e(route('delete.temp')); ?>">

                                <!-- Titre -->
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Titre <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-tag"></i></span>
                                            <input class="form-control" type="text" name="title" placeholder="Titre" value="<?php echo e(old('title')); ?>" required />
                                        </div>
                                        <div class="invalid-feedback">Veuillez entrer un titre valide.</div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Description <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-align-left"></i></span>
                                            <textarea id="description" class="form-control" rows="4" name="description" placeholder="Description" required><?php echo e(old('description')); ?></textarea>
                                        </div>
                                        <div class="invalid-feedback">Veuillez entrer une description valide.</div>
                                    </div>
                                </div>

                                <!-- Durée -->
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Durée (HH:mm:ss) <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icon-timer"></i></span>
                                            <input class="form-control" type="text" name="duration" value="<?php echo e(old('duration')); ?>" placeholder="Durée (HH:mm:ss)" pattern="\d{2}:\d{2}:\d{2}" title="Format: HH:mm:ss" required />
                                        </div>
                                        <div class="invalid-feedback">Veuillez entrer une durée valide (HH:mm:ss).</div>
                                    </div>
                                </div>

                                <!-- Chapitre -->
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Chapitre <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-auto">
                                                <span class="input-group-text"><i class="fa fa-book"></i></span>
                                            </div>
                                            <div class="col">
                                                <?php if(isset($chapitreId) && $chapitreId && isset($chapitres) && $chapitres->find($chapitreId)): ?>
                                                    <input type="text" class="form-control bg-light" value="<?php echo e($chapitres->find($chapitreId)->title); ?>" readonly />
                                                    <input type="hidden" name="chapter_id" value="<?php echo e($chapitreId); ?>">
                                                <?php else: ?>
                                                    <select class="form-select select2-chapitre" name="chapter_id" required>
                                                        <option value="" selected disabled>Choisir un chapitre</option>
                                                        <?php $__currentLoopData = $chapitres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chapitre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($chapitre->id); ?>" <?php echo e(old('chapter_id') == $chapitre->id ? 'selected' : ''); ?>><?php echo e($chapitre->title); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback">Veuillez sélectionner un chapitre valide.</div>
                                    </div>
                                </div>

                                <!-- Fichiers -->
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Fichiers <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <div class="dropzone-container dz-clickable" id="multipleFilesUpload">
                                            <div class="dz-message needsclick">
                                                <i class="icon-cloud-up"></i>
                                                <h6>Déposez les fichiers ici ou cliquez pour les uploader.</h6>
                                                <span class="note needsclick">(Formats acceptés: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, MP4, MP3, ZIP, JPG, JPEG, PNG)</span>
                                            </div>
                                        </div>
                                        <input type="hidden" name="uploaded_files" id="uploaded_files">
                                    </div>
                                </div>

                                <!-- Conteneur pour la prévisualisation des fichiers -->
                                <div id="filePreviewContainer" style="display:none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.8); z-index: 9999; padding: 20px; overflow: auto;">
                                    <div class="card" style="max-width: 90%; margin: 20px auto; max-height: 90vh; overflow: hidden;">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Aperçu du fichier</h5>
                                            <button type="button" class="btn-close close-preview" aria-label="Close"></button>
                                        </div>
                                        <div class="card-body" id="filePreviewContent" style="max-height: calc(90vh - 60px); overflow: auto;">
                                            <!-- Le contenu sera inséré ici dynamiquement -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Liens -->
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Liens <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-link"></i></span>
                                            <textarea class="form-control" name="link" id="link" rows="5" placeholder="Entrez un lien par ligne" required><?php echo e(old('link')); ?></textarea>
                                        </div>
                                        <small class="form-text text-muted">Entrez des liens valides, un par ligne.</small>
                                    </div>
                                </div>

                                <!-- Boutons de soumission -->
                                <div class="row">
                                    <div class="col">
                                        <div class="text-end mt-4">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fa fa-save"></i> Ajouter
                                            </button>
                                            <button class="btn btn-danger" type="button" onclick="window.location.href='<?php echo e(route('lessons')); ?>'">
                                                <i class="fa fa-times"></i> Annuler
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?php echo e(asset('assets/js/dropzone/dropzone.js')); ?>"></script>

<script src="<?php echo e(asset('assets/js/MonJs/lecons/dropzone.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/MonJs/lecons/file-preview.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/MonJs/lecons/pdf-preview.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/MonJs/lecons/image-preview.js')); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="<?php echo e(asset('assets/js/MonJs/select2-init/single-select.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/MonJs/form-validation/form-validation.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/MonJs/description/description.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/MonJs/lecons/link-validation.js')); ?>"></script>

<script src="https://cdn.tiny.cloud/1/cwjxs6s7k08kvxb3t6udodzrwpomhxtehiozsu4fem2igekf/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/pptxjs/dist/pptxjs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pptxgenjs@3.10.0/dist/pptxgen.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/pptx2html/dist/pptx2html.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\hibah\PFE\PlateformeELS\resources\views/admin/apps/lesson/lessoncreate.blade.php ENDPATH**/ ?>