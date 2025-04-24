
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
                            @foreach($allRoles as $role)
                                <option value="{{ $role->name }}" {{ isset($selectedRole) && $selectedRole == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                        <select class="form-select filter-select" id="status-filter" aria-label="Filtrer par statut">
                            <option value="">Tous les statuts</option>
                            <option value="active" {{ isset($selectedStatus) && $selectedStatus == 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="inactive" {{ isset($selectedStatus) && $selectedStatus == 'inactive' ? 'selected' : '' }}>Inactif</option>
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
                    @if(isset($selectedRole) && $selectedRole)
                        <span class="filter-badge">Rôle: {{ $selectedRole }}</span>
                    @endif
                    @if(isset($selectedStatus) && $selectedStatus)
                        <span class="filter-badge">Statut: {{ $selectedStatus == 'active' ? 'Actif' : 'Inactif' }}</span>
                    @endif
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
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->name }} {{ $user->lastname ?? '' }}</td>
                        <td>{{ $user->email }}</td>
                        <td>

                            @forelse($user->roles as $role)
                                @switch(strtolower($role->name))
                                    @case('admin')
                                        <span class="badge bg-danger">{{ $role->name }}</span>
                                        @break
                                    @case('professeur')
                                        <span class="badge bg-primary">{{ $role->name }}</span>
                                        @break
                                    @case('etudiant')
                                        <span class="badge bg-info">{{ $role->name }}</span>
                                        @break
                                    @default
                                        <span class="badge bg-success">{{ $role->name }}</span>
                                @endswitch
                            @empty
                                <span class="badge bg-secondary">Aucun rôle</span>
                            @endforelse
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input toggle-status-switch" type="checkbox"
                                       data-url="{{ route('admin.users.toggleStatus', $user) }}"
                                       id="status-{{ $user->id }}" {{ $user->status === 'active' ? 'checked' : '' }}>

                            </div>
                        </td>
                        <td>
                            <div class="dropdown dropdown-user-actions">
                                <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                        id="dropdownMenuButton-{{ $user->id }}" data-bs-toggle="dropdown"
                                        aria-expanded="false" aria-label="Actions pour {{ $user->name }}">
                                    <i class="fas fa-ellipsis-h" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton-{{ $user->id }}">
                                    <li>
                                        <a class="dropdown-item view-user-roles" href="javascript:void(0)"
                                           data-url="{{ route('admin.users.roles', $user) }}">
                                            <i class="fa fa-info-circle" aria-hidden="true"></i> Plus d'info
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item delete-user" href="javascript:void(0)"
                                           data-url="{{ route('admin.users.destroy', $user) }}">
                                            <i class="fas fa-trash me-2" aria-hidden="true"></i> Supprimer
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Aucun utilisateur trouvé</td>
                    </tr>
                    @endforelse
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
