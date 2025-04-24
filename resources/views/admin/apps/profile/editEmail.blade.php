
<div class="row">
    <div class="col-sm-12">
        <div class="mb-3">

        </div>
        <form id="email-form" class="ajax-form" action="{{ route('profile.sendEmailVerificationCode') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label class="form-label">Email actuel</label>
                <input class="form-control" type="email" value="{{ $user->email }}" disabled>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Nouvel Email</label>
                <input class="form-control" type="email" name="email" id="new-email" required>
                <small class="form-text text-muted">Un code de validation sera envoyé à cette adresse.</small>
            </div>
            <div class="form-group mb-3 d-flex gap-2">
                <button class="btn btn-outline-secondary back-btn" type="button" data-back-tab="account">
                    <i class="fa fa-arrow-left"></i> Retour
                </button>
                <button class="btn btn-primary" type="button" id="send-code-btn">Envoyer le code de validation</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal pour vérification du mot de passe -->
<div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordModalLabel">Vérification du mot de passe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning mb-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Attention :</strong> Vous avez 3 tentatives pour saisir votre mot de passe correctement.
                    Au-delà, votre compte sera  bloqué et vous devrez contacter l'administrateur.
                </div>
                <div id="modal-alert-container"></div>
                <div class="form-group mb-3">
                    <label class="form-label">Veuillez entrer votre mot de passe actuel</label>
                    <input type="password" class="form-control" id="current-password" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="verify-password-btn">Vérifier</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>

            </div>
        </div>
    </div>
</div>

