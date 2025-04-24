{{-- @extends('layouts.admin.master')

@section('title') Modifier un Feedback @endsection

@push('css')
    <!-- Inclusion de FontAwesome pour les étoiles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .star-rating {
            display: flex;
            flex-direction: row-reverse; /* Permet de placer l'étoile la plus haute à gauche */
            justify-content: flex-end;
            gap: 5px;
        }
        .star-rating .fa {
            font-size: 2rem;
            cursor: pointer;
            color: #ccc; /* Couleur par défaut pour les étoiles vides */
        }
        .star-rating .fa.filled {
            color: black; /* Couleur des étoiles remplies */
        }
    </style>
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Modifier un Feedback</h3>
        @endslot
        <li class="breadcrumb-item">Feedback</li>
        <li class="breadcrumb-item active">Modifier</li>
    @endcomponent

    <div class="container">
        <h2>Modifier un Feedback</h2>

        <!-- Affichage des erreurs de validation -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulaire de mise à jour -->
        <form action="{{ route('feedbackupdate', $feedback->id) }}" method="POST" class="form theme-form needs-validation" novalidate>
            @csrf
            @method('PUT') <!-- Utilisation de la méthode PUT pour la mise à jour -->

            <!-- Sélection de la formation -->
            <div class="form-group">
                <label for="formation_id">Formation</label>
                <select name="formation_id" id="formation_id" class="form-control" required>
                    <option value="">-- Sélectionnez une formation --</option>
                    @foreach($formations as $formation)
                        <option value="{{ $formation->id }}" {{ old('formation_id', $feedback->formation_id) == $formation->id ? 'selected' : '' }}>
                            {{ $formation->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Système de notation par étoiles -->
            <div class="form-group mt-2">
                <label for="rating">Note</label>
                <div class="star-rating">
                    <!-- L'ordre en row-reverse permet de cliquer sur l'étoile correspondant à la note maximale -->
                    <i class="fa fa-star-o" data-value="5" title="5 étoiles"></i>
                    <i class="fa fa-star-o" data-value="4" title="4 étoiles"></i>
                    <i class="fa fa-star-o" data-value="3" title="3 étoiles"></i>
                    <i class="fa fa-star-o" data-value="2" title="2 étoiles"></i>
                    <i class="fa fa-star-o" data-value="1" title="1 étoile"></i>
                </div>
                <!-- Champ caché pour stocker la note. Valeur par défaut 1 -->
                <input type="hidden" name="rating" id="rating" value="{{ old('rating', $feedback->rating_cout) }}">
            </div>

            <!-- Boutons -->
            <button type="submit" class="btn btn-success mt-2">Mettre à jour</button>
            <a href="{{ route('feedbacks') }}" class="btn btn-secondary mt-2">Annuler</a>
        </form>
    </div>
@endsection

@push('scripts')
    <!-- Script de gestion de la notation par étoiles -->
    <script>
        $(document).ready(function(){
            // Initialiser les étoiles en fonction de la note existante
            var initialRating = {{ $feedback->rating_cout }};
            $('#rating').val(initialRating);

            $('.star-rating .fa').each(function(){
                if ($(this).data('value') <= initialRating) {
                    $(this).removeClass('fa-star-o').addClass('fa-star filled');
                }
            });

            // Gestion du clic sur les étoiles
            $('.star-rating .fa').on('click', function(){
                var rating = $(this).data('value'); // Récupère la valeur de l'étoile cliquée
                $('#rating').val(rating); // Met à jour le champ caché

                // Met à jour l'affichage des étoiles
                $('.star-rating .fa').each(function(){
                    if ($(this).data('value') <= rating) {
                        $(this).removeClass('fa-star-o').addClass('fa-star filled');
                    } else {
                        $(this).removeClass('fa-star filled').addClass('fa-star-o');
                    }
                });
            });
        });
    </script>

    <!-- Script de validation côté client avec Bootstrap -->
    <script>
        (function () {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
@endpush --}}






{{-- 


@extends('layouts.admin.master')

@section('title') Modifier un Feedback @endsection

@push('css')
    <!-- Inclusion de FontAwesome pour les étoiles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .star-rating {
            display: flex;
            flex-direction: row-reverse; /* Permet de placer l'étoile la plus haute à gauche */
            justify-content: flex-end;
            gap: 5px;
        }
        .star-rating .fa {
            font-size: 2rem;
            cursor: pointer;
            color: #ccc; /* Couleur par défaut pour les étoiles vides */
        }
        .star-rating .fa.filled {
            color: black; /* Couleur des étoiles remplies */
        }
    </style>
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Modifier un Feedback</h3>
        @endslot
        <li class="breadcrumb-item">Feedback</li>
        <li class="breadcrumb-item active">Modifier</li>
    @endcomponent

    <div class="container">
        <h2>Modifier un Feedback</h2>

        <!-- Affichage des erreurs de validation -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulaire de mise à jour -->
        <form action="{{ route('feedbackupdate', $feedback->id) }}" method="POST" class="form theme-form needs-validation" novalidate>
            @csrf
            @method('PUT') <!-- Utilisation de la méthode PUT pour la mise à jour -->

            <!-- Sélection de la formation -->
            <div class="form-group">
                <label for="formation_id">Formation</label>
                <select name="formation_id" id="formation_id" class="form-control" required>
                    <option value="">-- Sélectionnez une formation --</option>
                    @foreach($formations as $formation)
                        <option value="{{ $formation->id }}" {{ old('formation_id', $feedback->formation_id) == $formation->id ? 'selected' : '' }}>
                            {{ $formation->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Système de notation par étoiles -->
            <div class="form-group mt-2">
                <label for="rating">Note</label>
                <div class="star-rating">
                    <!-- L'ordre en row-reverse permet de cliquer sur l'étoile correspondant à la note maximale -->
                    <i class="fa fa-star-o" data-value="5" title="5 étoiles"></i>
                    <i class="fa fa-star-o" data-value="4" title="4 étoiles"></i>
                    <i class="fa fa-star-o" data-value="3" title="3 étoiles"></i>
                    <i class="fa fa-star-o" data-value="2" title="2 étoiles"></i>
                    <i class="fa fa-star-o" data-value="1" title="1 étoile"></i>
                </div>
                <!-- Champ caché pour stocker la note. Valeur par défaut 0 si la note est vide -->
                <input type="hidden" name="rating" id="rating" value="{{ old('rating', $feedback->rating_cout ?? 0) }}">
            </div>

            <!-- Boutons -->
            <button type="submit" class="btn btn-success mt-2">Mettre à jour</button>
            <a href="{{ route('feedbacks') }}" class="btn btn-secondary mt-2">Annuler</a>
        </form>
    </div>
@endsection

@push('scripts')
    <!-- Script de gestion de la notation par étoiles -->
    <script>
        $(document).ready(function(){
            // Initialiser les étoiles en fonction de la note existante
            var initialRating = {{ $feedback->rating_cout ?? 0 }}; // Définit la note à 0 si elle est nulle
            $('#rating').val(initialRating);

            $('.star-rating .fa').each(function(){
                if ($(this).data('value') <= initialRating) {
                    $(this).removeClass('fa-star-o').addClass('fa-star filled');
                } else {
                    $(this).removeClass('fa-star filled').addClass('fa-star-o');
                }
            });

            // Gestion du clic sur les étoiles
            $('.star-rating .fa').on('click', function(){
                var rating = $(this).data('value'); // Récupère la valeur de l'étoile cliquée
                $('#rating').val(rating); // Met à jour le champ caché

                // Met à jour l'affichage des étoiles
                $('.star-rating .fa').each(function(){
                    if ($(this).data('value') <= rating) {
                        $(this).removeClass('fa-star-o').addClass('fa-star filled');
                    } else {
                        $(this).removeClass('fa-star filled').addClass('fa-star-o');
                    }
                });
            });
        });
    </script>

    <!-- Script de validation côté client avec Bootstrap -->
    <script>
        (function () {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
@endpush --}}
