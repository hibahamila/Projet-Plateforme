
<div class="card shadow-sm">
    <div class="card-header bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold"><i class="fas fa-user-shield me-2"></i>Modifier le Rôle</h5>

        </div>
    </div>
    <div class="card-body p-4">
        <form id="edit-role-form" action="{{ route('admin.roles.update', $role) }}" class="needs-validation" method="POST" novalidate>
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-12 mb-4">
                    <label for="roleName" class="form-label fw-medium">Nom du rôle</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-tag"></i></span>
                        <input class="form-control" type="text" id="roleName" name="name" required value="{{ $role->name }}" autocomplete="off" placeholder="Entrez le nom du rôle">
                        <div class="invalid-feedback">
                            Veuillez saisir un nom pour ce rôle.
                        </div>
                    </div>

                </div>

                <!-- Section Permissions avec accordéon -->
                <div class="col-xl-12">
                    <div class="accordion custom-accordion" id="permissionsAccordion">
                        <!-- Section Assigner des Permissions -->
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#assignPermissionsSection">
                                    <i class="fas fa-plus-circle text-success me-2"></i>
                                    <span class="fw-medium">Ajouter des permissions</span>
                                </button>
                            </h2>
                            <div id="assignPermissionsSection" class="accordion-collapse collapse show" data-bs-parent="#permissionsAccordion">
                                <div class="accordion-body bg-light-subtle">
                                    <div class="mb-3">
                                        <label class="form-label text-secondary mb-2"><i class="fas fa-key me-1"></i>Sélectionnez les permissions à ajouter</label>
                                        <select class="js-example-basic-multiple col-sm-12" name="assign_permissions[]" multiple="multiple" data-placeholder="Choisissez une ou plusieurs permissions">
                                            @foreach ($permissions as $permission)
                                                <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text mt-2">
                                            Ces permissions seront ajoutées au rôle sélectionné.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section Supprimer des Permissions -->
                        <div class="accordion-item border-0 shadow-sm">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#removePermissionsSection">
                                    <i class="fas fa-minus-circle text-danger me-2"></i>
                                    <span class="fw-medium">Retirer des permissions</span>
                                </button>
                            </h2>
                            <div id="removePermissionsSection" class="accordion-collapse collapse" data-bs-parent="#permissionsAccordion">
                                <div class="accordion-body bg-light-subtle">
                                    <div class="mb-3">
                                        @if($role->permissions->isNotEmpty())
                                            <label class="form-label text-secondary mb-2"><i class="fas fa-trash-alt me-1"></i>Sélectionnez les permissions à retirer</label>
                                            <select class="js-example-basic-multiple col-sm-12" name="remove_permissions[]" multiple="multiple" data-placeholder="Sélectionnez les permissions à retirer">
                                                @foreach ($role->permissions as $permission)
                                                    <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="form-text mt-2 text-danger">
                                                <i class="fas fa-exclamation-triangle me-1"></i> Ces permissions seront retirées du rôle.
                                            </div>
                                        @else
                                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                                <i class="fas fa-info-circle me-2 fs-5"></i>
                                                <div>Aucune permission n'est actuellement assignée à ce rôle.</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4 pt-2 border-top">
                <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">
                    <i class="fas fa-times-circle me-2"></i>Annuler
                </button>
                <button type="submit" class="btn btn-primary px-4 py-2">
                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .custom-accordion .accordion-button:not(.collapsed) {
        color: #4361ee;
        background-color: rgba(67, 97, 238, 0.05);
        box-shadow: none;
    }

    .custom-accordion .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(67, 97, 238, 0.2);
    }

    .bg-gradient-to-r {
        background: linear-gradient(to right, #4361ee, #3f51b5);
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #4361ee;
        border-color: #3f51b5;
        color: #fff;
        padding: 2px 10px;
    }



    /* Changer la couleur du texte dans les boutons d'accordéon collapsed */
    .accordion-button.collapsed {
        color: #495057 !important; /* Couleur sombre pour meilleure visibilité */
    }

    /* Style supplémentaire pour s'assurer que les éléments d'accordéon sont bien visibles */
    .accordion-button {
        background-color: #f8f9fa !important;
        color: #495057 !important;
    }

    .accordion-button:hover {
        background-color: #e9ecef !important;
    }
</style>
