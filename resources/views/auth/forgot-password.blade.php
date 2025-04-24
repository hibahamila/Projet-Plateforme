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
                    <h2 class="font-weight-bold mb-0">Récupération de compte</h2>
                </div>

                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#2B6ED4" viewBox="0 0 16 16">
                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383-4.708 2.825L15 11.105V5.383zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741zM1 11.105l4.708-2.897L1 5.383v5.722z"/>
                            </svg>
                        </div>
                        <h4 class="font-weight-bold">Récupération de compte</h4>
                        <p class="text-muted">Veuillez saisir votre adresse e-mail afin de recevoir un code de réinitialisation de votre mot de passe</p>
                    </div>

                    <form method="POST" action="{{ route('forgot.password.send') }}" class="needs-validation" novalidate>
                        @csrf
                        <div class="form-group mb-4">
                            <label for="email" class="form-label fw-bold">Adresse Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383-4.708 2.825L15 11.105V5.383zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741zM1 11.105l4.708-2.897L1 5.383v5.722z"/>
                                    </svg>
                                </span>
                                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror form-control-lg" placeholder="nom@exemple.com" required>
                            </div>
                            <div class="invalid-feedback js-error">Veuillez fournir une adresse email valide.</div>
                            @error('email')
                                <div class="invalid-feedback laravel-error" style="display: block;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-lg text-white d-flex align-items-center justify-content-center" style="background-color: #2B6ED4 !important; border-color: #2B6ED4 !important;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send me-2" viewBox="0 0 16 16">
                                    <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z"/>
                                </svg>
                                Envoyer le code
                            </button>
                            <a href="{{ route('login') }}" class="btn btn-square btn-outline-info" btn-lg d-flex align-items-center justify-content-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left me-2" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                                </svg>
                                Retour à la connexion
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn:hover {
        opacity: 0.9;
    }
    .card {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
    }
    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
    }
    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        color: #2B6ED4;
    }
</style>

@push('scripts')
    <script src="{{ asset('assets/js/form-validation/form_validation2.js') }}"></script>
@endpush
@endsection
