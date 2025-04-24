
@extends('layouts.admin.master')

@section('title') Liste des Leçons
{{ $title }}
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/MonCss/table.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/MonCss/custom-style.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/MonCss/lecons.css') }}"> --}}

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/prism.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
 
@endpush

@section('content')
@component('components.breadcrumb')
    @slot('breadcrumb_title')
        <h3>Liste des Leçons</h3>
    @endslot
    <li class="breadcrumb-item">Leçons</li>
    <li class="breadcrumb-item active">Liste des Leçons</li>
@endcomponent

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Leçons Disponibles</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger" id="success-message" style="display: none;">
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="d-flex justify-content-end mb-3">
                        <a class="btn btn-primary custom-btn" href="{{ route('lessoncreate') }}">
                            <i class="icofont icofont-plus-square"></i> Ajouter une Leçon
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="dataTable display" id="lessons-table">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th>Durée</th>
                                    <th>Chapitre</th>
                                    <th>Fichiers</th>
                                    <th>Liens</th>
                                    <th class="actions-column">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <meta name="csrf-token" content="{{ csrf_token() }}">
                                @foreach ($lessons as $lesson)
                                    <tr>
                                        <td>{{ $lesson->title }}</td>
                                        <td>{!!$lesson->description !!}</td>
                                        <td>{{ $lesson->duration }}</td>
                                        <td>{{ $lesson->chapter->title ?? 'Non attribué' }}</td>
                                        <td>
                                            @if($lesson->files->count() > 0)
                                                <div class="files-container">
                                                    @foreach($lesson->files as $file)
                                                        <div class="file-badge" 
                                                             data-file-name="{{ $file->name }} ({{ formatFileSize($file->file_size) }})">
                                                            <a href="{{ Storage::url($file->file_path) }}" 
                                                               target="_blank" 
                                                               class="file-link">
                                                                <i class="fas {{ getFileIcon($file->file_type) }}"></i>
                                                                {{ Str::limit(basename($file->name), 15) }}
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">Aucun fichier</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($lesson->link)
                                                @php
                                                    $links = json_decode($lesson->link);
                                                    if (json_last_error() !== JSON_ERROR_NONE) {
                                                        $links = [$lesson->link];
                                                    }
                                                @endphp
                                                @foreach($links as $link)
                                                    @if(!empty(trim($link)))
                                                        <div>
                                                            <a href="{{ formatUrl($link) }}" 
                                                               target="_blank"
                                                               class="d-block text-truncate" 
                                                               style="max-width: 150px;"
                                                               title="{{ $link }}">
                                                                <i class="fas fa-external-link-alt"></i> {{ Str::limit($link, 20) }}
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <span class="text-muted">Aucun lien</span>
                                            @endif
                                        </td>
                                        <td class="actions-column">
                                            <div class="dropdown float-right">
                                                <button class="btn btn-sm btn-light dropdown-toggle no-caret" type="button" id="actionMenu{{ $lesson->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="actionMenu{{ $lesson->id }}">
                                                    <a class="dropdown-item" href="{{ route('lessonedit', $lesson->id) }}">
                                                        <i class="icofont icofont-edit"></i> Modifier
                                                    </a>
                                                    <a class="dropdown-item text-danger delete-action" href="javascript:void(0);" data-delete-url="{{ route('lessondestroy', $lesson->id) }}" data-type="lesson" data-name="{{ $lesson->title }}" data-csrf="{{ csrf_token() }}">
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // $('#lessons-table').DataTable({
        //     language: {
        //         url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/fr-FR.json'
        //     },
        //     columnDefs: [
        //         { orderable: false, targets: [6] }
        //     ]
        // });

        // Initialisation des tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Suppression avec confirmation
        $('.delete-action').click(function() {
            const deleteUrl = $(this).data('delete-url');
            const csrfToken = $(this).data('csrf');
            const itemName = $(this).data('name');
            
            if(confirm(`Êtes-vous sûr de vouloir supprimer "${itemName}" ?`)) {
                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        if(response.successMessage) {
                            $('#success-message').text(response.successMessage).show();
                            setTimeout(() => location.reload(), 1500);
                        }
                    },
                    error: function(xhr) {
                        alert('Une erreur est survenue lors de la suppression');
                    }
                });
            }
        });
    });
</script>
@endpush

@php
    // Helper pour obtenir l'icône en fonction du type de fichier
    function getFileIcon($fileType) {
        $icons = [
            'pdf' => 'fa-file-pdf',
            'doc' => 'fa-file-word',
            'docx' => 'fa-file-word',
            'xls' => 'fa-file-excel',
            'xlsx' => 'fa-file-excel',
            'ppt' => 'fa-file-powerpoint',
            'pptx' => 'fa-file-powerpoint',
            'zip' => 'fa-file-archive',
            'rar' => 'fa-file-archive',
            'mp3' => 'fa-file-audio',
            'wav' => 'fa-file-audio',
            'mp4' => 'fa-file-video',
            'avi' => 'fa-file-video',
            'jpg' => 'fa-file-image',
            'jpeg' => 'fa-file-image',
            'png' => 'fa-file-image',
            'gif' => 'fa-file-image',
            'txt' => 'fa-file-alt',
        ];
        
        $extension = strtolower($fileType);
        return $icons[$extension] ?? 'fa-file';
    }

    // Helper pour formater la taille du fichier
    function formatFileSize($bytes) {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return '1 byte';
        } else {
            return '0 bytes';
        }
    }

    // Helper pour formater les URLs
    function formatUrl($url) {
        $url = trim($url);
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }
@endphp
@endsection