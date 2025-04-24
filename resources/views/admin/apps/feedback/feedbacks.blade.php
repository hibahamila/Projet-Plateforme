

@extends('layouts.admin.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h5 class="text-white"><i class="fa fa-comments mr-2"></i>Liste des Feedbacks</h5>
                        <span class="text-white-50">Vous pouvez voir et gérer les feedbacks donnés sur les formations ci-dessous.</span>
                    </div>
                    <div class="card-body">
                        <div class="bg-white p-3 rounded mb-4">
                            <form action="{{ route('deleteSelected') }}" method="POST" id="bulk-delete-form">
                                @csrf
                                <div class="row align-items-center">
                                    <div class="col-md-4 mb-2 mb-md-0">
                                        <div class="d-flex align-items-center">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="select-all">
                                                <label class="custom-control-label" for="select-all">Sélectionner tout</label>
                                            </div>
                                            <span class="badge badge-primary ml-2" id="selected-count-badge">0</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2 mb-md-0">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-filter"></i></span>
                                            </div>
                                            <select id="rate-filter" class="form-control">
                                                <option value="">Toutes les notes</option>
                                                <option value="0.5">0.5 étoile</option>
                                                <option value="1.0">1 étoile</option>
                                                <option value="1.5">1.5 étoiles</option>
                                                <option value="2.0">2 étoiles</option>
                                                <option value="2.5">2.5 étoiles</option>
                                                <option value="3.0">3 étoiles</option>
                                                <option value="3.5">3.5 étoiles</option>
                                                <option value="4.0">4 étoiles</option>
                                                <option value="4.5">4.5 étoiles</option>
                                                <option value="5.0">5 étoiles</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <button type="submit" class="btn btn-danger" id="bulk-delete-btn" disabled>
                                            <i class="fa fa-trash mr-2"></i>Supprimer (<span id="selected-count">0</span>)
                                        </button>
                                        <a href="{{ route('feedbackcreate') }}" class="btn btn-success ml-2">
                                            <i class="fa fa-plus mr-2"></i>Ajouter
                                        </a>
                                    </div>
                                </div>
                        
                                <div class="table-responsive mt-3">
                                    <table class="table table-hover" id="feedback-table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th width="50"></th>
                                                <th>Formation</th>
                                                <th width="150">Note</th>
                                                <th width="100">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($feedbacks as $feedback)
                                                <tr data-rate="{{ $feedback->rating_count !== null ? number_format($feedback->rating_count, 1) : 'null' }}" data-id="{{ $feedback->id }}">
                                                    <td class="text-center">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input feedback-checkbox" id="feedback-{{ $feedback->id }}" name="feedbacks[]" value="{{ $feedback->id }}">
                                                            <label class="custom-control-label" for="feedback-{{ $feedback->id }}"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-light rounded mr-3 text-center">
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0">{{ $feedback->formation->title }}</h6>
                                                                @if(isset($feedback->user))
                                                                    <small class="text-muted">Par: {{ $feedback->user->name ?? 'Anonyme' }}</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if ($feedback->rating_count !== null)
                                                            <span class="d-none">{{ number_format($feedback->rating_count, 1) }}</span>
                                                            <div class="rating-stars">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($feedback->rating_count >= $i)
                                                                        <i class="fa fa-star filled"></i>
                                                                    @elseif ($feedback->rating_count >= ($i - 0.5))
                                                                        <i class="fa fa-star-half-o filled"></i>
                                                                    @else
                                                                        <i class="fa fa-star-o"></i>
                                                                    @endif
                                                                @endfor
                                                                <span class="rating-value ml-2">({{ number_format($feedback->rating_count, 1) }})</span>
                                                            </div>
                                                        @else
                                                            <span class="d-none">null</span>
                                                            <span class="badge badge-light">Aucune note</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <button type="button" class="btn btn-outline-danger delete-button" 
                                                                    data-delete-url="{{ route('feedbackdestroy', $feedback->id) }}" 
                                                                    data-csrf="{{ csrf_token() }}"
                                                                    title="Supprimer">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card mini-stats-wid bg-light">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-muted fw-medium mb-2">Total Feedbacks</p>
                                                <h4 class="mb-0" id="total-feedbacks">{{ count($feedbacks) }}</h4>
                                            </div>
                                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                                <span class="avatar-title">
                                                    <i class="fa fa-comments font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mini-stats-wid bg-light">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-muted fw-medium mb-2">Note Moyenne</p>
                                                <h4 class="mb-0" id="average-rating">
                                                    @php
                                                        $totalRating = 0;
                                                        $ratedCount = 0;
                                                        foreach($feedbacks as $feedback) {
                                                            if ($feedback->rating_count !== null) {
                                                                $totalRating += $feedback->rating_count;
                                                                $ratedCount++;
                                                            }
                                                        }
                                                        echo $ratedCount > 0 ? number_format($totalRating / $ratedCount, 1) : 'N/A';
                                                    @endphp
                                                </h4>
                                            </div>
                                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                                <span class="avatar-title">
                                                    <i class="fa fa-star font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mini-stats-wid bg-light">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-muted fw-medium mb-2">Formations Notées</p>
                                                <h4 class="mb-0" id="rated-formations">
                                                    @php
                                                        $ratedFormations = [];
                                                        foreach($feedbacks as $feedback) {
                                                            if ($feedback->rating_count !== null) {
                                                                $ratedFormations[$feedback->formation->id] = true;
                                                            }
                                                        }
                                                        echo count($ratedFormations);
                                                    @endphp
                                                </h4>
                                            </div>
                                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                                <span class="avatar-title">
                                                    <i class="fa fa-graduation-cap font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmation de suppression</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Êtes-vous sûr de vouloir supprimer ce feedback ? Cette action est irréversible.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete">Supprimer</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/prism/prism.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">

    <script src="{{ asset('assets/js/clipboard/clipboard.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom-card/custom-card.js') }}"></script>
    <script src="{{ asset('assets/js/height-equal.js') }}"></script>
    <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('assets/js/MonJs/feedback/feedback.js') }}"></script>
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
@endpush   














 
