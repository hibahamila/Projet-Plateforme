<?php $__env->startPush('css'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><?php echo e(__('Modifier votre mot de passe')); ?></h4>
                </div>

                <div class="card-body p-4">
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo e(session('error')); ?>

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <p class="text-muted mb-4">Pour sécuriser votre compte, veuillez définir un nouveau mot de passe. Utilisez un mot de passe fort avec au moins 8 caractères, incluant des chiffres et des caractères spéciaux.</p>

                    <!-- Formulaire de changement de mot de passe -->
                    <form method="POST" action="<?php echo e(route('password.change')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right"><?php echo e(__('Nouveau mot de passe')); ?></label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" required autocomplete="new-password">

                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right"><?php echo e(__('Confirmer le mot de passe')); ?></label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <div class="col-md-8 offset-md-4 d-flex">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-lock mr-2"></i><?php echo e(__('Modifier le mot de passe')); ?>

                                </button>
                            </div>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <form method="POST" action="<?php echo e(route('password.skip')); ?>">
                            <?php echo csrf_field(); ?>
                            <p class="text-muted mb-3">Vous pourrez toujours modifier votre mot de passe ultérieurement depuis les paramètres de votre compte.</p>
                            <button type="submit" class="btn btn-outline-secondary">
                                <?php echo e(__('Ignorer pour le moment')); ?> <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 10px;
    border: none;
}

.card-header {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

.btn-primary {
    border-radius: 5px;
    padding: 10px 20px;
    transition: all 0.3s;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.btn-outline-secondary {
    border-radius: 5px;
    padding: 8px 16px;
    transition: all 0.3s;
}

.btn-outline-secondary:hover {
    background-color: #f8f9fa;
}

.form-control {
    border-radius: 5px;
    padding: 10px 15px;
    height: auto;
}

input[type="password"]:focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    border-color: #80bdff;
}

hr {
    border-top: 1px solid rgba(0, 0, 0, 0.1);
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\apprendre laravel\platformeEls\resources\views/auth/change-password.blade.php ENDPATH**/ ?>