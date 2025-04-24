
<div class="container mt-4" id="roles_Permission">
    <div class="card shadow">
        <div class="card-header  bg-primary text-white d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <button class="btn btn-light me-3 back-btn" data-back-tab="users">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <h5 class="mb-0">Plus d'informations sur {{ $user->name }}</h5>
            </div>
        </div>
        <div class="card-body">
           
            <div class="user-info mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0"><i class="fas fa-user me-2"></i>Informations de l'utilisateur</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="info-item">
                                    <span class="info-label">Nom</span>
                                    <span class="info-value">{{ $user->name }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-item">
                                    <span class="info-label">Prénom</span>
                                    <span class="info-value">{{ $user->lastname }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-item">
                                    <span class="info-label">Email</span>
                                    <span class="info-value">{{ $user->email }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Rôles attribués -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0"><i class="fas fa-user-tag me-2"></i>Rôles attribués</h6>
                        </div>
                        <div class="card-body p-0">
                            @if ($user->roles->isNotEmpty())
                                <ul class="list-group list-group-flush">
                                    @foreach($user->roles as $role)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="role-name">{{ $role->name }}</span>

                                    </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-center p-4">
                                    <i class="fas fa-info-circle text-muted mb-2 fa-2x"></i>
                                    <p class="text-muted mb-0">Aucun rôle assigné</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Toutes les permissions -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0"><i class="fas fa-key me-2"></i>Toutes les permissions</h6>
                        </div>
                        <div class="card-body p-0">
                            @if ($user->permissions->isNotEmpty() || $user->roles->isNotEmpty())
                                <ul class="list-group list-group-flush">
                                    @foreach($user->permissions as $user_permission)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="permission-item">
                                            <span>{{ $user_permission->name }}</span>
                                        </div>
                                    </li>
                                    @endforeach

                                    @foreach ($user->roles as $role)
                                        @foreach ($role->permissions as $permission)
                                            @if (!$user->permissions->contains($permission))
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div class="permission-item">
                                                    <span>{{ $permission->name }}</span>
                                                </div>
                                            </li>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-center p-4">
                                    <i class="fas fa-info-circle text-muted mb-2 fa-2x"></i>
                                    <p class="text-muted mb-0">Aucune permission</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styles pour améliorer l'apparence */
    #roles_Permission .card {
        border-radius: 8px;
        border: none;
    }

    #roles_Permission .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.1);
        padding: 15px 20px;
    }

    #roles_Permission .back-btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.9rem;
        border-radius: 6px;
        font-weight: 500;
        color: #4d5483;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    #roles_Permission .back-btn:hover {
        background-color: #f8f9fa;
        color: #3a3f63;
    }

    /* Style pour les informations utilisateur */
    .info-item {
        display: flex;
        flex-direction: column;
        margin-bottom: 5px;
    }

    .info-label {
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 3px;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 500;
        color: #212529;
    }

    /* Style pour les listes */
    .list-group-item {
        padding: 12px 20px;
        border-left: none;
        border-right: none;
        transition: background-color 0.2s;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .role-name {
        font-weight: 500;
    }

    .permission-item {
        display: flex;
        align-items: center;
    }

    /* Style pour le bouton supprimer */
    .btn-outline-danger {
        border-width: 1px;
        padding: 0.25rem 0.5rem;
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: white;
    }

    /* Style pour les headers des cartes */
    .card-header.bg-primary {
        background: linear-gradient(45deg, #4e73df, #224abe) !important;
    }

    .card-header.bg-info {
        background: linear-gradient(45deg, #36b9cc, #258391) !important;
    }

    .card-header.bg-secondary {
        background: #a9aab1 !important;
    }


    .card-header.bg-indigo {
        background: linear-gradient(45deg, #6777ef, #4d5483) !important;
    }
</style>
