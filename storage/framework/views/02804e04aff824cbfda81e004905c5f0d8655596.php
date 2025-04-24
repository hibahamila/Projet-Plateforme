<?php $__env->startSection('title'); ?> Modifier une Leçon <?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/dropzone.css')); ?>">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('breadcrumb_title'); ?>
            <h3>Modifier une Leçon</h3>
        <?php $__env->endSlot(); ?>
        <li class="breadcrumb-item">Leçons</li>
        <li class="breadcrumb-item active">Modifier</li>
    <?php echo $__env->renderComponent(); ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('lessonupdate', $lesson->id)); ?>" method="POST" class="form theme-form" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <!-- Titre -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label">Titre</label>
                                        <input class="form-control" type="text" name="titre" placeholder="Titre" value="<?php echo e(old('titre', $lesson->titre)); ?>" required />
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" rows="4" name="description"  placeholder="Description" required><?php echo e(old('description', $lesson->description)); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Durée -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label">Durée (HH:mm)</label>
                                        <input class="form-control" type="text" name="duree" value="<?php echo e(old('duree', \Carbon\Carbon::parse($lesson->duree)->format('H:i'))); ?>" placeholder="Durée (HH:mm)" pattern="\d{2}:\d{2}" title="Format: HH:mm" required />
                                    </div>
                                </div>
                            </div>

                            <!-- Chapitre -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label">Chapitre</label>
                                        <select class="form-select select2" name="chapitre_id" required>
                                            <option value="" disabled>Choisir un chapitre</option>
                                            <?php $__currentLoopData = $chapitres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chapitre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($chapitre->id); ?>" <?php echo e(old('chapitre_id', $lesson->chapitre_id) == $chapitre->id ? 'selected' : ''); ?>>
                                                    <?php echo e($chapitre->titre); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Fichier -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">

                                        <!-- Affichage de l'ancien fichier s'il existe -->
                                        <?php if($lesson->file_path): ?>
                                            <div>
                                                <strong>Ancien fichier : </strong>
                                                <a href="<?php echo e(asset('storage/' . $lesson->file_path)); ?>" target="_blank">
                                                    Voir l'ancien fichier
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <div>Aucun fichier associé</div>
                                        <?php endif; ?>

                                        <!-- Champ pour télécharger un nouveau fichier -->
                                        <input class="form-control mt-2" type="file" name="file_path">
                                        <a href="<?php echo e(asset('storage/' . $lesson->file_path)); ?>" target="_blank">
                                        </a>
                                        <small class="text-muted">Si vous ne sélectionnez pas de fichier, l'ancien fichier restera inchangé.</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="row">
                                <div class="col text-end">
                                    <button class="btn btn-secondary me-3" type="submit">Mettre à jour</button>
                                    <a href="<?php echo e(route('lessons')); ?>" class="btn btn-danger px-4">Annuler</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('assets/js/dropzone/dropzone.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/dropzone/dropzone-script.js')); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="<?php echo e(asset('assets/js/select2-init/single-select.js')); ?>"></script>
<?php $__env->stopPush(); ?>




<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\apprendre laravel\platformeEls\resources\views/admin/apps/lesson/lessonedit.blade.php ENDPATH**/ ?>