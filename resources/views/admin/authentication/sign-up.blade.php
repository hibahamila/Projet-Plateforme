@extends('admin.authentication.master')

@section('title') S'inscrire
    {{ $title }}
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.css') }}">
@endpush

@section('content')

    <section>
	    <div class="container-fluid p-0">
	        <div class="row m-0">
	            <div class="col-12 p-0">
	                <div class="login-card">
	                    <form class="theme-form login-form needs-validation" method="POST" action="{{ route('register') }}" novalidate>
                            @csrf
	                        <h4>Créer un compte</h4>
	                        <h6>Entrez vos informations personnelles pour créer un compte</h6>
	                        <div class="form-group">
	                            <label>Votre Nom</label>
	                            <div class="small-group">
	                                <div class="input-group">
	                                    <span class="input-group-text"><i class="icon-user"></i></span>
	                                    <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" required placeholder="Prénom" />
                                        <div class="invalid-feedback js-error">Veuillez entrer un prénom.</div>
                                        @error('name')
	                                        <div class="invalid-feedback laravel-error" style="display: block;">{{ $message }}</div>
	                                    @enderror
	                                </div>
	                                <div class="input-group">
	                                    <span class="input-group-text"><i class="icon-user"></i></span>
	                                    <input class="form-control @error('lastname') is-invalid @enderror" name="lastname" type="text" required placeholder="Nom de famille" />
                                        <div class="invalid-feedback js-error">Veuillez entrer un nom.</div>
                                        @error('lastname')
	                                        <div class="invalid-feedback laravel-error" style="display: block;">{{ $message }}</div>
	                                    @enderror
	                                </div>
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label>Adresse Email</label>
	                            <div class="input-group">
	                                <span class="input-group-text"><i class="icon-email"></i></span>
	                                <input class="form-control @error('email') is-invalid @enderror"  name="email" type="email" required placeholder="exemple@gmail.com" />
                                    <div class="invalid-feedback js-error">Veuillez entrer une adresse email valide.</div>
                                    @error('email')
	                                    <div class="invalid-feedback laravel-error" style="display: block;">{{ $message }}</div>
	                                @enderror
	                            </div>
	                        </div>

	                        <!-- Champ téléphone ajouté -->
	                        <div class="form-group">
	                            <label>Numéro de Téléphone</label>
	                            <div class="input-group">
	                                <span class="input-group-text"><i class="fa fa-phone"></i></span>
	                                <input class="form-control @error('phone') is-invalid @enderror" type="text" name="phone" required placeholder="+216 12 345 678" />
                                    <div class="invalid-feedback js-error">
                                        Veuillez entrer un numéro de téléphone valide.
                                    </div>
                                    @error('phone')
	                                    <div class="invalid-feedback laravel-error" style="display: block;">{{ $message }}</div>
	                                @enderror
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label>Mot de passe</label>
	                            <div class="input-group">
	                                <span class="input-group-text"><i class="icon-lock"></i></span>
	                                <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" required placeholder="*********" />
	                                <div class="show-hide"><span class="show"> </span></div>
                                    <div class="invalid-feedback js-error">Le mot de passe doit contenir au moins 8 caractères.</div>
                                    @error('password')
	                                    <div class="invalid-feedback laravel-error" style="display: block;">{{ $message }}</div>
	                                @enderror
	                            </div>
	                        </div>

                            <div class="form-group">
                                <div class="checkbox">
                                    <input id="checkbox1" type="checkbox" name="privacy_policy" value="1" required />
                                    <label class="text-muted" for="checkbox1">J'accepte la <span>Politique de confidentialité</span></label>
                                    <div class="invalid-feedback js-error">Veuillez accepter la Politique de confidentialité.</div>
                                    @error('privacy_policy')
                                        <div class="invalid-feedback laravel-error" style="display: block;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

	                        <div class="form-group">
	                            <button class="btn btn-primary btn-block" type="submit">Créer un compte</button>
	                        </div>

	                        <div class="login-social-title">
	                            <h5>Ou inscrivez-vous avec</h5>
	                        </div>

	                        <div class="form-group">
	                            <ul class="login-social">
	                                <li>
	                                    <a href="https://www.linkedin.com/login" target="_blank"><i data-feather="linkedin"></i></a>
	                                </li>
	                                <li>
	                                    <a href="https://www.linkedin.com/login" target="_blank"><i data-feather="twitter"></i></a>
	                                </li>
	                                <li>
	                                    <a href="https://www.linkedin.com/login" target="_blank"><i data-feather="facebook"></i></a>
	                                </li>
	                                <li>
	                                    <a href="https://www.instagram.com/login" target="_blank"><i data-feather="instagram"> </i></a>
	                                </li>
	                            </ul>
	                        </div>

	                        <p>Vous avez déjà un compte ? <a class="ms-2" href="{{ route('login') }}">Se connecter</a></p>
	                    </form>
	                </div>
	            </div>
	        </div>
	    </div>
	</section>

@push('scripts')
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>

    <script src="{{ asset('assets/js/form-validation/form_validation2.js') }}"></script>
@endpush

@endsection
