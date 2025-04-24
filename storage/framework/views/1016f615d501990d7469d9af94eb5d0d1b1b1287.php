<?php $__env->startSection('title'); ?> Gestion des Utilisateurs & Permissions <?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/select2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/datatables.css')); ?>">

<style>
    .badge-light { background-color: #f8f9fa; color: #212529; }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #7367f0;
        border-color: #7367f0;
        color: #fff;
    }
    #alert-container {
        z-index: 1100;
    }
    .swal-custom-popup {
  border-radius: 5px;
  box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.swal-confirm-button-no-border,
.swal-cancel-button-no-border {
  box-shadow: none !important;
  outline: none !important;
  border: none !important;
  border-radius: 5px !important;
  padding: 10px 24px !important;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<div id="alert-container" class="position-fixed top-0 end-0 p-3" style="max-width: 600px;"></div>

<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('breadcrumb_title'); ?>
        <h3>Gestion des Utilisateurs & Permissions</h3>
    <?php $__env->endSlot(); ?>
    <li class="breadcrumb-item">Administration</li>
    <li class="breadcrumb-item active">Utilisateurs & Permissions</li>
<?php echo $__env->renderComponent(); ?>

<div class="container-fluid">
    <div class="email-wrap bookmark-wrap">
        <div class="row">
            <div class="col-xl-3">
                <div class="email-sidebar">
                    <a class="btn btn-primary email-aside-toggle" href="javascript:void(0)">contact filter </a>
                    <div class="email-left-aside">
                        <div class="card">
                            <div class="card-body">
                                <div class="email-app-sidebar left-bookmark">
                                    <ul class="nav main-menu contact-options" role="tablist">
                                        <li class="nav-item">
                                            <button class="badge-light btn-block btn-mail w-100" type="button"
                                                    id="loadCreateUserForm"
                                                    data-create-url="<?php echo e(route('admin.users.create')); ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal"
                                                    aria-label="Créer un nouvel utilisateur">
                                                <i class="me-2 fas fa-user-plus" aria-hidden="true"></i> Nouvel Utilisateur
                                            </button>
                                        </li>

                                        <li class="nav-item"><span class="main-title"> Vues</span></li>
                                        <li>
                                            <a id="load-users" href="javascript:void(0)"
                                               data-user-url="<?php echo e(route('admin.users.index')); ?>"
                                               aria-label="Afficher la liste des utilisateurs">
                                               <i class="me-2 fas fa-users" aria-hidden="true"></i> Utilisateurs
                                            </a>
                                        </li>
                                        <li>
                                            <a id="load-roles" href="javascript:void(0)"
                                               data-roles-url="<?php echo e(route('admin.roles.index')); ?>"
                                               aria-label="Afficher la liste des rôles">
                                               <i class="me-2 fas fa-user-tag" aria-hidden="true"></i> Rôles
                                            </a>
                                        </li>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('gérer des permissions')): ?>
                                            <li>
                                                <a id="load-permission" href="javascript:void(0)"
                                                data-permission-url="<?php echo e(route('admin.permissions.index')); ?>"
                                                aria-label="Afficher la liste des permissions">
                                                <i class="me-2 fas fa-key" aria-hidden="true"></i> Permissions
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-9">
                <div id="blog-container" class="card">
                    <div class="card-body text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <p class="mt-2">Chargement des données...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal amélioré -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <!-- Pas d'en-tête fixe, il sera chargé dynamiquement avec le formulaire -->

            <div class="modal-body position-relative p-0">

            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/ajax/admin-management.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/form-validation/form_validation3.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\hibah\PFE\PlateformeELS\resources\views/admin/apps/contacts.blade.php ENDPATH**/ ?>