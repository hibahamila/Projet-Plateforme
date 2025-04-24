<?php $__env->startSection('title'); ?> Modifier un Cours <?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/dropzone.css')); ?>">
    <!-- CSS de Select2 via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('breadcrumb_title'); ?>
            <h3>Modifier un Cours</h3>
        <?php $__env->endSlot(); ?>
        <li class="breadcrumb-item">Cours</li>
        <li class="breadcrumb-item active">Modifier</li>
    <?php echo $__env->renderComponent(); ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Affichage des messages d'erreur -->
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- Affichage du message flash de succès -->
                        <?php if(session('success')): ?>
                            <div class="alert alert-success">
                                <?php echo e(session('success')); ?>

                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('coursupdate', $cours->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <!-- Titre -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="titre" class="form-label">Titre du Cours</label>
                                        <input type="text" name="titre" class="form-control" value="<?php echo e(old('titre', $cours->titre)); ?>" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea name="description" class="form-control" rows="4"  required><?php echo e(old('description', $cours->description)); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Date de début -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="date_debut" class="form-label">Date de Début</label>
                                        <input type="date" name="date_debut" class="form-control" value="<?php echo e(old('date_debut', $cours->date_debut)); ?>" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Date de fin -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="date_fin" class="form-label">Date de Fin</label>
                                        <input type="date" name="date_fin" class="form-control" value="<?php echo e(old('date_fin', $cours->date_fin)); ?>" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Formation avec Select2 -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="formation_id" class="form-label">Formation</label>
                                        <select name="formation_id" class="form-select select2-formation" required>
                                            <option value="" disabled selected>Sélectionner une formation</option>
                                            <?php $__currentLoopData = $formations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $formation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($formation->id); ?>" <?php echo e(old('formation_id', $cours->formation_id) == $formation->id ? 'selected' : ''); ?>>
                                                    <?php echo e($formation->titre); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Utilisateur avec Select2 -->
                            


                            <!-- Boutons -->
                            <div class="row">
                                <div class="col text-end">
                                    <button class="btn btn-secondary me-3" type="submit">Mettre à jour</button>
                                    <button class="btn btn-danger" type="button" onclick="window.location.href='<?php echo e(route('cours')); ?>'">Annuler</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('assets/js/dropzone/dropzone.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/dropzone/dropzone-script.js')); ?>"></script>
    <!-- Inclusion de Select2 JS via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <!-- Inclusion du fichier JS externe pour l'initialisation de Select2 -->
    <script src="<?php echo e(asset('assets/js/select2-init/single-select.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .custom-btn {
            background-color: #2b786a; /* Vert foncé */
            color: white;
            border-color: #2b786a;
        }
        .custom-btn:hover {
            background-color: #1f5c4d;
            border-color: #1f5c4d;
            color: white;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\apprendre laravel\platformeEls\resources\views/admin/apps/cours/coursedit.blade.php ENDPATH**/ ?>