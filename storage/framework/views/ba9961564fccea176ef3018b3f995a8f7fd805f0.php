






<?php $__env->startSection('title', 'Liste des Leçons'); ?>

<?php $__env->startPush('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/prism.css')); ?>">
<!-- Inclure ici le CSS d'icofont -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/icofont@1.0.0/css/icofont.css">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/datatables.css')); ?>">

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
<style>
    #success-message, #delete-message {
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }

    .custom-btn {
        background-color: #2b786a;
        color: white;
        border-color: #2b786a;
    }

    .custom-btn:hover {
        background-color: #1f5c4d;
        border-color: #1f5c4d;
        color: white;
    }

    .custom-btn i {
        margin-right: 8px;
    }

    .action-icon {
        cursor: pointer;
        font-size: 20px;
    }

    .delete-icon {
        color: #dc3545;
    }

    /* .edit-icon {
        color: #ffc107;
    } */
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('breadcrumb_title'); ?>
        <h3>Liste des Leçons</h3>
    <?php $__env->endSlot(); ?>
    <li class="breadcrumb-item">Leçons</li>
    <li class="breadcrumb-item active">Liste des Leçons</li>
<?php echo $__env->renderComponent(); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Leçons Disponibles</h5>
                    <span>Ce tableau affiche la liste des leçons disponibles. Vous pouvez rechercher, trier et paginer les données.</span>
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success" id="success-message">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if(session('delete')): ?>
                        <div class="alert alert-danger" id="delete-message">
                            <?php echo e(session('delete')); ?>

                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-end mb-3">
                        <a class="btn btn-primary custom-btn" href="<?php echo e(route('lessoncreate')); ?>">
                            <i class="icofont icofont-plus-square"></i> Ajouter une Leçon
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="display" id="lessons-table">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th>Durée</th>
                                    <th>Chapitre</th>
                                    <th>Fichier</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($lesson->titre); ?></td>
                                        <td><?php echo e($lesson->description); ?></td>
                                        <td><?php echo e($lesson->duree); ?></td>
                                        <td><?php echo e($lesson->chapitre->titre ?? 'Non attribué'); ?></td>
                                        <td>
                                            <?php if($lesson->file_path): ?>
                                                <a href="<?php echo e(asset('storage/' . $lesson->file_path)); ?>" target="_blank">
                                                    Voir le fichier
                                                </a>
                                            <?php else: ?>
                                                Aucun fichier
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <!-- Icône Modifier -->
                                            <i class="icofont icofont-edit edit-icon action-icon" data-edit-url="<?php echo e(route('lessonedit', $lesson->id)); ?>" style="cursor: pointer;"></i>

                                            <!-- Icône Supprimer -->
                                            <i class="icofont icofont-ui-delete delete-icon action-icon" data-delete-url="<?php echo e(route('lessondestroy', $lesson->id)); ?>" data-csrf="<?php echo e(csrf_token()); ?>" style="cursor: pointer; color: rgb(204, 28, 28);"></i>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('assets/js/dropzone/dropzone.js')); ?>"></script>
     <script src="<?php echo e(asset('assets/js/dropzone/dropzone-script.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/prism/prism.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/clipboard/clipboard.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/custom-card/custom-card.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/height-equal.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/actions-icon/actions-icon.js')); ?>"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>

<script>
    window.onload = function() {
        ['success-message', 'delete-message'].forEach(id => {
            const message = document.getElementById(id);
            if (message) {
                message.style.opacity = 1;
                setTimeout(() => {
                    message.style.opacity = 0;
                }, 2000);
            }
        });

        // Initialisation de DataTable
        $('#lessons-table').DataTable({
            language: {
               url: "//cdn.datatables.net/plug-ins/1.12.1/i18n/fr-FR.json" // Langue française
            },
            responsive: true,
            paging: true,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Tous"]],
            pageLength: 10,
            order: [[0, 'asc']] // Tri par défaut sur la première colonne
        });
    }
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\apprendre laravel\platformeEls\resources\views/admin/apps/lesson/lessons.blade.php ENDPATH**/ ?>