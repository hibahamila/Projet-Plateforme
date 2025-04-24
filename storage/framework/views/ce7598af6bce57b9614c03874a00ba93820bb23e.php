<?php $__env->startSection('title'); ?> Modifier la Réponse <?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<!-- Si vous avez des fichiers CSS supplémentaires -->
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('breadcrumb_title'); ?>
            <h3>Modifier la Réponse</h3>
        <?php $__env->endSlot(); ?>
        <li class="breadcrumb-item">Réponses</li>
        <li class="breadcrumb-item active">Modifier</li>
    <?php echo $__env->renderComponent(); ?>
    <?php $__env->startPush('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/dropzone.css')); ?>">
    <!-- CSS de Select2 via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <?php $__env->stopPush(); ?>

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

                        <form action="<?php echo e(route('reponseupdate', $reponse->id)); ?>" method="POST" class="form theme-form">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label">Question</label>
                                        <select class="form-select select2-question" name="question_id" required>
                                            <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($question->id); ?>" <?php echo e(old('question_id', $reponse->question_id) == $question->id ? 'selected' : ''); ?>>
                                                    <?php echo e($question->enonce); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label">Contenu</label>
                                        <input class="form-control" type="text" name="contenu" value="<?php echo e(old('contenu', $reponse->contenu)); ?>" required />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label">Est correcte ?</label>
                                        <select class="form-select" name="est_correcte" required>
                                            <option value="1" <?php echo e(old('est_correcte', $reponse->est_correcte) == 1 ? 'selected' : ''); ?>>Oui</option>
                                            <option value="0" <?php echo e(old('est_correcte', $reponse->est_correcte) == 0 ? 'selected' : ''); ?>>Non</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col text-end">
                                    <button class="btn btn-secondary me-3" type="submit">Mettre à jour</button>
                                    <button class="btn btn-danger" type="button" onclick="window.location.href='<?php echo e(route('reponses')); ?>'">Annuler</button>

                                    
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
    <!-- Inclusion du fichier externe pour l'initialisation de Select2 -->
    <script src="<?php echo e(asset('assets/js/select2-init/single-select.js')); ?>"></script>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<style>
    .custom-btn-modifier {
        background-color: #6a4e23; /* Marron */
        color: white; /* Texte en blanc */
        border-color: #6a4e23; /* Border avec la même couleur */
    }

    .custom-btn-modifier:hover {
        background-color: #4e3821; /* Marron plus foncé au survol */
        border-color: #4e3821;
        color: white; /* Texte en blanc */
    }

    .custom-btn-annuler {
        background-color: #e74c3c; /* Rouge */
        color: white; /* Texte en blanc */
        border-color: #e74c3c; /* Border avec la même couleur */
    }

    .custom-btn-annuler:hover {
        background-color: #c0392b; /* Rouge plus foncé au survol */
        border-color: #c0392b;
        color: white; /* Texte en blanc */
    }
</style>

<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\apprendre laravel\platformeEls\resources\views/admin/apps/reponse/reponseedit.blade.php ENDPATH**/ ?>