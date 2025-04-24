
<div class="card">
    <div class="card-header bg-primary text-white py-3">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-white p-2 me-3">
                <i class="fas fa-user-tag text-primary fa-lg"></i>
            </div>

                <h3 class="fw-bold mb-0">Gestion des Utilisateurs</h3>
            </div>
        </div>
    </div>
    <div class="card-header">
        <h5>Liste des Utilisateurs</h5>
        <div class="filter-container mt-3">
            <div class="row">
                <div class="col-12 mb-2">
                    <div class="filter-title">Filtres</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                        <select class="form-select filter-select" id="role-filter" aria-label="Filtrer par rôle">
                            <option value="">Tous les rôles</option>
                            <?php $__currentLoopData = $allRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($role->name); ?>" <?php echo e(isset($selectedRole) && $selectedRole == $role->name ? 'selected' : ''); ?>><?php echo e($role->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                        <select class="form-select filter-select" id="status-filter" aria-label="Filtrer par statut">
                            <option value="">Tous les statuts</option>
                            <option value="active" <?php echo e(isset($selectedStatus) && $selectedStatus == 'active' ? 'selected' : ''); ?>>Actif</option>
                            <option value="inactive" <?php echo e(isset($selectedStatus) && $selectedStatus == 'inactive' ? 'selected' : ''); ?>>Inactif</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <button id="reset-filters" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-undo"></i> Réinitialiser les filtres
                    </button>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12" id="active-filters">
                    <?php if(isset($selectedRole) && $selectedRole): ?>
                        <span class="filter-badge">Rôle: <?php echo e($selectedRole); ?></span>
                    <?php endif; ?>
                    <?php if(isset($selectedStatus) && $selectedStatus): ?>
                        <span class="filter-badge">Statut: <?php echo e($selectedStatus == 'active' ? 'Actif' : 'Inactif'); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="users-table" class="table data-table">
                <thead>
                    <tr>
                        <th>Nom Complet</th>
                        <th>Email</th>
                        <th>Rôles</th>
                        <th>Statut</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($user->name); ?> <?php echo e($user->lastname ?? ''); ?></td>
                        <td><?php echo e($user->email); ?></td>
                        <td>

                            <?php $__empty_2 = true; $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
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
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                <span class="badge bg-secondary">Aucun rôle</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input toggle-status-switch" type="checkbox"
                                       data-url="<?php echo e(route('admin.users.toggleStatus', $user)); ?>"
                                       id="status-<?php echo e($user->id); ?>" <?php echo e($user->status === 'active' ? 'checked' : ''); ?>>

                            </div>
                        </td>
                        <td>
                            <div class="dropdown dropdown-user-actions">
                                <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                        id="dropdownMenuButton-<?php echo e($user->id); ?>" data-bs-toggle="dropdown"
                                        aria-expanded="false" aria-label="Actions pour <?php echo e($user->name); ?>">
                                    <i class="fas fa-ellipsis-h" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton-<?php echo e($user->id); ?>">
                                    <li>
                                        <a class="dropdown-item view-user-roles" href="javascript:void(0)"
                                           data-url="<?php echo e(route('admin.users.roles', $user)); ?>">
                                            <i class="fa fa-info-circle" aria-hidden="true"></i> Plus d'info
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item delete-user" href="javascript:void(0)"
                                           data-url="<?php echo e(route('admin.users.destroy', $user)); ?>">
                                            <i class="fas fa-trash me-2" aria-hidden="true"></i> Supprimer
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center">Aucun utilisateur trouvé</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>


        </div>
    </div>
</div>

<style>
    .dropdown-toggle::after {
        display: none;
    }
    .dropdown-menu {
        min-width: 10rem;
        box-shadow: 0 5px 10px rgba(0,0,0,0.1);
    }
    .dropdown-item {
        padding: 0.35rem 1.5rem;
        font-size: 0.875rem;
    }
    .form-switch .form-check-input {
        width: 2.5em;
        height: 1.5em;
        cursor: pointer;
    }
    .btn-light {
        border: 1px solid #dee2e6;
        background-color: #f8f9fa;
    }
    .dropdown-toggle {
        padding: 0.25rem 0.5rem;
    }
    .badge {
        margin-right: 3px;
    }

    /* Modifications pour corriger le problème d'affichage du dropdown */
    .dropdown-user-actions {
        position: relative;
    }
    .dropdown-menu-end {
        right: 0;
        left: auto !important;
    }
    .table-responsive {
        overflow: visible !important;
    }

    /* Nouveau style pour les filtres */
    .filter-container {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .filter-select {
        border-radius: 6px;
        border: 1px solid #ced4da;
        height: 38px;
        transition: all 0.3s;
    }

    .filter-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    #reset-filters {
        height: 38px;
        border-radius: 6px;
        transition: all 0.3s;
    }

    #reset-filters:hover {
        background-color: #6c757d;
        color: white;
    }


    .filter-badge {
        font-size: 0.75rem;
        background-color: #e9ecef;
        color: #495057;
        border-radius: 4px;
        padding: 3px 8px;
        margin-right: 5px;
        display: inline-flex;
        align-items: center;
    }

    .filter-badge i {
        margin-left: 5px;
        cursor: pointer;
    }

    .filter-title {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 5px;
    }


</style>
<?php /**PATH D:\apprendre laravel\platformeEls\resources\views/admin/user/index.blade.php ENDPATH**/ ?>