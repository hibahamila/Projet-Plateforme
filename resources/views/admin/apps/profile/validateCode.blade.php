
<div class="row">
    <div class="col-sm-12">
        <div class="mb-3">

        </div>

        <div class="alert alert-info mb-4">
            <p class="mb-0">Un code de validation a été envoyé à votre nouvelle adresse email. Veuillez entrer ce code pour confirmer la modification de votre email.</p>
        </div>

        <form class="ajax-form" action="{{ route('profile.verifyEmail') }}" method="POST" data-reload-tab="account">
            @csrf

            <div class="form-group mb-3">
                <label class="form-label">Code de validation</label>
                <input class="form-control" type="text" name="verification_code" placeholder="Entrez le code à 6 chiffres" required maxlength="6">
            </div>

            <div class="form-group mb-3 d-flex gap-2">
                
                <button class="btn btn-primary" type="submit">Vérifier et mettre à jour</button>

            </div>
        </form>
    </div>
</div>
