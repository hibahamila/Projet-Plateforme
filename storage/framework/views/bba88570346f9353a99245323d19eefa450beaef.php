
<div class="card shadow">
    <div class="card-header bg-primary text-white py-3">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-white p-2 me-3">
                <i class="fas fa-key me-2 text-primary"></i>
            </div>

                <h3 class="fw-bold mb-0">Gestion des Permissions</h3>
            </div>
        </div>
    </div>
    <div class="row mb-4 mt-3 px-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-white border-start border-primary border-3">
                <div class="card-body d-flex justify-content-between align-items-center py-2">
                    <div>
                        <span class="text-muted small fw-medium">Total Permissions</span>
                        <h3 class="mb-0 fw-bold text-primary"><?php echo e(count($permissions)); ?></h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle">
                        <i class="fas fa-key text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Espace au milieu pour rapprocher les éléments -->
        <div class="col-md-4"></div>

        <!-- Bouton Nouvelle Permission à droite, maintenant plus petit -->
        <div class="col-md-4 d-flex justify-content-end align-items-center">
            <button class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm d-flex align-items-center"
                id="loadCreatePermissionForm"
                data-create-url="<?php echo e(route('admin.permissions.create')); ?>"
                data-bs-toggle="modal"
                data-bs-target="#exampleModal">
                <i class="fas fa-plus-circle me-1"></i>
                <span>Nouvelle Permission</span>
            </button>
        </div>
    </div>



    <div class="card-body">


        <!-- Section de filtrage et recherche -->
        <div class="row g-3 mb-4 bg-light p-3 rounded-3">
            <div class="col-md-4">
                <label for="permissionSearch" class="form-label small text-muted mb-1">Recherche</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0"
                           id="permissionSearch"
                           placeholder="Rechercher une permission...">
                </div>
            </div>
            <div class="col-md-4">
                <label for="role-filter-permission" class="form-label small text-muted mb-1">Filtrer par rôle</label>
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="fas fa-user-tag text-muted"></i>
                    </span>
                    <select class="form-select filter-select" id="role-filter-permission">
                        <option value="">Tous les rôles</option>
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($role->name); ?>" <?php echo e(isset($selectedRole) && $selectedRole == $role->name ? 'selected' : ''); ?>>
                                <?php echo e($role->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button id="reset-filters-permissions" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-undo me-1"></i> Réinitialiser les filtres
                </button>
            </div>
        </div>

        <!-- Tableau des permissions -->
        <div class="table-responsive ">
            <table id="permissions-table" class="table  table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="fw-semibold" style="width: 40%">Permission</th>
                        <th class="fw-semibold" style="width: 20%">Rôles associés</th>
                        <th class="fw-semibold" style="width: 15%">Utilisateurs</th>
                        <th class="fw-semibold text-center" style="width: 15%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr >
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-primary bg-opacity-10 p-2 rounded me-3">
                                    <i class="fas fa-key text-white"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-medium"><?php echo e($permission->name); ?></p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                <?php $__empty_2 = true; $__currentLoopData = $permission->roles ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                    <?php switch(strtolower($role->name)):
                                    case ('super-admin'): ?>
                                        <span class="badge bg-danger text-white" style="background-color: #900 !important;"><?php echo e($role->name); ?></span>
                                        <?php break; ?>
                                        <?php case ('admin'): ?>
                                            <span class="badge  bg-danger bg-opacity-75"><?php echo e($role->name); ?></span>
                                            <?php break; ?>
                                        <?php case ('professeur'): ?>
                                            <span class="badge  bg-primary bg-opacity-75"><?php echo e($role->name); ?></span>
                                            <?php break; ?>
                                        <?php case ('etudiant'): ?>
                                            <span class="badge  bg-info bg-opacity-75"><?php echo e($role->name); ?></span>
                                            <?php break; ?>
                                        <?php default: ?>
                                            <span class="badge  bg-success bg-opacity-75"><?php echo e($role->name); ?></span>
                                    <?php endswitch; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                    <span class="badge rounded-pill bg-secondary bg-opacity-10 text-white">Aucun rôle</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <?php echo e($permission->users_count); ?> utilisateurs
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                        id="dropdownMenuButton-<?php echo e($permission->id); ?>" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton-<?php echo e($permission->id); ?>">
                                    <li>
                                        <a class="dropdown-item edit-permission" href="#"
                                           data-edit-permission-url="<?php echo e(route('admin.permissions.edit', $permission)); ?>">
                                            <i class="fas fa-edit me-2"></i> Modifier
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item delete-permission" href="#"
                                           data-url="<?php echo e(route('admin.permissions.destroy', $permission)); ?>">
                                            <i class="fas fa-trash me-2"></i> Supprimer
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-folder-open text-muted fa-2x mb-2"></i>
                                <p class="text-muted">Aucune permission trouvée</p>
                            </div>
                        </td>
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

    .dropdown-toggle {
        padding: 0.25rem 0.5rem;
    }


    /* Modifications pour corriger le problème d'affichage du dropdown */
    .dropdown-role-actions {
        position: relative;
    }
    .dropdown-menu-end {
        right: 0;
        left: auto !important;
    }
    .table-responsive {
        overflow: visible !important;
    }

</style>
<script>
    // Script pour la recherche en temps réel
    $(document).ready(function() {
        $("#permissionSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#permissions-table tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
<?php /**PATH D:\apprendre laravel\platformeEls\resources\views/admin/permission/index.blade.php ENDPATH**/ ?>