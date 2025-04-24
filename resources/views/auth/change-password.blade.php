@extends('layouts.admin.master')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ __('Modifier votre mot de passe') }}</h4>
                </div>

                <div class="card-body p-4">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <p class="text-muted mb-4">Pour sécuriser votre compte, veuillez définir un nouveau mot de passe. Utilisez un mot de passe fort avec au moins 8 caractères, incluant des chiffres et des caractères spéciaux.</p>

                    <!-- Formulaire de changement de mot de passe -->
                    <form method="POST" action="{{ route('password.change') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Nouveau mot de passe') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirmer le mot de passe') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <div class="col-md-8 offset-md-4 d-flex">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-lock mr-2"></i>{{ __('Modifier le mot de passe') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <form method="POST" action="{{ route('password.skip') }}">
                            @csrf
                            <p class="text-muted mb-3">Vous pourrez toujours modifier votre mot de passe ultérieurement depuis les paramètres de votre compte.</p>
                            <button type="submit" class="btn btn-outline-secondary">
                                {{ __('Ignorer pour le moment') }} <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 10px;
    border: none;
}

.card-header {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

.btn-primary {
    border-radius: 5px;
    padding: 10px 20px;
    transition: all 0.3s;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.btn-outline-secondary {
    border-radius: 5px;
    padding: 8px 16px;
    transition: all 0.3s;
}

.btn-outline-secondary:hover {
    background-color: #f8f9fa;
}

.form-control {
    border-radius: 5px;
    padding: 10px 15px;
    height: auto;
}

input[type="password"]:focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    border-color: #80bdff;
}

hr {
    border-top: 1px solid rgba(0, 0, 0, 0.1);
}
</style>
@endsection
