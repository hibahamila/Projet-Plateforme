@extends('admin.authentication.master')

    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/verifyCode_password.css') }}">
    @endpush
@section('content')
@if (session('error'))
    <div class="alert alert-danger mb-4 shadow-sm fade-in">
        <div class="d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-circle-fill me-2" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
            </svg>
            {{ session('error') }}
        </div>
    </div>
@endif

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow border-0 rounded-lg">

                <div class="card-header text-white text-center py-4" style="background-color: #2B6ED4 !important;">
                    <h2 class="font-weight-bold mb-0">Réinitialisation du mot de passe</h2>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <!-- Modification de la couleur du SVG pour correspondre à #2B6ED4 -->
                        <div class="mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#2B6ED4" viewBox="0 0 16 16">
                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383-4.708 2.825L15 11.105V5.383zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741zM1 11.105l4.708-2.897L1 5.383v5.722z"/>
                            </svg>
                        </div>
                        <h4 class="font-weight-bold">Vérification du code</h4>
                        <p class="text-muted">Un code a été envoyé à votre adresse email. Veuillez le saisir ci-dessous pour continuer.</p>
                    </div>

                    <form method="POST" action="{{ route('reset.password.verify') }}" class=" needs-validation" novalidate>
                        @csrf
                        <div class="form-group mb-4">
                            <label for="verification-code" class="form-label fw-bold">Code de vérification</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                                    </svg>
                                </span>
                                <input type="text" id="verification-code" name="code" class="form-control @error('code') is-invalid @enderror form-control-lg" placeholder="Entrez le code à 6 chiffres" required>

                            </div>
                            <div class="invalid-feedback js-error">Nous avons envoyé un code à votre adresse e-mail. Veuillez le saisir pour continuer.</div>
                            @error('code')
	                            <div class="invalid-feedback laravel-error" style="display: block;">{{ $message }}</div>
	                        @enderror
                        </div>

                        <div class="d-grid gap-2 mt-4">
                           
                            <button type="submit" class="btn btn-lg verify-btn" style="background-color: #2B6ED4 !important; border-color: #2B6ED4 !important; color: #ffffff !important;">Vérifier le code</button>
                            <a href="{{ route('login') }}" class="btn btn-square btn-outline-info">Retour à la connexion</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script src="{{ asset('assets/js/form-validation/form_validation2.js') }}"></script>
@endpush
@endsection

