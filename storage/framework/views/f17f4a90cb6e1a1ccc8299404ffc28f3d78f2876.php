<?php $__env->startSection('title'); ?> Modifier le Profil <?php echo e($title); ?> <?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/editProfileCss/parametreCompte.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('breadcrumb_title'); ?>
            <h3><i class="fas fa-user-edit me-2"></i>Modifier le Profil</h3>
        <?php $__env->endSlot(); ?>
        <li class="breadcrumb-item">Utilisateurs</li>
        <li class="breadcrumb-item active">Modifier le Profil</li>
    <?php echo $__env->renderComponent(); ?>

    <div class="container-fluid">
        <div class="edit-profile">
            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h4 class="card-title mb-0"><i class="fas fa-id-card me-2"></i>Mon Profil</h4>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-4">
                                <div class="avatar-circle text-white mx-auto">
                                    <span><?php echo e(substr($user->name, 0, 1)); ?><?php echo e(substr($user->lastname, 0, 1)); ?></span>
                                </div>
                            </div>

                            <div class="user-info mb-4">
                                <h5 class="mb-2"><?php echo e($user->name); ?> <?php echo e($user->lastname); ?></h5>
                                <p class="mb-0 text-muted">
                                    <i class="fas fa-user-tag me-2"></i>
                                    <?php echo e($user->roles->first()->name); ?>

                                </p>
                                <p class="mb-0 text-muted mt-2">
                                    <i class="fas fa-envelope me-2"></i>
                                    <?php echo e($user->email); ?>

                                </p>
                            </div>

                            <div class="list-group">
                                <a href="#" class="list-group-item list-group-item-action active" id="profile-tab" data-tab="profile">
                                    <i class="fas fa-user"></i> Modifier le Profil
                                </a>
                                <a href="#" class="list-group-item list-group-item-action" id="account-tab" data-tab="account">
                                    <i class="fas fa-cog"></i> Paramètres du Compte
                                </a>
                                <a href="#" class="list-group-item list-group-item-action" id="certification-tab" data-tab="certification">
                                    <i class="fas fa-certificate"></i> Certification
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <div class="card">
                        
                        <div class="card-body">
                            <div id="alert-container"></div>
                            <div class="loader" id="content-loader"></div>
                            <div id="tab-content">
                                <!-- Le contenu sera chargé dynamiquement ici -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const PROFILE_URLS = {
        profile: "<?php echo e(route('profile.edit')); ?>",
        account: "<?php echo e(route('profile.updateCompte')); ?>",
        email: "<?php echo e(route('profile.updateEmail')); ?>",
        password: "<?php echo e(route('profile.editPassword')); ?>",
        checkEmail: "<?php echo e(route('profile.checkEmail')); ?>",
        verifyPassword: "<?php echo e(route('profile.verifyPassword')); ?>",
        sendCode: "<?php echo e(route('profile.sendEmailVerificationCode')); ?>",
        validateCode: "<?php echo e(route('profile.validateCode')); ?>",
        verifyEmail: "<?php echo e(route('profile.verifyEmail')); ?>"
    };
</script>
<script src="<?php echo e(asset('assets/ajax/profile/editProfile.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/form-validation/form_validation2.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\apprendre laravel\platformeEls\resources\views/admin/apps/profile/parametreCompte.blade.php ENDPATH**/ ?>