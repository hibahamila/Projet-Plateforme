
<form id="create-permission-form" action="{{ route('admin.permissions.store') }}" method="POST" class="needs-validation" novalidate>
    @csrf
    <div class="card shadow border-0 rounded-lg">
        <!-- Header avec un design moderne -->
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-key me-2"></i>Création d'une nouvelle permission
                    </h5>
                    <p class="mb-0 small opacity-75">Définissez une nouvelle permission pour les utilisateurs</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
        </div>
    <div class="card-body p-4">
        <div class="mb-4">
            <label for="permission-name" class="form-label fw-medium">
                <i class="fas fa-key text-primary me-1"></i>
                Nom de la permission
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="fas fa-tag text-muted"></i>
                </span>
                <input type="text"
                       id="permission-name"
                       class="form-control border-start-0"
                       name="name"
                       placeholder="Entrez le nom de la permission..."
                       autocomplete="off"
                       required>
                <div class="invalid-feedback">
                    Veuillez saisir un nom de permission valide.
                </div>
            </div>
            <small class="text-muted mt-1 d-block">
                <i class="fas fa-info-circle me-1"></i>
                Le nom doit être unique et descriptif.
            </small>
        </div>
    </div>



    <div class="card-footer bg-light d-flex justify-content-end py-3">
        <div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-check me-1"></i>
                Créer une permission
            </button>
            <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">
                <i class="fas fa-times me-1"></i>
                Annuler
            </button>

        </div>
    </div>
</form>
