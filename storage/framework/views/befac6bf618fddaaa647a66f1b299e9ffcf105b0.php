<?php $__env->startSection('title'); ?> Liste des Catégories
 <?php echo e($title); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/datatables.css')); ?>">


<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/prism.css')); ?>">


<style>
    /* Pagination verte */
    #categories-table_wrapper .dataTables_wrapper .dataTables_paginate .paginate_button {
        color: #28a745 !important;
        border: 1px solid #28a745 !important;
        margin: 0 5px;
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
        background-color: transparent !important;
    }

    #categories-table_wrapper .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #28a745 !important;
        color: white !important;
        border: 1px solid #28a745 !important;
    }

    #categories-table_wrapper .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background-color: #28a745 !important;
        color: white !important;
        border: 1px solid #28a745 !important;
    }

    .delete-icon {
        color: #dc3545;
    }

    #categories-table_wrapper .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    #categories-table_wrapper .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        color: #6c757d !important;
        border: 1px solid #6c757d !important;
        background-color: transparent !important;
        cursor: not-allowed;
    }
</style>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>
	<?php $__env->startComponent('components.breadcrumb'); ?>
		<?php $__env->slot('breadcrumb_title'); ?>
			<h3>Liste des Catégories</h3>
		<?php $__env->endSlot(); ?>
		<li class="breadcrumb-item">Apps</li>
		<li class="breadcrumb-item active">Liste des Catégories</li>
	<?php echo $__env->renderComponent(); ?>
	
	<div class="container-fluid">
	    <div class="row">
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-header">
	                    <h5>Catégories Disponibles</h5>
	                    <span>Ce tableau affiche la liste des catégories disponibles. Vous pouvez rechercher, trier et paginer les données.</span>
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

                        <div class="row project-cards">
                            <div class="col-md-12 project-list">
                                <div class="card">
                                    <div class="row">
                                        <div class="col-md-6 p-0">
                                        </div>
                                        <div class="col-md-6 p-0">
                                            <div class="form-group mb-0 me-0"></div>
                                            <a class="btn btn-primary custom-btn" href="<?php echo e(route('categoriecreate')); ?>">
                                                <i data-feather="plus-square"></i>Ajouter une catégorie
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="display" id="categories-table">
                                <thead>
                                    <tr>
                                        <th>Nom de la Catégorie</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categorie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($categorie->titre); ?></td>
                                            <td>
                                                <i class="icofont icofont-edit edit-icon action-icon" data-edit-url="<?php echo e(route('categorieedit', $categorie->id)); ?>"></i>
                                                <i class="icofont icofont-ui-delete delete-icon action-icon" data-delete-url="<?php echo e(route('categoriedestroy', $categorie->id)); ?>" data-csrf="<?php echo e(csrf_token()); ?>"></i>
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
	<script src="<?php echo e(asset('assets/js/prism/prism.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/clipboard/clipboard.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/custom-card/custom-card.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/height-equal.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/actions-icon/actions-icon.js')); ?>"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialisation de DataTable
            $('#categories-table').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.12.1/i18n/fr-FR.json" // Langue française
                },
                responsive: true,
                paging: true,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Tous"]],
                pageLength: 10,
                order: [[0, 'asc']] // Tri par défaut sur la première colonne
            });

            // Gestion des messages de succès et d'erreur
            const successMessage = document.getElementById('success-message');
            const deleteMessage = document.getElementById('delete-message');

            if (successMessage) {
                successMessage.style.opacity = 1;
                setTimeout(() => {
                    successMessage.style.transition = 'opacity 0.3s ease';
                    successMessage.style.opacity = 0;
                }, 2000);
            }

            if (deleteMessage) {
                deleteMessage.style.opacity = 1;
                setTimeout(() => {
                    deleteMessage.style.transition = 'opacity 0.3s ease';
                    deleteMessage.style.opacity = 0;
                }, 2000);
            }
        });
    </script>
	<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 







<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\apprendre laravel\platformeEls\resources\views/admin/apps/categorie/categories.blade.php ENDPATH**/ ?>