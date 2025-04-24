<?php $__env->startSection('title'); ?> Connexion <?php echo e($title); ?> <?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<?php $__env->stopPush(); ?>
<?php if(session('success')): ?>
    <div class="alert alert-success mb-4 shadow-sm">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-check-circle-fill me-2" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                <?php echo e(session('success')); ?>

            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    </div>
<?php endif; ?>

<?php $__env->startSection('content'); ?>

    <section>
	    <div class="container-fluid">
	        <div class="row">
	            <div class="col-xl-5"><img class="bg-img-cover bg-center" src="<?php echo e(asset('assets/images/login/3.jpg')); ?>" alt="page de connexion" /></div>
	            <div class="col-xl-7 p-0">
	                <div class="login-card">
	                    <form class="theme-form login-form needs-validation" method="POST" action="<?php echo e(route('login')); ?>" novalidate>
	                        <?php echo csrf_field(); ?>

	                        <h4>Connexion</h4>
	                        <h6>Bienvenue ! Connectez-vous à votre compte.</h6>


	                        <div class="form-group">
	                            <label>Adresse Email</label>
	                            <div class="input-group">
	                                <span class="input-group-text"><i class="icon-email"></i></span>
	                                <input class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="email" name="email" required placeholder="exemple@gmail.com" value="<?php echo e(old('email')); ?>" />
	                                <div class="invalid-feedback js-error">Veuillez entrer votre email.</div>
	                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
	                                    <div class="invalid-feedback laravel-error" style="display: block;"><?php echo e($message); ?></div>
	                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
	                            </div>
	                        </div>


	                        <div class="form-group">
	                            <label>Mot de passe</label>
	                            <div class="input-group">
	                                <span class="input-group-text"><i class="icon-lock"></i></span>
	                                <input class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="password" name="password" required placeholder="*********" />
	                                <div class="show-hide"><span class="show"> </span></div>
	                                <div class="invalid-feedback js-error">Veuillez entrer votre mot de passe.</div>
	                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
	                                    <div class="invalid-feedback laravel-error" style="display: block;"><?php echo e($message); ?></div>
	                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
	                            </div>
	                        </div>


	                        <div class="form-group">
	                            <div class="checkbox">
	                                <input id="checkbox1" type="checkbox" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?> />
	                                <label class="text-muted" for="checkbox1">Se souvenir de moi</label>
	                            </div>
	                            <a class="link" href="<?php echo e(route('forgot.password')); ?>">Mot de passe oublié ?</a>
	                        </div>


	                        <div class="form-group">
	                            <button class="btn btn-primary btn-block" type="submit">Se connecter</button>
	                        </div>


	                        <div class="login-social-title">
	                            <h5>Se connecter avec</h5>
	                        </div>
	                        <div class="form-group">
	                            <ul class="login-social">
	                                <li><a href="https://www.linkedin.com/login" target="_blank"><i data-feather="linkedin"></i></a></li>
	                                <li><a href="https://www.linkedin.com/login" target="_blank"><i data-feather="twitter"></i></a></li>
	                                <li><a href="https://www.linkedin.com/login" target="_blank"><i data-feather="facebook"></i></a></li>
	                                <li><a href="https://www.instagram.com/login" target="_blank"><i data-feather="instagram"> </i></a></li>
	                            </ul>
	                        </div>


	                        <p>Vous n'avez pas encore de compte ? <a class="ms-2" href="<?php echo e(route('sign-up')); ?>">Créer un compte</a></p>
	                    </form>
	                </div>
	            </div>
	        </div>
	    </div>
	</section>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('assets/js/form-validation/form_validation2.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.authentication.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\apprendre laravel\platformeEls\resources\views/auth/login.blade.php ENDPATH**/ ?>