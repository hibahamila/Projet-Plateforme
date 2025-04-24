
<div class="card">
    <div class="modal-header bg-primary text-white py-3">
        <div>
            <h5 class="modal-title mb-1">
                <i class="fas fa-user-plus me-2"></i>Création d'un nouvel utilisateur
            </h5>

        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
    </div>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <img src="assets/images/createUser.png" alt="Création d'utilisateur" class="img-fluid mb-4" />
                    <p class="text-muted text-center">Remplissez le formulaire pour ajouter un nouvel utilisateur à la plateforme</p>
                </div>
            </div>
        </div>


        <div class="col-md-8">
            <form id="create-user-form" class="needs-validation" action="<?php echo e(route('admin.users.store')); ?>" method="POST" novalidate>
                <?php echo csrf_field(); ?>

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-user me-2 text-secondary"></i>Nom
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light d-flex justify-content-center" style="width: 45px;">
                                            <i class="fas fa-id-card text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> border-start-0" name="name" required placeholder="Entrez le nom">
                                        <div class="invalid-feedback js-error">Veuillez entrer un nom.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-user me-2 text-secondary"></i>Prénom
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light d-flex justify-content-center" style="width: 45px;">
                                            <i class="fas fa-id-card text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control <?php $__errorArgs = ['lastname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> border-start-0" name="lastname" required placeholder="Entrez le prénom">
                                        <div class="invalid-feedback js-error">Veuillez entrer un prénom.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-envelope me-2 text-secondary"></i>Email
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light d-flex justify-content-center" style="width: 45px;">
                                    <i class="fas fa-at text-muted"></i>
                                </span>
                                <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> border-start-0" name="email" required placeholder="exemple@formation.com">
                                <div class="invalid-feedback js-error">Veuillez entrer une adresse email valide.</div>
                            </div>
                        </div>

            
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-phone me-2 text-secondary"></i>Téléphone
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light d-flex justify-content-center" style="width: 45px;">
                                    <i class="fas fa-mobile-alt text-muted"></i>
                                </span>
                                <input type="tel" inputmode="tel" pattern="[0-9\s\+]{8,15}" oninput="this.value = this.value.replace(/[^0-9\s+]/g, '')" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> border-start-0" name="phone" required placeholder="92 125 420">
                                <div class="invalid-feedback js-error">
                                    Veuillez entrer un numéro de téléphone valide.
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-user-tag me-2 text-secondary"></i>Rôle
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light d-flex justify-content-center" style="width: 45px;">
                                    <i class="fas fa-shield-alt text-muted"></i>
                                </span>
                                <select class="form-control <?php $__errorArgs = ['roles'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> form-select border-start-0" id="role_id" name="roles" required>
                                    <?php if(auth()->user()->hasRole('admin')): ?>
                                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($role->name === 'professeur'): ?>
                                                <option value="<?php echo e($role->id); ?>" selected><?php echo e(ucfirst($role->name)); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php elseif(auth()->user()->hasRole('super-admin')): ?>
                                        <option value="" disabled selected>Sélectionnez un rôle</option>
                                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($role->id); ?>"><?php echo e(ucfirst($role->name)); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                                <div class="invalid-feedback js-error">Veuillez sélectionner un rôle.</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between border-top pt-3">
                            <button type="button" class="btn btn-outline-secondary cancel-user-creation" data-bs-dismiss="modal">
                                <i class="fas fa-times-circle me-2"></i>Annuler
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Créer l'utilisateur
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php /**PATH C:\Users\hibah\PFE\PlateformeELS\resources\views/admin/user/create.blade.php ENDPATH**/ ?>