


@extends('layouts.admin.master')

@section('title') Liste des Chapitres
{{ $title }}
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/prism.css') }}">
<style>
    .highlighted {
        background-color: #ffeb3b !important; /* Couleur de surbrillance */
    }
</style>
@endpush

@section('content')
@component('components.breadcrumb')
    @slot('breadcrumb_title')
        <h3>Liste des Chapitres</h3>
    @endslot
    <li class="breadcrumb-item">Chapitres</li>
    <li class="breadcrumb-item active">Liste des Chapitres</li>
@endcomponent

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Chapitres Disponibles</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success" id="success-message">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('delete'))
                        <div class="alert alert-danger" id="delete-message">
                            {{ session('delete') }}
                        </div>
                    @endif

                    <div class="row project-cards">
                        <div class="col-md-12 project-list">
                            <div class="card">
                                <div class="row">
                                    <div class="col-md-6 p-0"></div>
                                    <div class="col-md-6 p-0">
                                        <a class="btn btn-primary custom-btn" href="{{ route('chapitrecreate') }}">
                                            <i data-feather="plus-square"></i> Ajouter un Chapitre
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="display dataTable" id="chapitres-table">
                            <thead>
                                <tr>
                                    <th>title</th>
                                    <th>Description</th>
                                    <th>Durée</th>
                                    <th>Cours</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($chapitres as $chapitre)
                                    <tr>
                                        <td>{{ $chapitre->title }}</td>
                                        <td>{!! $chapitre->description !!}</td>
                                        <td>{{ $chapitre->duration }}</td>
                                        <td>
                                            <a href="{{ route('cours', ['selected_cours' => $chapitre->Course->id]) }}" class="cours-link" data-cours-id="{{ $chapitre->Course->id }}">
                                                {{ $chapitre->Course->title }}
                                            </a>
                                        </td>
                                        <td>
                                            <i class="icofont icofont-edit edit-icon action-icon" data-edit-url="{{ route('chapitreedit', $chapitre->id) }}" style="cursor: pointer;"></i>
                                            <i class="icofont icofont-ui-delete delete-icon action-icon" data-delete-url="{{ route('chapitredestroy', $chapitre->id) }}" data-csrf="{{ csrf_token() }}" style="cursor: pointer; color: rgb(204, 28, 28);"></i>
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
<script src="{{ asset('assets/js/prism/prism.min.js') }}"></script>
<script src="{{ asset('assets/js/clipboard/clipboard.min.js') }}"></script>
<script src="{{ asset('assets/js/custom-card/custom-card.js') }}"></script>
<script src="{{ asset('assets/js/height-equal.js') }}"></script>
<script src="{{ asset('assets/js/MonJs//actions-icon/actions-icon.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('assets/js/MonJs/datatables/datatables.js') }}"></script>

<script>
    $(document).ready(function () {
        // Récupérer l'ID du cours sélectionné depuis l'URL
        let selectedCoursId = new URLSearchParams(window.location.search).get('selected_cours');

        if (selectedCoursId) {
            $('.cours-link').each(function () {
                if ($(this).data('cours-id') == selectedCoursId) {
                    $(this).addClass('highlighted');
                }
            });
        }
    });
</script>
@endpush
@endsection


{{-- La directive {!! !!} est utilisée pour 

afficher le contenu de la description sans échapper les balises HTML. 
Cela permet de conserver le style (comme les sauts de ligne, les listes,
 les balises <strong>, etc.) qui pourrait 
    être présent dans la description. --}}