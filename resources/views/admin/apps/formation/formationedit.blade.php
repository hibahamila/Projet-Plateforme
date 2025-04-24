@extends('layouts.admin.master')

@section('title') Modifier une Formation @endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/dropzone.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/MonCss/formationedit.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/MonCss/custom-calendar.css') }}">

    
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Modifier une formation</h5>
                        <span>Complétez les informations pour modifier la formation</span>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form theme-form">
                            <form class="needs-validation" action="{{ route('formationupdate', $formation->id) }}" method="POST" enctype="multipart/form-data" novalidate>
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col">
                                        <!-- Titre -->
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Titre <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa fa-tag"></i></span>
                                                    <input class="form-control" type="text" id="title" name="title" placeholder="Titre" value="{{ old('title', $formation->title) }}" required />
                                                </div>
                                                <div class="invalid-feedback">Veuillez entrer un Titre valide.</div>
                                            </div>
                                        </div>

                                        <!-- Description -->            
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Description <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <div class="input-group" style="flex-wrap: nowrap;">
                                                    <div class="input-group-text d-flex align-items-stretch" style="height: auto;">
                                                        <i class="fa fa-align-left align-self-center"></i>
                                                    </div>
                                                    <textarea class="form-control" id="description" name="description" placeholder="Description" required>{{ old('description',$formation->description) }}</textarea>
                                                </div>
                                                <div class="invalid-feedback">Veuillez entrer une description valide.</div>
                                            </div>
                                        </div>
                                        <!-- Dates de début et fin -->
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Périodes <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <div class="date-input-group">
                                                    <div class="date-input-container">
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                            <input class="form-control datepicker" type="text" id="start_date" name="start_date" 
                                                                value="{{ old('start_date', \Carbon\Carbon::parse($formation->start_date)->format('d/m/Y')) }}" required />
                                                        </div>
                                                        <small class="text-muted">Date de début</small>
                                                    </div>
                                                    <div class="date-input-container">
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                            <input class="form-control datepicker" type="text" id="end_date" name="end_date" 
                                                                value="{{ old('end_date', \Carbon\Carbon::parse($formation->end_date)->format('d/m/Y')) }}" required />
                                                        </div>
                                                        <small class="text-muted">Date de fin</small>
                                                    </div>
                                                </div>
                                                <div class="invalid-feedback">Veuillez entrer des dates valides (la date de fin doit être après la date de début).</div>
                                            </div>
                                        </div>
                                        <!-- Type -->
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Type <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa fa-list"></i></span>
                                                    <select class="form-select" id="type" name="type" required>
                                                        <option value="payante" {{ old('type', $formation->type) == 'payante' ? 'selected' : '' }}>Payante</option>
                                                        <option value="gratuite" {{ old('type', $formation->type) == 'gratuite' ? 'selected' : '' }}>Gratuite</option>
                                                    </select>
                                                </div>
                                                <div class="invalid-feedback">Veuillez sélectionner un type.</div>
                                            </div>
                                        </div>

                                        <!-- Prix -->
                                        <div class="mb-3 row" id="priceContainer" style="{{ old('type', $formation->type) == 'payante' ? 'display: flex;' : 'display: none;' }}">
                                            <label class="col-sm-2 col-form-label">Prix <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <span class="input-group-text">Dt</span>
                                                    <input class="form-control" 
                                                           type="number" 
                                                           id="price" 
                                                           name="price" 
                                                           placeholder="Ex: 50.000" 
                                                           step="0.001" 
                                                           min="0"
                                                           value="{{ old('price', $formation->price) }}" />
                                                </div>
                                                <small class="text-muted">Format: 000.000 (3 décimales obligatoires)</small>
                                                <div class="invalid-feedback">Veuillez entrer un prix valide (ex: 50.000 ou 45.500)</div>
                                            </div>
                                        </div>

                                        <!-- Remise -->
                                        <div class="mb-3 row" id="discountContainer" style="{{ old('type', $formation->type) == 'payante' ? 'display: flex;' : 'display: none;' }}">
                                            <label class="col-sm-2 col-form-label">Remise (%)</label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa fa-percent"></i></span>
                                                    <input class="form-control" 
                                                           type="number" 
                                                           id="discount" 
                                                           name="discount" 
                                                           placeholder="Ex: 10" 
                                                           min="0" 
                                                           max="100"
                                                           value="{{ old('discount', $formation->discount ?? 0) }}" />
                                                </div>
                                                <small class="text-muted">Entrez un pourcentage de remise (0-100)</small>
                                            </div>
                                        </div>

                                        <!-- Prix final -->
                                        <div class="mb-3 row" id="finalPriceContainer" style="{{ old('type', $formation->type) == 'payante' ? 'display: flex;' : 'display: none;' }}">
                                            <label class="col-sm-2 col-form-label">Prix final</label>
                                            <div class="col-sm-10">
                                                <div class="price-display">
                                                    <span class="original-price" id="originalPriceDisplay">{{ number_format($formation->price, 3) }} DT</span>
                                                    <span class="final-price" id="finalPriceDisplay">{{ number_format($formation->final_price, 3) }} DT</span>
                                                </div>
                                                <input type="hidden" id="final_price" name="final_price" value="{{ old('final_price', $formation->final_price ?? $formation->price) }}">
                                            </div>
                                        </div>

                                        <!-- Catégorie -->
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Catégorie <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <select class="form-select select2-categorie" id="categorie_id" name="category_id" required>
                                                        <option value="" disabled>Choisir une catégorie</option>
                                                        @foreach($categories as $categorie)
                                                            <option value="{{ $categorie->id }}" {{ old('categorie_id', $formation->category_id) == $categorie->id ? 'selected' : '' }}>
                                                                {{ $categorie->title }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="invalid-feedback">Veuillez sélectionner une catégorie valide.</div>
                                            </div>
                                        </div>

                                        <!-- Professeur -->
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Professeur <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <div class="row">
                                                    <div class="col-auto">
                                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                                    </div>
                                                    <div class="col">
                                                        <select id="user_id" class="form-select select2-professeur" name="user_id" required>
                                                            <option value="" disabled>Choisir un professeur</option>
                                                            @foreach($professeurs as $professeur)
                                                                <option value="{{ $professeur->id }}" 
                                                                    {{ $formation->user_id == $professeur->id ? 'selected' : '' }}>
                                                                    {{ $professeur->name }} {{ $professeur->lastname ?? '' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="invalid-feedback">Veuillez sélectionner un professeur valide.</div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Nombre de places <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa fa-users"></i></span>
                                                    <input class="form-control" 
                                                           type="number" 
                                                           id="total_seats" 
                                                           name="total_seats" 
                                                           placeholder="Ex: 20" 
                                                           min="1" 
                                                           value="{{ old('total_seats', $formation->total_seats) }}" 
                                                           required />
                                                </div>
                                                <small class="text-muted">Nombre maximum de participants</small>
                                                <div class="invalid-feedback">Veuillez entrer un nombre de places valide (minimum 1).</div>
                                            </div>
                                        </div>

                                        <!-- Image -->
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Image <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                @if($formation->image)
                                                    <div id="currentImageContainer" class="image-container">
                                                        <img src="{{ asset('storage/' . $formation->image) }}?v={{ time() }}" alt="image" class="centered-image" id="currentImage" />
                                                        <div class="image-actions">
                                                            <button type="button" class="btn" id="deleteImage">
                                                                <i class="fa fa-trash trash-icon" title="Supprimer l'image"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div id="newImagePreview" class="image-preview-container" style="display: none;">
                                                    <img id="previewImage" src="#" alt="Prévisualisation de la nouvelle image" class="image-preview" />
                                                </div>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="imageIcon" style="{{ $formation->image ? 'display: none;' : '' }}">
                                                        <i class="fa fa-image"></i>
                                                    </span>
                                                    <input class="form-control" type="file" id="imageUpload" name="image" accept="image/*" style="{{ $formation->image ? 'display: none;' : '' }}">
                                                </div>
                                                <small class="text-muted d-block text-center my-2">Formats acceptés: JPG, PNG, GIF. Taille max: 2Mo</small>
                                                <button id="restoreImage" type="button" class="btn" style="display: none;">
                                                    <i class="fa fa-undo"></i> Revenir à l'image actuelle
                                                </button>
                                                <input type="hidden" name="delete_image" id="deleteImageInput" value="0">
                                            </div>
                                        </div>
                                        
                                        <!-- Publication Section -->
                                        <div class="mb-3 row">
                                            <div class="col-12">
                                                @if($formation->status)
                                                    <div class="publication-status text-success text-center">
                                                        <i class="fa fa-check-circle"></i> Formation publiée
                                                        @if($formation->publish_date)
                                                            le {{ \Carbon\Carbon::parse($formation->publish_date)->format('d/m/Y H:i') }}
                                                        @endif
                                                    </div>
                                                @elseif($formation->publish_date)
                                                    <div class="publication-status text-muted text-center">
                                                        <i class="fa fa-clock"></i> Publication programmée pour le {{ \Carbon\Carbon::parse($formation->publish_date)->format('d/m/Y H:i') }}
                                                    </div>
                                                @endif

                                                <div class="d-flex justify-content-center mt-3">
                                                    <div class="form-group m-t-15 m-checkbox-inline mb-0 custom-radio-ml">
                                                        <div class="radio radio-primary mx-2">
                                                            <input id="publishNow" type="radio" name="publication_type" value="now" 
                                                                {{ (old('publication_type', $formation->status ? 'now' : ($formation->publish_date ? 'later' : 'now'))) == 'now' ? 'checked' : '' }}>
                                                            <label class="mb-0" for="publishNow">
                                                                {{ $formation->status ? 'Maintenir publiée' : 'Publier immédiatement' }}
                                                            </label>
                                                        </div>
                                                        <div class="radio radio-primary mx-2">
                                                            <input id="publishLater" type="radio" name="publication_type" value="later" 
                                                                {{ (old('publication_type', $formation->status ? 'now' : ($formation->publish_date ? 'later' : 'now'))) == 'later' ? 'checked' : '' }}>
                                                            <label class="mb-0" for="publishLater">
                                                                {{ $formation->status ? 'Dépublier' : 'Programmer la publication' }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Publication Date Container -->
                                                <div id="publishDateContainer" class="mt-3 text-center" 
                                                    style="{{ (old('publication_type', $formation->status ? 'now' : ($formation->publish_date ? 'later' : 'now'))) == 'later' ? 'display: block;' : 'display: none;' }}">
                                                    <div class="d-flex justify-content-center">
                                                        <div class="input-group" style="max-width:500px;">
                                                            <span class="input-group-text"><i class="fa fa-clock"></i></span>
                                                            <input class="form-control" 
                                                                type="datetime-local" 
                                                                id="publish_date" 
                                                                name="publish_date" 
                                                                value="{{ old('publish_date', $formation->publish_date ? \Carbon\Carbon::parse($formation->publish_date)->format('Y-m-d\TH:i') : \Carbon\Carbon::now()->format('Y-m-d\TH:i')) }}"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}">
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">Sélectionnez la date et l'heure de publication</small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Boutons de soumission -->
                                        <div class="row">
                                            <div class="col">
                                                <div class="text-end mt-4">
                                                    <button class="btn btn-primary" type="submit">
                                                        <i class="fa fa-save"></i> Enregistrer
                                                    </button>
                                                    <button class="btn btn-danger" type="button" onclick="window.location.href='{{ route('formations') }}'">
                                                        <i class="fa fa-times"></i> Annuler
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('assets/js/dropzone/dropzone-script.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="{{ asset('assets/js/MonJs/select2-init/single-select.js') }}"></script>
    <script src="{{ asset('assets/js/MonJs/form-validation/form-validation.js') }}"></script>
    <script src="{{ asset('assets/js/MonJs/formations/formation-edit.js') }}"></script>
    <script src="{{ asset('assets/js/MonJs/formations/formation-edit-price.js') }}"></script>
    <script src="{{ asset('assets/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/js/MonJs/description/description.js') }}"></script>
    <script src="{{ asset('assets/js/MonJs/toast/toast.js') }}"></script>
    <script src="{{ asset('assets/js/MonJs/calendar/edit-calendar.js') }}"></script>


    <script src="https://cdn.tiny.cloud/1/cwjxs6s7k08kvxb3t6udodzrwpomhxtehiozsu4fem2igekf/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        // Script pour afficher le toast si un message de succès est présent
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                toast.success('{{ session('success') }}');
            @endif
        });
    </script>
@endpush