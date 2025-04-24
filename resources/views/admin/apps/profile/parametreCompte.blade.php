{{-- @extends('layouts.admin.master')

@section('title') Modifier le Profil {{ $title }} @endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('assets/css/editProfileCss/parametreCompte.css') }}">
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3><i class="fas fa-user-edit me-2"></i>Modifier le Profil</h3>
        @endslot
        <li class="breadcrumb-item">Utilisateurs</li>
        <li class="breadcrumb-item active">Modifier le Profil</li>
    @endcomponent

    <div class="container-fluid">
        <div class="edit-profile">
            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h4 class="card-title mb-0"><i class="fas fa-id-card me-2"></i>Mon Profil</h4>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-4">
                                <div class="avatar-circle text-white mx-auto">
                                    <span>{{ substr($user->name, 0, 1) }}{{ substr($user->lastname, 0, 1) }}</span>
                                </div>
                            </div>

                            <div class="user-info mb-4">
                                <h5 class="mb-2">{{ $user->name }} {{ $user->lastname }}</h5>
                                <p class="mb-0 text-muted">
                                    <i class="fas fa-user-tag me-2"></i>
                                    {{ $user->roles->first()->name }}
                                </p>
                                <p class="mb-0 text-muted mt-2">
                                    <i class="fas fa-envelope me-2"></i>
                                    {{ $user->email }}
                                </p>

                            </div>

                            <div class="list-group">
                                <a href="#" class="list-group-item list-group-item-action active" id="profile-tab" data-tab="profile">
                                    <i class="fas fa-user"></i> Modifier le Profil
                                </a>
                                <a href="#" class="list-group-item list-group-item-action" id="account-tab" data-tab="account">
                                    <i class="fas fa-cog"></i> Paramètres du Compte
                                </a>
                                <a href="#" class="list-group-item list-group-item-action" id="certification-tab" data-tab="certification">
                                    <i class="fas fa-certificate"></i> Certification
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h4 class="card-title mb-0" id="tab-title">
                                <i class="fas fa-pen me-2"></i>Modifier le Profil
                            </h4>
                        </div>
                        <div class="card-body">
                            <div id="alert-container"></div>
                            <div class="loader" id="content-loader"></div>
                            <div id="tab-content">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

    const PROFILE_URLS = {
        profile: "{{ route('profile.edit') }}",
        account: "{{ route('profile.updateCompte') }}",
        email: "{{ route('profile.updateEmail') }}",
        password: "{{ route('profile.editPassword') }}"

    };
</script>
<script src="{{asset('assets/ajax/profile/editProfile.js')}}"></script>
<script src="{{ asset('assets/js/form-validation/form_validation2.js') }}"></script>
@endpush --}}



@extends('layouts.admin.master')

@section('title') Modifier le Profil {{ $title }} @endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/editProfileCss/parametreCompte.css') }}">
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3><i class="fas fa-user-edit me-2"></i>Modifier le Profil</h3>
        @endslot
        <li class="breadcrumb-item">Utilisateurs</li>
        <li class="breadcrumb-item active">Modifier le Profil</li>
    @endcomponent

    <div class="container-fluid">
        <div class="edit-profile">
            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h4 class="card-title mb-0"><i class="fas fa-id-card me-2"></i>Mon Profil</h4>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-4">
                                <div class="avatar-circle text-white mx-auto">
                                    <span>{{ substr($user->name, 0, 1) }}{{ substr($user->lastname, 0, 1) }}</span>
                                </div>
                            </div>

                            <div class="user-info mb-4">
                                <h5 class="mb-2">{{ $user->name }} {{ $user->lastname }}</h5>
                                <p class="mb-0 text-muted">
                                    <i class="fas fa-user-tag me-2"></i>
                                    {{ $user->roles->first()->name }}
                                </p>
                                <p class="mb-0 text-muted mt-2">
                                    <i class="fas fa-envelope me-2"></i>
                                    {{ $user->email }}
                                </p>
                            </div>

                            <div class="list-group">
                                <a href="#" class="list-group-item list-group-item-action active" id="profile-tab" data-tab="profile">
                                    <i class="fas fa-user"></i> Modifier le Profil
                                </a>
                                <a href="#" class="list-group-item list-group-item-action" id="account-tab" data-tab="account">
                                    <i class="fas fa-cog"></i> Paramètres du Compte
                                </a>
                                <a href="#" class="list-group-item list-group-item-action" id="certification-tab" data-tab="certification">
                                    <i class="fas fa-certificate"></i> Certification
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <div class="card">
                        
                        <div class="card-body">
                            <div id="alert-container"></div>
                            <div class="loader" id="content-loader"></div>
                            <div id="tab-content">
                                <!-- Le contenu sera chargé dynamiquement ici -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const PROFILE_URLS = {
        profile: "{{ route('profile.edit') }}",
        account: "{{ route('profile.updateCompte') }}",
        email: "{{ route('profile.updateEmail') }}",
        password: "{{ route('profile.editPassword') }}",
        checkEmail: "{{ route('profile.checkEmail') }}",
        verifyPassword: "{{ route('profile.verifyPassword') }}",
        sendCode: "{{ route('profile.sendEmailVerificationCode') }}",
        validateCode: "{{ route('profile.validateCode') }}",
        verifyEmail: "{{ route('profile.verifyEmail') }}"
    };
</script>
<script src="{{asset('assets/ajax/profile/editProfile.js')}}"></script>
<script src="{{ asset('assets/js/form-validation/form_validation2.js') }}"></script>
@endpush
