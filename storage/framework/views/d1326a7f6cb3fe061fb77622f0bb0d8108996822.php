

<div class="card shadow-sm border-0 rounded-lg">
    <!-- En-tête avec design amélioré -->
    <div class="card-header bg-primary text-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="fas fa-edit me-2"></i>Modifier la Permission
                </h5>
                
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
    </div>

    <div class="card-body p-4">
        <form id="edit-permission-form" action="<?php echo e(route('admin.permissions.update', $permission)); ?>" method="POST" class="needs-validation" novalidate>
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="mb-4">
                <label for="permission-name-edit" class="form-label fw-medium d-flex align-items-center">
                    <i class="fas fa-key text-primary me-2"></i>
                    Nom de la permission
                </label>
                <div class="input-group" style="max-width: 600px;">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="fas fa-tag text-muted"></i>
                    </span>
                    <input type="text"
                           id="permission-name-edit"
                           class="form-control border-start-0 "
                           name="name"
                           value="<?php echo e($permission->name); ?>"
                           required>
                    <div class="invalid-feedback">
                        Le nom de permission est requis.
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">
                    <i class="fas fa-info-circle me-1"></i>
                    Le nom doit être unique et descriptif.
                </small>
            </div>

            <!-- Rôles associés (section optionnelle) -->
            <?php if(isset($permission->roles) && count($permission->roles) > 0): ?>
            <div class="mb-4">
                <label class="form-label fw-medium d-flex align-items-center">
                    <i class="fas fa-users-cog text-primary me-2"></i>
                    Rôles associés
                </label>
                <div class="d-flex flex-wrap gap-2 p-3 bg-light rounded">
                    <?php $__currentLoopData = $permission->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php switch(strtolower($role->name)):
                            case ('admin'): ?>
                                <span class="badge bg-danger"><?php echo e($role->name); ?></span>
                                <?php break; ?>
                            <?php case ('professeur'): ?>
                                <span class="badge bg-primary"><?php echo e($role->name); ?></span>
                                <?php break; ?>
                            <?php case ('etudiant'): ?>
                                <span class="badge bg-info"><?php echo e($role->name); ?></span>
                                <?php break; ?>
                            <?php default: ?>
                                <span class="badge bg-success"><?php echo e($role->name); ?></span>
                        <?php endswitch; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <small class="text-muted mt-2 d-block">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    La modification des rôles se fait depuis la gestion des rôles.
                </small>
            </div>
            <?php endif; ?>

            <div class="card-footer bg-light d-flex justify-content-end py-3 mt-4 mx-n4 mb-n4 border-top">
                <div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Enregistrer
                    </button>
                    <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Annuler
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php /**PATH D:\apprendre laravel\platformeEls\resources\views/admin/permission/edit.blade.php ENDPATH**/ ?>