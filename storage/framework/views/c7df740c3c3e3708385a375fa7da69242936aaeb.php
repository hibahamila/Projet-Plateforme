

<div class="card">

    <div class="card-header bg-primary text-white py-3">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-white p-2 me-3">
                <i class="fas fa-user-tag text-primary fa-lg"></i>
            </div>
                    <h3 class="fw-bold mb-0">Gestion des Rôles</h3>
            </div>
        </div>
    </div>





    <div class="row mb-4 px-3 mt-3">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center py-2">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                        <i class="fas fa-users-cog text-white"></i>
                    </div>
                    <div>
                        <h5 class="text-muted mb-0 small">Total des rôles : <strong><?php echo e(count($roles)); ?></strong></h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bouton Nouveau Rôle déplacé ici à droite -->
        <div class="col-md-7 d-flex justify-content-end align-items-center">
            <button class="btn btn-primary d-flex align-items-center shadow-sm" id="loadCreateRoleForm"
                    data-create-url="<?php echo e(route('admin.roles.create')); ?>"
                    data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fas fa-plus-circle me-2"></i> Nouveau Rôle
            </button>
        </div>
    </div>

    <!-- Tableau des rôles avec design amélioré -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">Liste des Rôles</h5>
                <div class="input-group" style="width: 250px;">
                    <span class="input-group-text bg-light border-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-0 bg-light" placeholder="Rechercher un rôle..." id="roleSearch">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="roles-table" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-bold">Nom du rôle</th>
                            <th class="fw-bold">Utilisateurs</th>
                            <th class="fw-bold text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php switch(strtolower($role->name)):
                                        case ('admin'): ?>
                                            <div class="rounded-circle bg-danger bg-opacity-10 p-2 me-3">
                                                <i class="fas fa-user-shield text-white"></i>
                                            </div>
                                            <?php break; ?>
                                        <?php case ('professeur'): ?>
                                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                                <i class="fas fa-chalkboard-teacher text-white"></i>
                                            </div>
                                            <?php break; ?>
                                        <?php case ('etudiant'): ?>
                                            <div class="rounded-circle bg-info bg-opacity-10 p-2 me-3">
                                                <i class="fas fa-user-graduate text-white"></i>
                                            </div>
                                            <?php break; ?>
                                        <?php default: ?>
                                            <div class="rounded-circle  bg-success bg-opacity-10 p-2 me-3">
                                                <i class="fas fa-user-tag text-white"></i>
                                            </div>
                                    <?php endswitch; ?>
                                    <div>
                                        <h6 class="mb-0 fw-bold"><?php echo e($role->name); ?></h6>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark border"><?php echo e($role->users->count()); ?> utilisateur(s)</span>
                            </td>
                            <td>
                                <div class="dropdown dropdown-role-actions">
                                    <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                            id="dropdownMenuButton-<?php echo e($role->id); ?>" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fas fa-ellipsis-h" aria-hidden="true"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton-<?php echo e($role->id); ?>">
                                        <li>

                                            <a class="dropdown-item edit-role" href="#"
                                               data-edit-url="<?php echo e(route('admin.roles.edit', $role)); ?>" id="loadEditRoleForm">
                                                <i class="fas fa-edit me-2"></i> Modifier
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item delete-role" href="#"
                                               data-url="<?php echo e(route('admin.roles.destroy', $role)); ?>">
                                                <i class="fas fa-trash me-2"></i> Supprimer
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
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
        $("#roleSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#roles-table tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
<?php /**PATH D:\apprendre laravel\platformeEls\resources\views/admin/role/index.blade.php ENDPATH**/ ?>