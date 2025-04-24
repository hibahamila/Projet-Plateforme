{{-- 
@extends('layouts.admin.master')

@section('title') Liste des Catégories
{{ $title }}
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/table.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom-style.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/prism.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush

@section('content')
@component('components.breadcrumb')
    @slot('breadcrumb_title')
        <h3>Liste des Catégories</h3>
    @endslot
    <li class="breadcrumb-item">Apps</li>
    <li class="breadcrumb-item active">Liste des Catégories</li>
@endcomponent

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Catégories Disponibles</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger" id="success-message" style="display: none;">
                    </div>

                    @if(session('delete'))
                        <div class="alert alert-danger" id="delete-message">
                            {{ session('delete') }}
                        </div>
                    @endif
                    <div class="row project-cards">
                        <div class="col-md-12 project-list">
                            <div class="card">
                                <div class="row">
                                    <div class="col-md-6 p-0">
                                    </div>
                                    <div class="col-md-6 p-0 text-end">
                                        <a class="btn btn-primary custom-btn" href="{{ route('categoriecreate') }}">
                                            <i data-feather="plus-square"></i> Ajouter une categorie
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="dataTable display" id="categories-table">
                            <thead>
                                <tr>
                                    <th>Nom de la Catégorie</th>
                                </tr>
                            </thead>
                            <tbody>
                                <meta name="csrf-token" content="{{ csrf_token() }}">

                                @foreach($categories as $categorie)
                                    <tr>
                                        <td>
                                            {{ $categorie->title }}
                                            <div class="dropdown float-right">
                                                <button class="btn btn-sm btn-light dropdown-toggle no-caret" type="button" id="actionMenu{{ $categorie->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="actionMenu{{ $categorie->id }}">
                                                    <a class="dropdown-item" href="{{ route('categorieedit', $categorie->id) }}">
                                                        <i class="icofont icofont-ui-edit"></i>
                                                    </a>
                                                    <a class="dropdown-item text-danger delete-action" href="javascript:void(0);" data-delete-url="{{ route('categoriedestroy', $categorie->id) }}" data-type="catégorie" data-name="{{ $categorie->title }}" data-csrf="{{ csrf_token() }}">
                                                        <i class="icofont icofont-ui-delete"></i> 
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('assets/js/dropdown/dropdown.js') }}"></script>
    <script src="{{ asset('assets/js/prism/prism.min.js') }}"></script>
    <script src="{{ asset('assets/js/clipboard/clipboard.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom-card/custom-card.js') }}"></script>
    <script src="{{ asset('assets/js/height-equal.js') }}"></script>
    <script src="{{ asset('assets/js/MonJs/datatables/datatables.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
@endpush
@endsection



 --}}


 @extends('layouts.admin.master')

@section('title') Liste des Catégories
{{ $title }}
@endsection

@push('css')
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/table.css') }}"> --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/prism.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Supprimer la flèche du bouton dropdown */
        .dropdown-toggle.no-caret::after {
            display: none !important;
        }

        /* Styles pour le bouton des trois points */
        .dropdown-toggle.no-caret {
            background: none !important; /* Supprimer l'arrière-plan */
            border: none !important; /* Supprimer la bordure */
            padding: 0 !important; /* Supprimer le padding */
            color: black !important; /* Couleur noire */
        }

        /* Supprimer l'effet de survol (hover) */
        .dropdown-toggle.no-caret:hover,
        .dropdown-toggle.no-caret:focus {
            background: none !important;
            color: black !important;
            box-shadow: none !important;
        }

        /* Désactiver le focus sur le bouton des trois points */
        .dropdown-toggle.no-caret:focus {
            outline: none !important; /* Supprimer le contour de focus */
            box-shadow: none !important; /* Supprimer l'ombre de focus */
            border-color: transparent !important; /* Supprimer la bordure de focus */
        }

        /* Aligner les trois points verticalement */
        .dropdown-toggle.no-caret i.fa-ellipsis-v {
            display: inline-block;
            vertical-align: middle;
            transform: rotate(90deg); /* Rotation pour un effet vertical */
        }

        /* Positionner le menu déroulant en bas */
        .dropdown-menu {
            top: 100% !important;
            bottom: auto !important;
            margin-top: 0.125rem;
        }

        /* Styles pour le tableau */
        #categories-table {
            width: 100% !important;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }

        /* Styles pour l'en-tête du tableau */
        #categories-table thead th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: bold;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
        }

        /* Styles pour les cellules du tableau */
        #categories-table tbody td {
            padding: 10px 15px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }

        /* Styles pour les lignes du tableau */
        #categories-table tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Styles pour le message de succès */
        #success-message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            display: none;
        }

        /* Styles pour le message de suppression */
        #delete-message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
    </style>
@endpush

@section('content')
@component('components.breadcrumb')
    @slot('breadcrumb_title')
        <h3>Liste des Catégories</h3>
    @endslot
    <li class="breadcrumb-item">Apps</li>
    <li class="breadcrumb-item active">Liste des Catégories</li>
@endcomponent

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Catégories Disponibles</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger" id="success-message" style="display: none;">
                    </div>

                    @if(session('delete'))
                        <div class="alert alert-danger" id="delete-message">
                            {{ session('delete') }}
                        </div>
                    @endif
                    <div class="row project-cards">
                        <div class="col-md-12 project-list">
                            <div class="card">
                                <div class="row">
                                    <div class="col-md-6 p-0">
                                    </div>
                                    <div class="col-md-6 p-0 text-end">
                                        <a class="btn btn-primary custom-btn" href="{{ route('categoriecreate') }}">
                                            <i data-feather="plus-square"></i> Ajouter une categorie
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="dataTable display" id="categories-table">
                            <thead>
                                <tr>
                                    <th>Nom de la Catégorie</th>
                                </tr>
                            </thead>
                            <tbody>
                                <meta name="csrf-token" content="{{ csrf_token() }}">

                                @foreach($categories as $categorie)
                                    <tr>
                                        <td>
                                            {{ $categorie->title }}
                                            <div class="dropdown float-right">
                                                <button class="btn btn-sm dropdown-toggle no-caret" type="button" id="actionMenu{{ $categorie->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="actionMenu{{ $categorie->id }}">
                                                    <a class="dropdown-item" href="{{ route('categorieedit', $categorie->id) }}">
                                                        <i class="icofont icofont-edit"></i> Modifier
                                                    </a>
                                                    <a class="dropdown-item text-danger delete-action" href="javascript:void(0);" data-delete-url="{{ route('categoriedestroy', $categorie->id) }}" data-type="catégorie" data-name="{{ $categorie->title }}" data-csrf="{{ csrf_token() }}">
                                                        <i class="icofont icofont-ui-delete"></i> Supprimer
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('assets/js/MonJs/dropdown/dropdown.js') }}"></script>
    <script src="{{ asset('assets/js/prism/prism.min.js') }}"></script>
    <script src="{{ asset('assets/js/clipboard/clipboard.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom-card/custom-card.js') }}"></script>
    <script src="{{ asset('assets/js/height-equal.js') }}"></script>
    <script src="{{ asset('assets/js/MonJs/datatables/datatables.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
@endpush
@endsection