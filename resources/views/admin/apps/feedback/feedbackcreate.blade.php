

@extends('layouts.admin.master')

@section('title') Ajouter un Feedback @endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .star-rating {
            display: flex;
            flex-direction: row-reverse; 
            justify-content: flex-end;
            gap: 0;
        }
        .star-container {
            position: relative;
            width: 32px;
            height: 32px;
            cursor: pointer;
        }
        .star-rating .fa {
            font-size: 2rem;
            color: #ccc; 
            position: absolute;
            top: 0;
            left: 0;
        }
        .star-rating .fa.fa-star-half-o,
        .star-rating .fa.fa-star {
            color: black; 
        }
        .star-half-left, .star-half-right {
            position: absolute;
            width: 16px;
            height: 32px;
            top: 0;
            z-index: 10;
        }
        .star-half-left { left: 0; }
        .star-half-right { right: 0; }
        .current-rating {
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Ajouter un Feedback</h3>
        @endslot
        <li class="breadcrumb-item">Feedback</li>
        <li class="breadcrumb-item active">Ajouter</li>
    @endcomponent

    <div class="container">
        <h2>Ajouter un Feedback</h2>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('feedbackstore') }}" method="POST" class="form theme-form needs-validation" novalidate>
            @csrf

            <div class="form-group">
                <label for="formation_id">Formation</label>
                <select name="formation_id" id="formation_id" class="form-control" required>
                    <option value="">-- Sélectionnez une formation --</option>
                    @foreach($formations as $formation)
                        <option value="{{ $formation->id }}" {{ old('formation_id') == $formation->id ? 'selected' : '' }}>
                            {{ $formation->title }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">Veuillez sélectionner une formation.</div>
            </div>

            <div class="form-group mt-3">
                <label for="rating_count">Note</label>
                <div class="star-rating">
                    <div class="star-container" data-value="5">
                        <div class="star-half-right" data-value="5.0"></div>
                        <div class="star-half-left" data-value="4.5"></div>
                        <i class="fa fa-star-o"></i>
                    </div>
                    <div class="star-container" data-value="4">
                        <div class="star-half-right" data-value="4.0"></div>
                        <div class="star-half-left" data-value="3.5"></div>
                        <i class="fa fa-star-o"></i>
                    </div>
                    <div class="star-container" data-value="3">
                        <div class="star-half-right" data-value="3.0"></div>
                        <div class="star-half-left" data-value="2.5"></div>
                        <i class="fa fa-star-o"></i>
                    </div>
                    <div class="star-container" data-value="2">
                        <div class="star-half-right" data-value="2.0"></div>
                        <div class="star-half-left" data-value="1.5"></div>
                        <i class="fa fa-star-o"></i>
                    </div>
                    <div class="star-container" data-value="1">
                        <div class="star-half-right" data-value="1.0"></div>
                        <div class="star-half-left" data-value="0.5"></div>
                        <i class="fa fa-star-o"></i>
                    </div>
                </div>
                <div class="current-rating">Note: <span id="rating-value"></span>/5</div>
                <input type="hidden" name="rating_count" id="rating_count" >
            </div>

            <button type="submit" class="btn btn-success mt-3">Soumettre</button>
            <a href="{{ route('feedbacks') }}" class="btn btn-secondary mt-3">Annuler</a>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/MonJs/feedback/feedback.js') }}"></script>
@endpush 
  

