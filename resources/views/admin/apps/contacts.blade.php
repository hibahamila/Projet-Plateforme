@extends('layouts.admin.master')

@section('title') Gestion des Utilisateurs & Permissions @endsection

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">

<style>
    .badge-light { background-color: #f8f9fa; color: #212529; }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #7367f0;
        border-color: #7367f0;
        color: #fff;
    }
    #alert-container {
        z-index: 1100;
    }
    .swal-custom-popup {
  border-radius: 5px;
  box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.swal-confirm-button-no-border,
.swal-cancel-button-no-border {
  box-shadow: none !important;
  outline: none !important;
  border: none !important;
  border-radius: 5px !important;
  padding: 10px 24px !important;
}
</style>
@endpush

@section('content')
{{-- CSRF Token pour les requêtes AJAX --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<div id="alert-container" class="position-fixed top-0 end-0 p-3" style="max-width: 600px;"></div>

@component('components.breadcrumb')
    @slot('breadcrumb_title')
        <h3>Gestion des Utilisateurs & Permissions</h3>
    @endslot
    <li class="breadcrumb-item">Administration</li>
    <li class="breadcrumb-item active">Utilisateurs & Permissions</li>
@endcomponent

<div class="container-fluid">
    <div class="email-wrap bookmark-wrap">
        <div class="row">
            <div class="col-xl-3">
                <div class="email-sidebar">
                    <a class="btn btn-primary email-aside-toggle" href="javascript:void(0)">contact filter </a>
                    <div class="email-left-aside">
                        <div class="card">
                            <div class="card-body">
                                <div class="email-app-sidebar left-bookmark">
                                    <ul class="nav main-menu contact-options" role="tablist">
                                        <li class="nav-item">
                                            <button class="badge-light btn-block btn-mail w-100" type="button"
                                                    id="loadCreateUserForm"
                                                    data-create-url="{{ route('admin.users.create') }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal"
                                                    aria-label="Créer un nouvel utilisateur">
                                                <i class="me-2 fas fa-user-plus" aria-hidden="true"></i> Nouvel Utilisateur
                                            </button>
                                        </li>

                                        <li class="nav-item"><span class="main-title"> Vues</span></li>
                                        <li>
                                            <a id="load-users" href="javascript:void(0)"
                                               data-user-url="{{ route('admin.users.index') }}"
                                               aria-label="Afficher la liste des utilisateurs">
                                               <i class="me-2 fas fa-users" aria-hidden="true"></i> Utilisateurs
                                            </a>
                                        </li>
                                        <li>
                                            <a id="load-roles" href="javascript:void(0)"
                                               data-roles-url="{{ route('admin.roles.index') }}"
                                               aria-label="Afficher la liste des rôles">
                                               <i class="me-2 fas fa-user-tag" aria-hidden="true"></i> Rôles
                                            </a>
                                        </li>
                                        @can('gérer des permissions')
                                            <li>
                                                <a id="load-permission" href="javascript:void(0)"
                                                data-permission-url="{{ route('admin.permissions.index') }}"
                                                aria-label="Afficher la liste des permissions">
                                                <i class="me-2 fas fa-key" aria-hidden="true"></i> Permissions
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-9">
                <div id="blog-container" class="card">
                    <div class="card-body text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <p class="mt-2">Chargement des données...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal amélioré -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <!-- Pas d'en-tête fixe, il sera chargé dynamiquement avec le formulaire -->

            <div class="modal-body position-relative p-0">

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/ajax/admin-management.js') }}"></script>
<script src="{{ asset('assets/js/form-validation/form_validation3.js') }}"></script>
@endpush

@endsection
